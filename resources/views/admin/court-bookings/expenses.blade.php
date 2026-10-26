@extends('layouts.admin')

@section('title', 'Pengeluaran Operasional')

@section('styles')
<style>
    .expense-page {
        --ink: #111111;
        --muted: #707072;
        --line: #e5e5e5;
        --soft: #f5f5f5;
        max-width: 1440px;
        margin: 0 auto;
        color: var(--ink);
    }

    .expense-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 18px 24px;
        margin-bottom: 24px;
    }

    .expense-header h1 {
        margin: 0 0 7px;
        font-size: clamp(1.75rem, 2.5vw, 2rem);
        font-weight: 500;
        line-height: 1.2;
    }

    .expense-header p {
        max-width: 68ch;
        margin: 0;
        color: var(--muted);
    }

    .expense-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .expense-page .btn {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 17px;
        border: 0;
        border-radius: 9999px;
        background: var(--soft);
        color: var(--ink);
        font-weight: 500;
        box-shadow: none;
    }

    .expense-page .btn:hover {
        background: #e9e9e9;
        color: var(--ink);
    }

    .expense-page .btn-primary {
        background: var(--ink);
        color: #ffffff;
    }

    .expense-page .btn-primary:hover {
        background: #39393b;
        color: #ffffff;
    }

    .expense-page .btn-outline-danger {
        border: 1px solid #b42318;
        background: #ffffff;
        color: #b42318;
    }

    .expense-page .btn-outline-danger:hover {
        background: #fff5f4;
        color: #8f1c13;
    }

    .expense-panel {
        overflow: hidden;
        margin-bottom: 24px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: #ffffff;
    }

    .expense-panel-heading {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px 24px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--line);
    }

    .expense-panel-heading h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 500;
    }

    .expense-panel-heading p {
        margin: 4px 0 0;
        color: var(--muted);
        font-size: 14px;
    }

    .expense-panel-body {
        padding: 24px;
    }

    .expense-filter {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 10px;
    }

    .expense-filter label,
    .expense-form label,
    .expense-edit label {
        display: block;
        margin: 0 0 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .expense-filter .form-control,
    .expense-form .form-control,
    .expense-form .form-select,
    .expense-edit .form-control,
    .expense-edit .form-select {
        min-height: 44px;
        border: 1px solid #cacacb;
        border-radius: 8px;
        box-shadow: none;
    }

    .expense-filter .form-control {
        width: 190px;
    }

    .expense-filter .form-control:focus,
    .expense-form .form-control:focus,
    .expense-form .form-select:focus,
    .expense-edit .form-control:focus,
    .expense-edit .form-select:focus {
        border-color: #555555;
        box-shadow: 0 0 0 3px rgb(17 17 17 / 10%);
    }

    .expense-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border-bottom: 1px solid var(--line);
    }

    .expense-stat {
        min-width: 0;
        padding: 20px 22px;
    }

    .expense-stat + .expense-stat {
        border-left: 1px solid var(--line);
    }

    .expense-stat--total {
        border-left: 4px solid var(--ink);
        background: var(--soft);
    }

    .expense-stat span {
        display: block;
        color: var(--muted);
        font-size: 13px;
    }

    .expense-stat strong {
        display: block;
        margin-top: 5px;
        font-size: 20px;
        font-weight: 500;
        line-height: 1.3;
        font-variant-numeric: tabular-nums;
        overflow-wrap: anywhere;
    }

    .expense-stat--total strong {
        color: var(--ink);
        font-size: 24px;
        font-weight: 650;
    }

    .expense-entry-heading {
        margin-bottom: 18px;
    }

    .expense-entry-heading h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .expense-entry-heading p {
        margin: 4px 0 0;
        color: var(--muted);
        font-size: 13px;
    }

    .expense-form,
    .expense-edit {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 16px;
    }

    .expense-field {
        grid-column: span 3;
        min-width: 0;
    }

    .expense-field--wide {
        grid-column: span 6;
    }

    .expense-field--full {
        grid-column: 1 / -1;
    }

    .expense-form .input-group-text,
    .expense-edit .input-group-text {
        border-color: #cacacb;
        border-radius: 8px 0 0 8px;
        background: var(--soft);
        color: var(--muted);
    }

    .expense-form .input-group .form-control,
    .expense-edit .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    .expense-form-actions {
        grid-column: 1 / -1;
        display: flex;
        justify-content: flex-end;
        padding-top: 2px;
    }

    .expense-row {
        padding: 18px 24px;
        border-bottom: 1px solid var(--line);
    }

    .expense-row:last-child {
        border-bottom: 0;
    }

    .expense-row-main {
        display: grid;
        grid-template-columns: minmax(220px, 1.5fr) minmax(100px, .7fr) minmax(130px, 1fr) minmax(max-content, .8fr) auto;
        align-items: center;
        gap: 16px;
    }

    .expense-row-title {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px 10px;
        font-weight: 500;
    }

    .expense-row-meta {
        margin-top: 4px;
        color: var(--muted);
        font-size: 13px;
    }

    .expense-row-method {
        color: #39393b;
        font-size: 14px;
    }

    .expense-row-note {
        color: var(--muted);
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .expense-row-amount {
        text-align: right;
        font-size: 16px;
        font-weight: 600;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .expense-row-actions {
        display: flex;
        justify-content: flex-end;
    }

    .expense-row details {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--line);
    }

    .expense-row summary {
        width: max-content;
        max-width: 100%;
        cursor: pointer;
        color: #333333;
        font-size: 13px;
        font-weight: 500;
    }

    .expense-edit-modal .modal-dialog {
        width: calc(100% - 24px);
        max-width: 760px;
        margin: 12px auto;
    }

    .expense-edit-modal .modal-content {
        max-height: calc(100vh - 24px);
        max-height: calc(100dvh - 24px);
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 8px;
    }

    .expense-edit-modal form {
        display: flex;
        flex: 1 1 auto;
        min-height: 0;
        flex-direction: column;
        overflow: hidden;
    }

    .expense-edit-modal .modal-header,
    .expense-edit-modal .modal-footer {
        flex: 0 0 auto;
        padding: 18px 22px;
        background: #ffffff;
    }

    .expense-edit-modal .modal-title {
        font-size: 19px;
        font-weight: 600;
    }

    .expense-edit-modal .modal-body {
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
        padding: 22px;
    }

    .expense-edit-modal .modal-footer {
        gap: 8px;
        border-top: 1px solid var(--line);
    }

    .expense-edit-modal .modal-footer .btn {
        min-height: 44px;
    }

    .expense-edit-form {
        gap: 16px;
    }

    .expense-audit {
        margin: 14px 0 0;
        padding: 12px 14px;
        border-left: 2px solid #bdbdbd;
        background: var(--soft);
        font-size: 13px;
    }

    .expense-audit + .expense-audit {
        margin-top: 8px;
    }

    .expense-audit p {
        margin: 4px 0 0;
        color: var(--muted);
    }

    .expense-deleted {
        color: #7b1d16;
    }

    .expense-empty {
        padding: 36px 20px;
        color: var(--muted);
        text-align: center;
    }

    @media (max-width: 1024px) {
        .expense-row-main {
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 8px 16px;
        }

        .expense-row-method {
            grid-column: 1;
            grid-row: 2;
        }

        .expense-row-note {
            grid-column: 1;
            grid-row: 3;
        }

        .expense-row-amount {
            grid-column: 2;
            grid-row: 1;
        }

        .expense-row-actions {
            grid-column: 2;
            grid-row: 2 / span 2;
            align-self: center;
        }
    }

    @media (max-width: 850px) {
        .expense-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .expense-stat:nth-child(3) {
            border-top: 1px solid var(--line);
            border-left: 0;
        }

        .expense-stat:nth-child(4) {
            border-top: 1px solid var(--line);
        }

        .expense-field,
        .expense-field--wide {
            grid-column: span 6;
        }
    }

    @media (max-width: 560px) {
        .expense-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .expense-actions {
            width: 100%;
        }

        .expense-actions .btn {
            flex: 1 1 auto;
            padding-inline: 12px;
        }

        .expense-panel-heading,
        .expense-panel-body {
            padding: 16px;
        }

        .expense-panel-heading {
            align-items: stretch;
        }

        .expense-filter {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
        }

        .expense-filter > div,
        .expense-filter .form-control {
            width: 100%;
            min-width: 0;
        }

        .expense-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .expense-stat {
            padding: 15px 16px;
        }

        .expense-stat strong {
            font-size: 17px;
        }

        .expense-stat--total strong {
            font-size: 19px;
        }

        .expense-field,
        .expense-field--wide {
            grid-column: 1 / -1;
        }

        .expense-form-actions .btn {
            width: 100%;
        }

        .expense-row {
            padding: 16px;
        }

        .expense-row-main {
            gap: 8px 12px;
        }

        .expense-row-amount {
            font-size: 14px;
        }

        .expense-row-actions .btn {
            min-height: 40px;
            padding-inline: 13px;
        }

        .expense-edit-modal .modal-dialog {
            width: calc(100% - 16px);
            margin: 8px auto;
        }

        .expense-edit-modal .modal-content {
            max-height: calc(100vh - 16px);
            max-height: calc(100dvh - 16px);
        }

        .expense-edit-modal .modal-header,
        .expense-edit-modal .modal-body,
        .expense-edit-modal .modal-footer {
            padding: 16px;
        }

        .expense-edit-form .expense-field,
        .expense-edit-form .expense-field--wide {
            grid-column: 1 / -1;
        }

        .expense-edit-modal .modal-footer {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .expense-edit-modal .modal-footer .btn {
            width: 100%;
            padding-inline: 10px;
        }
    }

    @media (max-width: 360px) {
        .expense-edit-modal .modal-footer {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<main class="expense-page">
    <header class="expense-header">
        <div>
            <h1>Pengeluaran operasional</h1>
            <p>Catat gaji, listrik, dan biaya lain. Koreksi dan penghapusan menyimpan riwayat admin.</p>
        </div>
        <nav class="expense-actions" aria-label="Navigasi laporan">
            <a class="btn" href="{{ route('admin.court-bookings.revenue') }}"><i class="bi bi-cash-stack" aria-hidden="true"></i>Laporan pemasukan</a>
            <a class="btn" href="{{ route('admin.court-bookings.reports.daily') }}"><i class="bi bi-clipboard-data" aria-hidden="true"></i>Laporan operasional</a>
        </nav>
    </header>

    @if(session('success')) <div class="alert alert-success" role="status">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger" role="alert">Periksa kembali data pengeluaran. {{ $errors->first() }}</div> @endif

    <section class="expense-panel" aria-labelledby="expenseMonthTitle">
        <header class="expense-panel-heading">
            <div><h2 id="expenseMonthTitle">Ringkasan {{ $selectedMonthLabel }}</h2><p>Pengeluaran yang dihapus tidak masuk ke total.</p></div>
            <form class="expense-filter" method="GET" action="{{ route('admin.court-bookings.expenses.index') }}">
                <div><label for="expenseMonth">Periode</label><input class="form-control" id="expenseMonth" type="month" name="month" value="{{ $selectedMonth }}" required></div>
                <button class="btn btn-primary" type="submit">Tampilkan</button>
            </form>
        </header>
        <div class="expense-stats">
            <div class="expense-stat expense-stat--total"><span>Total pengeluaran</span><strong>Rp {{ number_format($totalExpense, 0, ',', '.') }}</strong></div>
            <div class="expense-stat"><span>Gaji</span><strong>Rp {{ number_format($byCategory->get('salary', 0), 0, ',', '.') }}</strong></div>
            <div class="expense-stat"><span>Listrik</span><strong>Rp {{ number_format($byCategory->get('electricity', 0), 0, ',', '.') }}</strong></div>
            <div class="expense-stat"><span>Lain-lain</span><strong>Rp {{ number_format($byCategory->get('other', 0), 0, ',', '.') }}</strong></div>
        </div>
        <div class="expense-panel-body">
            <div class="expense-entry-heading">
                <h3>Catat pengeluaran</h3>
                <p>Isi tanggal, kategori, nominal, dan metode pembayaran untuk setiap biaya.</p>
            </div>
            <form class="expense-form" method="POST" action="{{ route('admin.court-bookings.expenses.store') }}">
                @csrf
                <div class="expense-field"><label for="newExpenseDate">Tanggal pengeluaran</label><input class="form-control" id="newExpenseDate" type="date" name="expense_date" value="{{ old('expense_date', now(\App\Models\CourtBooking::TIMEZONE)->toDateString()) }}" required></div>
                <div class="expense-field"><label for="newExpenseCategory">Kategori</label><select class="form-select" id="newExpenseCategory" name="category" required><option value="salary">Gaji</option><option value="electricity">Listrik</option><option value="other">Lain-lain</option></select></div>
                <div class="expense-field expense-field--wide"><label for="newExpenseTitle">Nama pengeluaran</label><input class="form-control" id="newExpenseTitle" name="title" maxlength="150" value="{{ old('title') }}" placeholder="Contoh: Gaji petugas Oktober" required></div>
                <div class="expense-field"><label for="newExpenseAmountDisplay">Nominal</label><div class="input-group"><span class="input-group-text">Rp</span><input class="form-control js-expense-money" id="newExpenseAmountDisplay" type="text" inputmode="numeric" autocomplete="off" placeholder="0" data-hidden-target="newExpenseAmount"><input type="hidden" id="newExpenseAmount" name="amount" value="{{ old('amount') }}"></div></div>
                <div class="expense-field"><label for="newExpenseMethod">Metode pembayaran</label><select class="form-select" id="newExpenseMethod" name="payment_method" required><option value="cash">Tunai</option><option value="bank_transfer">Transfer</option><option value="qris">QRIS</option><option value="unclassified">Belum dirinci</option></select></div>
                <div class="expense-field expense-field--wide"><label for="newExpenseNote">Catatan <span class="text-secondary">(opsional)</span></label><input class="form-control" id="newExpenseNote" name="note" maxlength="2000" value="{{ old('note') }}" placeholder="Keterangan tambahan"></div>
                <div class="expense-form-actions"><button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i>Catat pengeluaran</button></div>
            </form>
        </div>
    </section>

    <section class="expense-panel" aria-labelledby="expenseListTitle">
        <header class="expense-panel-heading"><div><h2 id="expenseListTitle">Daftar pengeluaran</h2><p>Urutan terbaru lebih dulu · {{ $expenses->total() }} catatan pada {{ $selectedMonthLabel }}</p></div></header>
        @forelse($expenses as $expense)
            @php
                $deleted = $expense->trashed();
            @endphp
            <article class="expense-row {{ $deleted ? 'expense-deleted' : '' }}">
                <div class="expense-row-main">
                    <div><div class="expense-row-title">{{ $expense->title }} @if($deleted)<span class="badge text-bg-danger">Dihapus · tidak dihitung</span>@endif</div><div class="expense-row-meta">{{ $expense->expense_date->locale('id')->translatedFormat('d F Y') }} · {{ $expense->category_label }}</div></div>
                    <div class="expense-row-method">{{ ['cash'=>'Tunai','bank_transfer'=>'Transfer','qris'=>'QRIS','unclassified'=>'Belum dirinci'][$expense->payment_method] ?? 'Belum dirinci' }}</div>
                    <div class="expense-row-note">{{ $expense->note ?: 'Tanpa catatan' }}</div>
                    <div class="expense-row-amount">Rp {{ number_format((float) $expense->amount, 0, ',', '.') }}</div>
                    @if(!$deleted)
                        <div class="expense-row-actions">
                            <button class="btn js-open-expense-edit" type="button" data-bs-toggle="modal"
                                data-bs-target="#editExpenseModal" data-expense-id="{{ $expense->id }}"
                                data-action="{{ route('admin.court-bookings.expenses.update', $expense) }}"
                                data-date="{{ $expense->expense_date->toDateString() }}"
                                data-category="{{ $expense->category }}" data-title="{{ $expense->title }}"
                                data-amount="{{ (int) $expense->amount }}"
                                data-method="{{ $expense->payment_method }}" data-note="{{ $expense->note }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>Ubah
                            </button>
                        </div>
                    @endif
                </div>
                <details>
                    <summary>Riwayat perubahan{{ $deleted ? ' dan penghapusan' : '' }}</summary>
                    @forelse($expense->audits as $audit)
                        <div class="expense-audit"><strong>{{ ['created'=>'Dicatat','updated'=>'Dikoreksi','deleted'=>'Dihapus'][$audit->action] ?? 'Diubah' }}</strong> · {{ $audit->created_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d/m/Y H:i') }} WIB · {{ $audit->actor_name_snapshot ?: 'Admin' }}
                            @if($audit->reason)<p>Alasan: {{ $audit->reason }}</p>@endif
                            @if($audit->before_data)<p>Sebelumnya: {{ $audit->before_data['title'] ?? '' }} · Rp {{ number_format((float) ($audit->before_data['amount'] ?? 0), 0, ',', '.') }}</p>@endif
                            @if($audit->after_data)<p>Sesudahnya: {{ $audit->after_data['title'] ?? '' }} · Rp {{ number_format((float) ($audit->after_data['amount'] ?? 0), 0, ',', '.') }}</p>@endif
                        </div>
                    @empty <p class="small text-secondary mt-3">Belum ada riwayat perubahan.</p> @endforelse
                    @if(!$deleted)
                        <form class="mt-3" method="POST" action="{{ route('admin.court-bookings.expenses.destroy', $expense) }}" onsubmit="return confirm('Hapus catatan ini dari perhitungan? Riwayatnya tetap disimpan.')">
                            @csrf @method('DELETE')
                            <div class="row g-2 align-items-end"><div class="col-12 col-md-8"><label class="form-label" for="deleteReason{{ $expense->id }}">Alasan hapus <span class="text-danger">*</span></label><input class="form-control" id="deleteReason{{ $expense->id }}" name="audit_reason" minlength="3" maxlength="500" required></div><div class="col-auto"><button class="btn btn-outline-danger" type="submit">Hapus catatan</button></div></div>
                        </form>
                    @endif
                </details>
            </article>
        @empty
            <div class="expense-empty">Belum ada pengeluaran yang dicatat pada {{ $selectedMonthLabel }}.</div>
        @endforelse
        @if($expenses->hasPages())<div class="p-3">{{ $expenses->links() }}</div>@endif
    </section>

    <div class="modal fade expense-edit-modal" id="editExpenseModal" tabindex="-1"
        aria-labelledby="editExpenseModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="editExpenseForm" method="POST" action="#">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_editing_expense_id" id="editingExpenseId"
                        value="{{ old('_editing_expense_id') }}">
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title mb-1" id="editExpenseModalTitle">Ubah pengeluaran</h2>
                            <p class="small text-secondary mb-0">Perubahan akan tersimpan di riwayat audit.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any() && old('_editing_expense_id'))
                            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                        @endif
                        <div class="expense-form expense-edit-form">
                            <div class="expense-field expense-field--wide">
                                <label for="editExpenseDate">Tanggal</label>
                                <input class="form-control" id="editExpenseDate" type="date" name="expense_date"
                                    value="{{ old('expense_date') }}" required>
                            </div>
                            <div class="expense-field expense-field--wide">
                                <label for="editExpenseCategory">Kategori</label>
                                <select class="form-select" id="editExpenseCategory" name="category" required>
                                    <option value="salary" @selected(old('category') === 'salary')>Gaji</option>
                                    <option value="electricity" @selected(old('category') === 'electricity')>Listrik</option>
                                    <option value="other" @selected(old('category') === 'other')>Lain-lain</option>
                                </select>
                            </div>
                            <div class="expense-field expense-field--full">
                                <label for="editExpenseTitle">Nama pengeluaran</label>
                                <input class="form-control" id="editExpenseTitle" name="title" maxlength="150"
                                    value="{{ old('title') }}" required>
                            </div>
                            <div class="expense-field expense-field--wide">
                                <label for="editExpenseAmountDisplay">Nominal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input class="form-control js-expense-money" id="editExpenseAmountDisplay"
                                        type="text" inputmode="numeric" autocomplete="off" value="{{ old('amount') }}"
                                        data-hidden-target="editExpenseAmount" required>
                                    <input type="hidden" id="editExpenseAmount" name="amount" value="{{ old('amount') }}">
                                </div>
                            </div>
                            <div class="expense-field expense-field--wide">
                                <label for="editExpenseMethod">Metode pembayaran</label>
                                <select class="form-select" id="editExpenseMethod" name="payment_method" required>
                                    <option value="cash" @selected(old('payment_method') === 'cash')>Tunai</option>
                                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Transfer</option>
                                    <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                                    <option value="unclassified" @selected(old('payment_method') === 'unclassified')>Belum dirinci</option>
                                </select>
                            </div>
                            <div class="expense-field expense-field--full">
                                <label for="editExpenseNote">Catatan <span class="text-secondary">(opsional)</span></label>
                                <input class="form-control" id="editExpenseNote" name="note" maxlength="2000"
                                    value="{{ old('note') }}">
                            </div>
                            <div class="expense-field expense-field--full">
                                <label for="editExpenseReason">Alasan koreksi <span class="text-danger">*</span></label>
                                <input class="form-control" id="editExpenseReason" name="audit_reason" minlength="3"
                                    maxlength="500" value="{{ old('audit_reason') }}"
                                    placeholder="Wajib diisi untuk menyimpan koreksi" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan koreksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(() => {
    const formatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
    const normalizeDigits = (value) => value.replace(/\D/g, '').replace(/^0+(?=\d)/, '');
    const formatAmount = (value) => value ? formatter.format(Number(value)) : '';

    document.querySelectorAll('.js-expense-money').forEach((input) => {
        const hidden = document.getElementById(input.dataset.hiddenTarget);
        if (!hidden) return;

        const sync = () => {
            const digits = normalizeDigits(input.value);
            input.value = formatAmount(digits);
            hidden.value = digits;
        };

        if (hidden.value) input.value = formatAmount(normalizeDigits(hidden.value));
        input.addEventListener('input', sync);
        input.form?.addEventListener('submit', sync);
    });

    const modal = document.getElementById('editExpenseModal');
    const form = document.getElementById('editExpenseForm');
    if (!modal || !form) return;

    const fields = {
        id: document.getElementById('editingExpenseId'),
        date: document.getElementById('editExpenseDate'),
        category: document.getElementById('editExpenseCategory'),
        title: document.getElementById('editExpenseTitle'),
        amount: document.getElementById('editExpenseAmount'),
        amountDisplay: document.getElementById('editExpenseAmountDisplay'),
        method: document.getElementById('editExpenseMethod'),
        note: document.getElementById('editExpenseNote'),
        reason: document.getElementById('editExpenseReason'),
    };

    modal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        if (!trigger) return;

        form.action = trigger.dataset.action;
        fields.id.value = trigger.dataset.expenseId;
        fields.date.value = trigger.dataset.date;
        fields.category.value = trigger.dataset.category;
        fields.title.value = trigger.dataset.title;
        fields.amount.value = normalizeDigits(trigger.dataset.amount || '');
        fields.amountDisplay.value = formatAmount(fields.amount.value);
        fields.method.value = trigger.dataset.method;
        fields.note.value = trigger.dataset.note || '';
        fields.reason.value = '';
    });

    const previousExpenseId = fields.id.value;
    if (previousExpenseId) {
        const trigger = Array.from(document.querySelectorAll('.js-open-expense-edit'))
            .find((button) => button.dataset.expenseId === previousExpenseId);

        if (trigger) {
            form.action = trigger.dataset.action;
            bootstrap.Modal.getOrCreateInstance(modal).show();
        }
    }
})();
</script>
@endpush
