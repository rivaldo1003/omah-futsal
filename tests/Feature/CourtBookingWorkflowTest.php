<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtBookingPayment;
use App\Models\CourtBookingFundSettlement;
use App\Models\CourtBookingSetting;
use App\Models\CourtDailyRevenue;
use App\Models\CourtDailyRevenueEntry;
use App\Models\CourtMemberSchedule;
use App\Models\CourtRentalItem;
use App\Models\CourtRentalOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourtBookingWorkflowTest extends TestCase
{
    private Court $court;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        // Laravel stores unzoned SQL timestamps in the application's UTC timezone.
        Carbon::setTestNow(Carbon::parse('2026-10-20 03:00:00', 'UTC'));
        $this->createBookingSchema();
        $this->court = Court::query()->create([
            'name' => 'Lapangan Uji',
            'hourly_rate' => 300000,
            'day_hourly_rate' => 300000,
            'night_hourly_rate' => 600000,
            'night_rate_starts_at' => '18:00:00',
            'opening_time' => '08:00:00',
            'closing_time' => '23:00:00',
            'slot_duration_minutes' => 60,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        CourtBookingSetting::query()->create([
            'id' => 1,
            'bank_name' => 'Bank Uji',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'OFS Uji',
            'qris_image_path' => 'test/qris.png',
            'payment_instructions' => 'Kirim bukti setelah transfer.',
            'booking_policy' => 'Booking berlaku setelah diverifikasi admin.',
            'payment_hold_minutes' => 60,
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_public_availability_and_booking_detail_pages_render(): void
    {
        CourtRentalItem::query()->create([
            'name' => 'Rompi', 'unit_price' => 20000, 'stock_quantity' => 5,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->get('/booking?date=2026-10-21')
            ->assertOk()
            ->assertSee('Lapangan Uji')
            ->assertSee('Rp 300.000');

        $booking = $this->createPublicBooking();

        $this->get(route('booking.show', $booking->public_token))->assertOk();
        $this->get(route('booking.status', $booking->public_token))
            ->assertOk()
            ->assertJsonPath('status', CourtBooking::STATUS_AWAITING_PAYMENT)
            ->assertHeader('Cache-Control', 'max-age=0, no-store, private');
    }

    public function test_member_schedules_have_a_dedicated_admin_page_and_can_be_paused(): void
    {
        $firstSchedule = CourtMemberSchedule::query()->create([
            'court_id' => $this->court->id,
            'member_name' => 'Elano',
            'day_of_week' => 2,
            'time_start' => '20:00',
            'time_end' => '21:00',
            'is_active' => true,
        ]);
        CourtMemberSchedule::query()->create([
            'court_id' => $this->court->id,
            'member_name' => 'Elano',
            'day_of_week' => 6,
            'time_start' => '20:00',
            'time_end' => '21:00',
            'is_active' => true,
        ]);

        $admin = $this->createAdmin();
        $this->actingAs($admin)
            ->get(route('admin.court-bookings.member-schedules.index'))
            ->assertOk()
            ->assertSee('Jadwal Member')
            ->assertSee('Jadwal terdaftar')
            ->assertSee('2 jadwal')
            ->assertDontSee('Informasi pembayaran');

        $this->actingAs($admin)
            ->get(route('admin.court-bookings.settings'))
            ->assertOk()
            ->assertSee('Informasi pembayaran')
            ->assertDontSee('Jadwal terdaftar');

        $this->from(route('admin.court-bookings.member-schedules.index'))
            ->actingAs($admin)
            ->post(route('admin.court-bookings.member-schedules.days-off.store', $firstSchedule), [
                'off_date' => '2026-10-27',
                'note' => 'Libur khusus',
            ])
            ->assertRedirect(route('admin.court-bookings.member-schedules.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('court_member_schedule_days_off', [
            'court_member_schedule_id' => $firstSchedule->id,
            'off_date' => '2026-10-27',
            'note' => 'Libur khusus',
        ]);

        $this->from(route('admin.court-bookings.member-schedules.index'))
            ->actingAs($admin)
            ->put(route('admin.court-bookings.member-schedules.update', $firstSchedule), [
                'court_id' => $this->court->id,
                'member_name' => 'Elano',
                'member_name_mode' => 'existing',
                'day_of_week' => 2,
                'time_start' => '20:00',
                'time_end' => '21:00',
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.court-bookings.member-schedules.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('court_member_schedules', [
            'id' => $firstSchedule->id,
            'is_active' => false,
        ]);
    }

    public function test_public_booking_uses_server_pricing_and_supports_multiple_consecutive_slots(): void
    {
        $vest = CourtRentalItem::query()->create([
            'name' => 'Rompi', 'unit_price' => 20000, 'stock_quantity' => 5,
            'is_active' => true, 'sort_order' => 1,
        ]);
        $payload = $this->publicBookingPayload([
            'time_start' => '17:00',
            'quoted_duration_minutes' => 120,
            'quoted_amount' => 940000,
            'rental_items' => [$vest->id => 2],
        ]);

        $response = $this->post(route('booking.store'), $payload);
        $booking = CourtBooking::query()->firstOrFail();

        $response->assertRedirect(route('booking.show', $booking->public_token));
        $this->assertSame('17:00:00', $booking->time_start);
        $this->assertSame('19:00:00', $booking->time_end);
        $this->assertSame('120', (string) $booking->duration_minutes);
        $this->assertSame('940000.00', $booking->amount);
        $this->assertSame(CourtBooking::STATUS_AWAITING_PAYMENT, $booking->status);
        $this->assertSame(2, (int) $booking->rentalLines()->firstOrFail()->quantity);
        $this->assertSame('40000.00', $booking->rentalLines()->firstOrFail()->line_total);

        $this->post(route('booking.store'), $payload)
            ->assertRedirect(route('booking.show', $booking->public_token));
        $this->assertSame(1, CourtBooking::query()->count(), 'Retry with the same idempotency key must not create a duplicate.');

        $changedPayload = $payload;
        $changedPayload['time_start'] = '16:00';
        $this->from('/booking')->post(route('booking.store'), $changedPayload)
            ->assertSessionHasErrors('booking_request_key');
        $this->assertSame(1, CourtBooking::query()->count());
    }

    public function test_overlapping_booking_is_rejected_and_expired_hold_releases_its_slot(): void
    {
        $first = $this->createPublicBooking();

        $this->from('/booking')->post(route('booking.store'), $this->publicBookingPayload([
            'booking_request_key' => (string) Str::uuid(),
        ]))->assertSessionHasErrors('time_start');
        $this->assertSame(1, CourtBooking::query()->count());

        $first->update(['expires_at' => now()->subSecond()]);
        $second = $this->createPublicBooking(['booking_request_key' => (string) Str::uuid()]);

        $this->assertSame(CourtBooking::STATUS_EXPIRED, $first->fresh()->status);
        $this->assertSame(CourtBooking::STATUS_AWAITING_PAYMENT, $second->status);
        $this->assertSame(2, CourtBooking::query()->count());
    }

    public function test_past_slot_and_customer_supplied_wrong_price_are_rejected(): void
    {
        $this->from('/booking')->post(route('booking.store'), $this->publicBookingPayload([
            'booking_date' => '2026-10-20',
            'time_start' => '09:00',
        ]))->assertSessionHasErrors('time_start');

        $this->from('/booking')->post(route('booking.store'), $this->publicBookingPayload([
            'quoted_amount' => 1,
        ]))->assertSessionHasErrors('quoted_amount');

        $this->assertSame(0, CourtBooking::query()->count());
    }

    public function test_public_booking_cannot_overlap_a_scheduled_match_at_the_court(): void
    {
        \Illuminate\Support\Facades\DB::table('matches')->insert([
            'match_date' => '2026-10-21', 'venue' => ' Lapangan Uji ', 'status' => 'upcoming',
            'is_cancelled' => false, 'time_start' => '14:00:00', 'time_end' => '15:00:00',
        ]);

        $this->from('/booking')->post(route('booking.store'), $this->publicBookingPayload())
            ->assertSessionHasErrors('time_start');
        $this->assertSame(0, CourtBooking::query()->count());
    }

    public function test_inventory_is_reserved_across_courts_for_overlapping_booking_times(): void
    {
        $secondCourt = Court::query()->create([
            'name' => 'Lapangan Kedua', 'hourly_rate' => 300000,
            'day_hourly_rate' => 300000, 'night_hourly_rate' => 600000,
            'night_rate_starts_at' => '18:00:00', 'opening_time' => '08:00:00',
            'closing_time' => '23:00:00', 'slot_duration_minutes' => 60,
            'is_active' => true, 'sort_order' => 1,
        ]);
        $vest = CourtRentalItem::query()->create([
            'name' => 'Rompi', 'unit_price' => 20000, 'stock_quantity' => 3,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->createPublicBooking(['rental_items' => [$vest->id => 2], 'quoted_amount' => 340000]);

        $this->from('/booking')->post(route('booking.store'), $this->publicBookingPayload([
            'court_id' => $secondCourt->id,
            'booking_request_key' => (string) Str::uuid(),
            'rental_items' => [$vest->id => 2],
            'quoted_amount' => 340000,
        ]))->assertSessionHasErrors('rental_items');

        $this->assertSame(1, CourtBooking::query()->count());
    }

    public function test_payment_proof_requires_admin_verification_and_tracks_deposit_then_settlement(): void
    {
        Storage::fake('local');
        $booking = $this->createPublicBooking();

        $this->post(route('booking.payment-proof', $booking->public_token), [
            'payment_reference' => 'TRX-TEST-1',
            'payment_proof' => UploadedFile::fake()->image('proof.png', 20, 20),
        ])->assertRedirect(route('booking.show', $booking->public_token));

        $booking->refresh();
        $payment = $booking->payments()->firstOrFail();
        $this->assertSame(CourtBooking::STATUS_PENDING_REVIEW, $booking->status);
        $this->assertSame(CourtBookingPayment::STATUS_PENDING_REVIEW, $payment->status);
        $this->assertSame('0.00', $payment->amount);
        Storage::disk('local')->assertExists($payment->payment_proof_path);

        $admin = $this->createAdmin();
        $this->actingAs($admin)->from('/admin/court-bookings')->post(route('admin.court-bookings.confirm', $booking), [
            'verified_amount' => 350000,
        ])->assertSessionHasErrors('booking');
        $this->assertSame(CourtBooking::STATUS_PENDING_REVIEW, $booking->fresh()->status);

        $this->actingAs($admin)->post(route('admin.court-bookings.confirm', $booking), [
            'verified_amount' => 150000,
        ])->assertSessionHasNoErrors();

        $this->assertSame(CourtBooking::STATUS_CONFIRMED, $booking->fresh()->status);
        $this->assertSame(CourtBookingPayment::STAGE_DEPOSIT, $payment->fresh()->stage);
        $this->assertSame('150000.00', $payment->fresh()->verified_amount);

        $this->actingAs($admin)->from('/admin/court-bookings')->post(route('admin.court-bookings.payments.store', $booking), [
            'amount' => 150001,
            'payment_method' => 'cash',
        ])->assertSessionHasErrors('amount');

        $this->actingAs($admin)->post(route('admin.court-bookings.payments.store', $booking), [
            'amount' => 150000,
            'payment_method' => 'cash',
        ])->assertSessionHasNoErrors();

        $this->assertSame(300000.0, (float) $booking->fresh()->payments()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)->sum('verified_amount'));
        $this->assertSame(CourtBookingPayment::STAGE_SETTLEMENT, CourtBookingPayment::query()
            ->where('court_booking_id', $booking->id)->orderByDesc('id')->firstOrFail()->stage);
    }

    public function test_admin_walk_in_booking_records_deposit_and_later_settlement(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $this->get('/admin/court-bookings/manual/create?date=2026-10-21&range=14%3A00%7C15%3A00')
            ->assertOk()
            ->assertSee('Lapangan Uji')
            ->assertSee('Nominal yang diterima sekarang')
            ->assertSee('Sisa setelah pembayaran ini');

        $this->post(route('admin.court-bookings.manual.store'), [
            'court_id' => $this->court->id,
            'booking_date' => '2026-10-21',
            'range' => '14:00|15:00',
            'manual_request_key' => (string) Str::uuid(),
            'customer_name' => 'Pelanggan Walk-in',
            'team_name' => 'Tim Walk-in',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
            'amount_received' => '150000',
        ])->assertRedirect(route('admin.court-bookings.index', ['date' => '2026-10-21']));

        $booking = CourtBooking::query()->firstOrFail();
        $this->assertSame('walk_in', $booking->source);
        $this->assertSame(CourtBooking::STATUS_CONFIRMED, $booking->status);
        $deposit = $booking->payments()->firstOrFail();
        $this->assertSame('150000.00', $deposit->verified_amount);
        $this->assertSame(CourtBookingPayment::STAGE_DEPOSIT, $deposit->stage);

        $this->get('/admin/court-bookings?date=2026-10-21')
            ->assertOk()
            ->assertSee('Tim Walk-in')
            ->assertSee('Sisa tagihan')
            ->assertSee('Rp 150.000');
        $this->get('/admin/court-bookings/reports/daily?date=2026-10-21')->assertOk();

        $this->post(route('admin.court-bookings.payments.store', $booking), [
            'amount' => '150000',
            'payment_method' => 'cash',
        ])->assertSessionHasNoErrors();

        $this->assertSame(300000.0, (float) $booking->fresh()->payments()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)->sum('verified_amount'));
        $this->assertSame(CourtBookingPayment::STAGE_SETTLEMENT, CourtBookingPayment::query()
            ->where('court_booking_id', $booking->id)->orderByDesc('id')->firstOrFail()->stage);

        $this->put(route('admin.court-bookings.reports.daily-revenue.update'), [
            'operating_date' => '2026-10-21',
            'parking_income' => 'Rp 150.000',
            'shop_income' => 'Rp 275.000',
        ])->assertRedirect(route('admin.court-bookings.reports.daily', ['date' => '2026-10-21']));

        $this->assertDatabaseHas('court_daily_revenues', [
            'operating_date' => '2026-10-21',
            'parking_income' => 150000,
            'shop_income' => 275000,
            'updated_by' => $admin->id,
        ]);
    }

    public function test_admin_can_book_the_remaining_current_slot_at_the_full_slot_rate(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-21 08:20:00', CourtBooking::TIMEZONE));
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $this->get('/admin/court-bookings/manual/create?date=2026-10-21&range=now%7C09%3A00')
            ->assertOk()
            ->assertSee('Mulai sekarang')
            ->assertSee('tarif penuh 60 menit')
            ->assertSee('40 menit');

        $this->post(route('admin.court-bookings.manual.store'), [
            'court_id' => $this->court->id,
            'booking_date' => '2026-10-21',
            'range' => 'now|09:00',
            'manual_request_key' => (string) Str::uuid(),
            'customer_name' => 'Pelanggan Langsung',
            'team_name' => 'Tim Langsung',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
            'amount_received' => '300000',
        ])->assertSessionHasNoErrors();

        $booking = CourtBooking::query()->firstOrFail();
        $this->assertSame('08:20:00', $booking->time_start);
        $this->assertSame('09:00:00', $booking->time_end);
        $this->assertSame(40, (int) $booking->duration_minutes);
        $this->assertSame('300000.00', $booking->amount);
        $this->assertStringContainsString('tarif penuh satu slot 60 menit', $booking->payments()->firstOrFail()->note);
    }

    public function test_admin_cannot_book_current_slot_when_less_than_thirty_minutes_remain(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-21 08:31:00', CourtBooking::TIMEZONE));
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $this->get('/admin/court-bookings/manual/create?date=2026-10-21&range=now%7C09%3A00')
            ->assertOk()
            ->assertSee('Slot yang dipilih tidak tersedia lagi.');

        $this->from('/admin/court-bookings/manual/create?date=2026-10-21')
            ->post(route('admin.court-bookings.manual.store'), [
                'court_id' => $this->court->id,
                'booking_date' => '2026-10-21',
                'range' => 'now|09:00',
                'manual_request_key' => (string) Str::uuid(),
                'customer_name' => 'Pelanggan Langsung',
                'team_name' => 'Tim Langsung',
                'customer_phone' => '081234567890',
                'payment_method' => 'cash',
                'amount_received' => '300000',
            ])->assertSessionHasErrors('range');

        $this->assertSame(0, CourtBooking::query()->count());
    }

    public function test_admin_cannot_book_current_slot_that_conflicts_with_a_booking_or_match(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-21 08:20:00', CourtBooking::TIMEZONE));
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $existing = $this->createPublicBooking();
        $existing->update(['time_start' => '08:00:00', 'time_end' => '09:00:00']);

        $payload = [
            'court_id' => $this->court->id,
            'booking_date' => '2026-10-21',
            'range' => 'now|09:00',
            'manual_request_key' => (string) Str::uuid(),
            'customer_name' => 'Pelanggan Langsung',
            'team_name' => 'Tim Langsung',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
            'amount_received' => '300000',
        ];

        $this->from('/admin/court-bookings/manual/create?date=2026-10-21')
            ->post(route('admin.court-bookings.manual.store'), $payload)
            ->assertSessionHasErrors('range');

        $existing->update(['status' => CourtBooking::STATUS_CANCELLED]);
        \Illuminate\Support\Facades\DB::table('matches')->insert([
            'match_date' => '2026-10-21', 'venue' => 'Lapangan Uji', 'status' => 'upcoming',
            'is_cancelled' => false, 'time_start' => '08:00:00', 'time_end' => '09:00:00',
        ]);

        $payload['manual_request_key'] = (string) Str::uuid();
        $this->from('/admin/court-bookings/manual/create?date=2026-10-21')
            ->post(route('admin.court-bookings.manual.store'), $payload)
            ->assertSessionHasErrors('range');

        $this->assertSame(1, CourtBooking::query()->count());
    }

    public function test_walk_in_payment_cannot_exceed_the_booking_total(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin)->from('/admin/court-bookings/manual/create')->post(route('admin.court-bookings.manual.store'), [
            'court_id' => $this->court->id,
            'booking_date' => '2026-10-21',
            'range' => '14:00|15:00',
            'manual_request_key' => (string) Str::uuid(),
            'customer_name' => 'Pelanggan Walk-in',
            'team_name' => 'Tim Walk-in',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
            'amount_received' => '300001',
        ])->assertSessionHasErrors('amount_received');

        $this->assertSame(0, CourtBooking::query()->count());
    }

    public function test_customer_can_add_equipment_to_confirmed_booking_and_inventory_stays_reserved(): void
    {
        Storage::fake('local');
        $admin = $this->createAdmin();
        $this->actingAs($admin)->post(route('admin.court-bookings.manual.store'), [
            'court_id' => $this->court->id,
            'booking_date' => '2026-10-21',
            'range' => '14:00|15:00',
            'manual_request_key' => (string) Str::uuid(),
            'customer_name' => 'Pelanggan Uji',
            'team_name' => 'Tim Uji',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
            'amount_received' => '300000',
        ])->assertSessionHasNoErrors();
        $booking = CourtBooking::query()->firstOrFail();
        $vest = CourtRentalItem::query()->create([
            'name' => 'Rompi', 'unit_price' => 20000, 'stock_quantity' => 5,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->actingAs($admin)->post(route('admin.court-bookings.rental-additions.store', $booking), [
            'request_key' => (string) Str::uuid(),
            'payment_method' => 'bank_transfer',
            'rental_items' => [$vest->id => 2],
        ])->assertSessionHasNoErrors();
        $addition = $booking->rentalAdditions()->firstOrFail();
        $this->assertSame('40000.00', $addition->amount);
        $this->assertSame('awaiting_payment', $addition->status);

        $this->post(route('booking.rental-additions.payment-proof', [$booking->public_token, $addition->id]), [
            'payment_reference' => 'TRX-ADD-1',
            'payment_proof' => UploadedFile::fake()->image('addition-proof.png', 20, 20),
        ])->assertRedirect(route('booking.show', $booking->public_token));
        $this->assertSame('pending_review', $addition->fresh()->status);

        $this->actingAs($admin)->post(route('admin.court-bookings.rental-additions.confirm', [$booking, $addition]))
            ->assertSessionHasNoErrors();
        $this->assertSame('confirmed', $addition->fresh()->status);

        $otherCourt = Court::query()->create([
            'name' => 'Lapangan Kedua', 'hourly_rate' => 300000,
            'day_hourly_rate' => 300000, 'night_hourly_rate' => 600000,
            'night_rate_starts_at' => '18:00:00', 'opening_time' => '08:00:00',
            'closing_time' => '23:00:00', 'slot_duration_minutes' => 60,
            'is_active' => true, 'sort_order' => 1,
        ]);
        $this->from('/booking')->post(route('booking.store'), $this->publicBookingPayload([
            'court_id' => $otherCourt->id,
            'booking_request_key' => (string) Str::uuid(),
            'rental_items' => [$vest->id => 4],
            'quoted_amount' => 380000,
        ]))->assertSessionHasErrors('rental_items');
        $this->assertSame(1, CourtBooking::query()->count());
    }

    public function test_admin_cancellation_releases_an_unpicked_booking_slot(): void
    {
        $booking = $this->createPublicBooking();
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post(route('admin.court-bookings.cancel', $booking), [
            'admin_note' => 'Jadwal dibatalkan atas permintaan pelanggan.',
        ])->assertSessionHasNoErrors();
        $this->assertSame(CourtBooking::STATUS_CANCELLED, $booking->fresh()->status);

        $replacement = $this->createPublicBooking(['booking_request_key' => (string) Str::uuid()]);
        $this->assertSame(CourtBooking::STATUS_AWAITING_PAYMENT, $replacement->status);
        $this->assertSame(2, CourtBooking::query()->count());
    }

    public function test_non_admin_cannot_access_booking_management(): void
    {
        $user = $this->createAdmin('user');

        $this->actingAs($user)->get('/admin/court-bookings')->assertForbidden();
        $this->actingAs($user)->post(route('admin.court-bookings.manual.store'), [])->assertForbidden();
    }

    public function test_equipment_catalog_entry_point_redirects_into_lapangan_booking(): void
    {
        $this->get('/sewa-perlengkapan')->assertRedirect(route('booking.index'));
    }

    public function test_daily_and_revenue_reports_show_old_bookings_tournament_income_and_method_totals(): void
    {
        $admin = $this->createAdmin();
        $date = '2026-10-01';
        $tournamentId = \Illuminate\Support\Facades\DB::table('tournaments')->insertGetId(['name' => 'OFS Cup']);
        $homeTeamId = \Illuminate\Support\Facades\DB::table('teams')->insertGetId(['name' => 'Bayu FC']);
        $awayTeamId = \Illuminate\Support\Facades\DB::table('teams')->insertGetId(['name' => 'Garuda FC']);
        \Illuminate\Support\Facades\DB::table('matches')->insert([
            'match_date' => $date,
            'time_start' => '18:00:00',
            'time_end' => '19:00:00',
            'team_home_id' => $homeTeamId,
            'team_away_id' => $awayTeamId,
            'tournament_id' => $tournamentId,
            'venue' => $this->court->name,
            'status' => 'upcoming',
            'is_cancelled' => false,
        ]);

        $activeBooking = CourtBooking::query()->create([
            'public_token' => (string) Str::uuid(),
            'court_id' => $this->court->id,
            'booking_date' => $date,
            'time_start' => '20:00:00',
            'time_end' => '21:30:00',
            'duration_minutes' => 90,
            'amount' => 80000,
            'team_name' => 'Tim Transfer',
            'customer_name' => 'Pelanggan A',
            'customer_phone' => '081234567890',
            'payment_method' => 'bank_transfer',
            'source' => 'walk_in',
            'status' => CourtBooking::STATUS_CONFIRMED,
        ]);
        $activeBooking->payments()->create([
            'amount' => 80000,
            'verified_amount' => 80000,
            'stage' => CourtBookingPayment::STAGE_FULL,
            'payment_method' => 'bank_transfer',
            'status' => CourtBookingPayment::STATUS_CONFIRMED,
            'recorded_at' => now(),
            'verified_at' => now(),
        ]);

        $cancelledBooking = CourtBooking::query()->create([
            'public_token' => (string) Str::uuid(),
            'court_id' => $this->court->id,
            'booking_date' => $date,
            'time_start' => '21:30:00',
            'time_end' => '22:00:00',
            'duration_minutes' => 30,
            'amount' => 30000,
            'team_name' => 'Tim Refund',
            'customer_name' => 'Pelanggan B',
            'customer_phone' => '081234567891',
            'payment_method' => 'cash',
            'source' => 'walk_in',
            'status' => CourtBooking::STATUS_CANCELLED,
        ]);
        $cancelledBooking->payments()->create([
            'amount' => 20000,
            'verified_amount' => 20000,
            'stage' => CourtBookingPayment::STAGE_DEPOSIT,
            'payment_method' => 'cash',
            'status' => CourtBookingPayment::STATUS_CONFIRMED,
            'recorded_at' => now(),
            'verified_at' => now(),
        ]);
        $cancelledBooking->fundSettlements()->create([
            'request_key' => (string) Str::uuid(),
            'type' => CourtBookingFundSettlement::TYPE_REFUND,
            'amount' => 20000,
            'payment_method' => 'cash',
            'reason' => 'Refund penuh',
            'recorded_by' => $admin->id,
            'recorded_at' => now(),
        ]);

        CourtRentalOrder::query()->create([
            'rental_date' => $date,
            'time_start' => '18:00:00',
            'time_end' => '19:00:00',
            'rental_mode' => 'scheduled',
            'amount' => 20000,
            'payment_method' => 'qris',
            'status' => CourtRentalOrder::STATUS_CONFIRMED,
        ]);

        CourtDailyRevenue::query()->create([
            'operating_date' => $date,
            'parking_income' => 5000,
            'parking_cash_income' => 3000,
            'parking_transfer_income' => 2000,
            'parking_qris_income' => 0,
            'parking_unclassified_income' => 0,
            'shop_income' => 5000,
            'shop_cash_income' => 0,
            'shop_transfer_income' => 0,
            'shop_qris_income' => 5000,
            'shop_unclassified_income' => 0,
        ]);
        CourtDailyRevenueEntry::query()->create([
            'operating_date' => $date,
            'title' => 'Sewa lapangan turnamen',
            'amount' => 100000,
            'payment_method' => 'cash',
            'tournament_id' => $tournamentId,
            'court_id' => $this->court->id,
            'updated_by' => $admin->id,
        ]);
        CourtDailyRevenueEntry::query()->create([
            'operating_date' => $date,
            'title' => 'Sponsor',
            'amount' => 50000,
            'payment_method' => 'bank_transfer',
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.court-bookings.reports.daily', ['date' => $date]))
            ->assertOk()
            ->assertSee('Tim Transfer')
            ->assertSee('Jadwal pertandingan')
            ->assertSee('OFS Cup')
            ->assertSee('Bayu FC vs Garuda FC')
            ->assertSee('Pemasukan diakui menurut metode pembayaran')
            ->assertSee('Rp 103.000')
            ->assertSee('Rp 132.000')
            ->assertSee('Rp 25.000')
            ->assertSee('Rp 260.000');

        $this->actingAs($admin)
            ->get(route('admin.court-bookings.revenue'))
            ->assertOk()
            ->assertSee('Pemasukan diakui berdasarkan metode pembayaran')
            ->assertSee('Rp 103.000')
            ->assertSee('Rp 132.000')
            ->assertSee('Rp 25.000')
            ->assertSee('Rp 260.000');
    }

    public function test_team_hours_report_includes_recorded_member_and_walk_in_bookings_for_selected_year(): void
    {
        $admin = $this->createAdmin();

        foreach ([
            ['Rivers FC', 'member', CourtBooking::STATUS_CONFIRMED, '2026-02-01', 90],
            ['Rivers FC', 'walk_in', CourtBooking::STATUS_CONFIRMED, '2026-07-01', 60],
            ['Rivers FC', 'walk_in', CourtBooking::STATUS_CANCELLED, '2026-08-01', 120],
            ['Tim Tidak Aktif', 'public', CourtBooking::STATUS_AWAITING_PAYMENT, '2026-09-01', 30],
            ['Rivers FC', 'walk_in', CourtBooking::STATUS_CONFIRMED, '2025-12-31', 180],
        ] as [$teamName, $source, $status, $date, $duration]) {
            CourtBooking::query()->create([
                'public_token' => (string) Str::uuid(),
                'court_id' => $this->court->id,
                'booking_date' => $date,
                'time_start' => '14:00:00',
                'time_end' => '15:00:00',
                'duration_minutes' => $duration,
                'amount' => 300000,
                'team_name' => $teamName,
                'customer_name' => $teamName,
                'customer_phone' => '081234567890',
                'payment_method' => $source === 'member' ? 'none' : 'cash',
                'source' => $source,
                'status' => $status,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.court-bookings.reports.team-hours', ['year' => 2026]))
            ->assertOk()
            ->assertSee('Tahun kalender 2026')
            ->assertSee('Rivers FC')
            ->assertSee('2 jam 30 menit')
            ->assertDontSee('Tim Tidak Aktif');
    }

    public function test_daily_revenue_inputs_save_method_totals_without_losing_legacy_split(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->put(route('admin.court-bookings.reports.daily-revenue.update'), [
                'operating_date' => '2026-10-01',
                'parking_cash_income' => 'Rp 100.000',
                'parking_transfer_income' => 'Rp 25.000',
                'parking_qris_income' => '0',
                'parking_unclassified_income' => 'Rp 50.000',
                'shop_cash_income' => '0',
                'shop_transfer_income' => 'Rp 60.000',
                'shop_qris_income' => 'Rp 40.000',
                'shop_unclassified_income' => '0',
            ])
            ->assertRedirect(route('admin.court-bookings.reports.daily', ['date' => '2026-10-01']))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('court_daily_revenues', [
            'operating_date' => '2026-10-01',
            'parking_income' => 175000,
            'parking_cash_income' => 100000,
            'parking_transfer_income' => 25000,
            'parking_unclassified_income' => 50000,
            'shop_income' => 100000,
            'shop_transfer_income' => 60000,
            'shop_qris_income' => 40000,
        ]);
    }

    private function createPublicBooking(array $overrides = []): CourtBooking
    {
        $payload = $this->publicBookingPayload($overrides);
        $this->post(route('booking.store'), $payload)->assertSessionHasNoErrors();

        return CourtBooking::query()->latest('id')->firstOrFail();
    }

    private function publicBookingPayload(array $overrides = []): array
    {
        return array_replace([
            'court_id' => $this->court->id,
            'booking_date' => '2026-10-21',
            'time_start' => '14:00',
            'customer_name' => 'Pelanggan Uji',
            'team_name' => 'Tim Uji',
            'customer_phone' => '081234567890',
            'customer_email' => 'customer@example.test',
            'payment_method' => 'bank_transfer',
            'booking_request_key' => (string) Str::uuid(),
            'quoted_duration_minutes' => 60,
            'quoted_amount' => 300000,
            'booking_policy_hash' => hash('sha256', 'Booking berlaku setelah diverifikasi admin.'),
            'accept_booking_rules' => '1',
        ], $overrides);
    }

    private function createAdmin(string $role = 'admin'): User
    {
        $user = new User;
        $user->forceFill([
            'name' => 'Admin Uji', 'email' => uniqid('admin-', true).'@example.test',
            'password' => 'password', 'role' => $role, 'is_admin' => $role === 'admin',
        ])->save();

        return $user;
    }

    private function createBookingSchema(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('role')->default('user');
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });
        Schema::create('matches', function (Blueprint $table): void {
            $table->id();
            $table->date('match_date')->nullable();
            $table->unsignedBigInteger('tournament_id')->nullable();
            $table->unsignedBigInteger('team_home_id')->nullable();
            $table->unsignedBigInteger('team_away_id')->nullable();
            $table->string('venue')->nullable();
            $table->string('status')->default('upcoming');
            $table->boolean('is_cancelled')->default(false);
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
        });
        Schema::create('tournaments', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        Schema::create('teams', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        Schema::create('news_articles', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
        Schema::create('courts', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('hourly_rate', 12, 2);
            $table->decimal('day_hourly_rate', 12, 2)->nullable();
            $table->decimal('night_hourly_rate', 12, 2)->nullable();
            $table->time('night_rate_starts_at')->default('18:00:00');
            $table->time('opening_time');
            $table->time('closing_time');
            $table->unsignedSmallInteger('slot_duration_minutes')->default(60);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('court_member_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->string('member_name', 120);
            $table->unsignedTinyInteger('day_of_week');
            $table->time('time_start');
            $table->time('time_end');
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('notes', 500)->nullable();
            $table->timestamps();
        });
        Schema::create('court_member_schedule_days_off', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('court_member_schedule_id')->constrained('court_member_schedules')->cascadeOnDelete();
            $table->date('off_date');
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->unique(['court_member_schedule_id', 'off_date']);
        });
        Schema::create('court_booking_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('qris_image_path')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->text('booking_policy')->nullable();
            $table->unsignedSmallInteger('payment_hold_minutes')->default(60);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
        Schema::create('court_rental_items', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->unsignedSmallInteger('stock_quantity')->default(0);
            $table->boolean('is_active')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('court_bookings', function (Blueprint $table): void {
            $table->id();
            $table->uuid('public_token')->unique();
            $table->uuid('idempotency_key')->nullable()->unique();
            $table->char('request_fingerprint', 64)->nullable();
            $table->unsignedBigInteger('court_id');
            $table->date('booking_date');
            $table->time('time_start');
            $table->time('time_end');
            $table->unsignedSmallInteger('duration_minutes');
            $table->decimal('amount', 12, 2);
            $table->string('team_name')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('payment_method');
            $table->string('source')->default('public');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->string('payment_proof_original_name')->nullable();
            $table->string('payment_bank_name')->nullable();
            $table->string('payment_bank_account_number')->nullable();
            $table->string('payment_bank_account_name')->nullable();
            $table->string('payment_qris_image_path')->nullable();
            $table->text('payment_instructions_snapshot')->nullable();
            $table->text('booking_policy_snapshot')->nullable();
            $table->string('status')->default('awaiting_payment');
            $table->text('admin_note')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('rental_picked_up_at')->nullable();
            $table->timestamp('rental_returned_at')->nullable();
            $table->timestamps();
        });
        Schema::create('court_booking_rental_lines', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('court_booking_id');
            $table->unsignedBigInteger('court_rental_item_id')->nullable();
            $table->string('item_name_snapshot');
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
        });
        Schema::create('court_booking_payments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('court_booking_id');
            $table->decimal('amount', 12, 2);
            $table->decimal('claimed_amount', 12, 2)->nullable();
            $table->decimal('verified_amount', 12, 2)->nullable();
            $table->string('stage');
            $table->string('payment_method');
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->string('payment_proof_original_name')->nullable();
            $table->string('status')->default('pending_review');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
        Schema::create('court_rental_orders', function (Blueprint $table): void {
            $table->id();
            $table->uuid('public_token')->nullable();
            $table->uuid('idempotency_key')->nullable();
            $table->string('request_fingerprint')->nullable();
            $table->date('rental_date');
            $table->time('time_start');
            $table->time('time_end');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->string('rental_mode')->default('scheduled');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('source')->default('public');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('status')->default('awaiting_payment');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rental_picked_up_at')->nullable();
            $table->timestamp('rental_returned_at')->nullable();
            $table->timestamps();
        });
        Schema::create('court_rental_order_lines', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('court_rental_order_id');
            $table->unsignedBigInteger('court_rental_item_id')->nullable();
            $table->string('item_name_snapshot');
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
        });
        Schema::create('court_booking_rental_additions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('court_booking_id');
            $table->uuid('idempotency_key')->unique();
            $table->char('request_fingerprint', 64);
            $table->decimal('amount', 12, 2);
            $table->string('payment_method');
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->string('payment_proof_original_name')->nullable();
            $table->string('payment_bank_name')->nullable();
            $table->string('payment_bank_account_number')->nullable();
            $table->string('payment_bank_account_name')->nullable();
            $table->string('payment_qris_image_path')->nullable();
            $table->text('payment_instructions_snapshot')->nullable();
            $table->string('status')->default('awaiting_payment');
            $table->text('admin_note')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('court_booking_rental_addition_lines', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('court_booking_rental_addition_id');
            $table->unsignedBigInteger('court_rental_item_id')->nullable();
            $table->string('item_name_snapshot');
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
        });
        Schema::create('court_daily_revenues', function (Blueprint $table): void {
            $table->id();
            $table->date('operating_date')->unique();
            $table->decimal('parking_income', 12, 2)->default(0);
            $table->decimal('shop_income', 12, 2)->default(0);
            foreach (['parking', 'shop'] as $source) {
                foreach (['cash', 'transfer', 'qris', 'unclassified'] as $method) {
                    $table->decimal("{$source}_{$method}_income", 12, 2)->default(0);
                }
            }
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
        Schema::create('court_daily_revenue_entries', function (Blueprint $table): void {
            $table->id();
            $table->date('operating_date');
            $table->string('title', 120);
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 30)->default('unclassified');
            $table->unsignedBigInteger('tournament_id')->nullable();
            $table->unsignedBigInteger('court_id')->nullable();
            $table->string('note', 500)->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('court_booking_fund_settlements', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('court_booking_id');
            $table->uuid('request_key')->unique();
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->string('reason');
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->string('recorded_by_name_snapshot')->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->unsignedBigInteger('voided_by')->nullable();
            $table->string('voided_by_name_snapshot')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->string('void_reason')->nullable();
            $table->timestamps();
        });
    }
}
