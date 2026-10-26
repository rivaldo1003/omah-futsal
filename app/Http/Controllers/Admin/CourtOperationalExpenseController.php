<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourtOperationalExpense;
use App\Models\CourtOperationalExpenseAudit;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CourtOperationalExpenseController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureAdmin();
        $filters = $request->validate([
            'month' => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
        ]);

        $selectedMonth = $filters['month'] ?? CarbonImmutable::now('Asia/Jakarta')->format('Y-m');
        $monthStart = CarbonImmutable::parse($selectedMonth.'-01', 'Asia/Jakarta')->startOfMonth()->toDateString();
        $monthEnd = CarbonImmutable::parse($selectedMonth.'-01', 'Asia/Jakarta')->endOfMonth()->toDateString();
        $expenses = CourtOperationalExpense::withTrashed()
            ->with(['audits'])
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->orderByDesc('expense_date')->orderByDesc('id')->paginate(25)->withQueryString();

        $summary = CourtOperationalExpense::query()
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->selectRaw('COALESCE(SUM(amount), 0) as total, category')
            ->groupBy('category')
            ->get();
        $totalExpense = (float) $summary->sum('total');
        $byCategory = $summary->groupBy('category')->map(fn ($rows) => (float) $rows->sum('total'));
        $selectedMonthLabel = CarbonImmutable::parse($selectedMonth.'-01', 'Asia/Jakarta')->locale('id')->translatedFormat('F Y');

        return view('admin.court-bookings.expenses', compact(
            'expenses', 'selectedMonth', 'selectedMonthLabel', 'totalExpense', 'byCategory',
        ));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data): void {
            $expense = CourtOperationalExpense::query()->create($data + [
                'recorded_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
            $this->audit($expense, 'created', null, $this->snapshot($expense), null);
        });

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function update(Request $request, CourtOperationalExpense $expense)
    {
        $this->ensureAdmin();
        $data = $this->validatedData($request, true);

        DB::transaction(function () use ($expense, $data): void {
            $locked = CourtOperationalExpense::query()->whereKey($expense->id)->lockForUpdate()->firstOrFail();
            $before = $this->snapshot($locked);
            $reason = $data['audit_reason'];
            unset($data['audit_reason']);
            $locked->update($data + ['updated_by' => auth()->id()]);
            $this->audit($locked, 'updated', $before, $this->snapshot($locked), $reason);
        });

        return back()->with('success', 'Pengeluaran berhasil diperbarui dan riwayatnya tersimpan.');
    }

    public function destroy(Request $request, CourtOperationalExpense $expense)
    {
        $this->ensureAdmin();
        $data = $request->validate(['audit_reason' => ['required', 'string', 'min:3', 'max:500']]);

        DB::transaction(function () use ($expense, $data): void {
            $locked = CourtOperationalExpense::query()->whereKey($expense->id)->lockForUpdate()->firstOrFail();
            $before = $this->snapshot($locked);
            $locked->deleted_by = auth()->id();
            $locked->save();
            $this->audit($locked, 'deleted', $before, null, trim($data['audit_reason']));
            $locked->delete();
        });

        return back()->with('success', 'Pengeluaran dihapus dari perhitungan. Riwayat penghapusannya tetap tersimpan.');
    }

    private function validatedData(Request $request, bool $withAuditReason = false): array
    {
        $data = $request->validate([
            'expense_date' => ['required', 'date_format:Y-m-d'],
            'category' => ['required', Rule::in(['salary', 'electricity', 'other'])],
            'title' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'string', 'max:20', 'regex:/^[0-9.,\s]+$/'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'qris', 'unclassified'])],
            'note' => ['nullable', 'string', 'max:2000'],
            'audit_reason' => [$withAuditReason ? 'required' : 'nullable', 'string', 'min:3', 'max:500'],
        ]);
        $amount = preg_replace('/\D+/', '', $data['amount']);
        if ($amount === '' || (float) $amount <= 0 || (float) $amount > 9999999999.99) {
            throw ValidationException::withMessages(['amount' => 'Masukkan nominal pengeluaran yang valid.']);
        }
        $data['amount'] = (float) $amount;
        $data['title'] = trim($data['title']);
        $data['note'] = isset($data['note']) ? trim($data['note']) : null;
        if (!$withAuditReason) {
            unset($data['audit_reason']);
        }

        return $data;
    }

    private function snapshot(CourtOperationalExpense $expense): array
    {
        return [
            'expense_date' => $expense->expense_date?->toDateString(),
            'category' => $expense->category,
            'title' => $expense->title,
            'amount' => (float) $expense->amount,
            'payment_method' => $expense->payment_method,
            'note' => $expense->note,
        ];
    }

    private function audit(CourtOperationalExpense $expense, string $action, ?array $before, ?array $after, ?string $reason): void
    {
        CourtOperationalExpenseAudit::query()->create([
            'expense_id' => $expense->id,
            'actor_id' => auth()->id(),
            'actor_name_snapshot' => auth()->user()?->name,
            'action' => $action,
            'reason' => $reason,
            'before_data' => $before,
            'after_data' => $after,
            'created_at' => now(),
        ]);
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}
