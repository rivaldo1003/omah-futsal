<?php

namespace Tests\Feature;

use App\Models\Tournament;
use App\Models\User;
use App\Http\Controllers\TournamentController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Tests\TestCase;

class MultipleTournamentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('tournaments', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->string('organizer')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('status')->default('upcoming');
            $table->string('type')->default('league');
            $table->unsignedInteger('groups_count')->nullable();
            $table->unsignedInteger('teams_per_group')->nullable();
            $table->unsignedInteger('qualify_per_group')->nullable();
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('home_order')->default(0);
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('coach_name')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('tournaments');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_admin_can_open_tournament_creation_while_another_tournament_is_live(): void
    {
        $admin = new User;
        $admin->forceFill([
            'name' => 'Admin Uji',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
            'is_admin' => true,
        ])->save();
        $this->createTournament('Turnamen Berjalan', 'ongoing');

        $this->actingAs($admin)
            ->get(route('admin.tournaments.create'))
            ->assertRedirect(route('admin.tournaments.create.step', ['step' => 1]));

        $stepView = app(TournamentController::class)->createStep(request(), 1);

        $this->assertInstanceOf(View::class, $stepView);
        $this->assertSame('admin.tournaments.create', $stepView->getName());
    }

    private function createTournament(string $name, string $status): Tournament
    {
        return Tournament::query()->create([
            'name' => $name,
            'slug' => str($name)->slug(),
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-30',
            'type' => 'league',
            'status' => $status,
        ]);
    }
}
