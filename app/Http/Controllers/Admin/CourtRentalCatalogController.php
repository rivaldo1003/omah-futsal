<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourtRentalItem;
use App\Services\CourtRentalInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CourtRentalCatalogController extends Controller
{
    public function index()
    {
        $this->ensureAdmin();
        $rentalItems = CourtRentalItem::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.court-rentals.settings', compact('rentalItems'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $validated = $this->validateItem($request);
        $validated['sort_order'] = min(65535, ((int) CourtRentalItem::query()->max('sort_order')) + 10);
        CourtRentalItem::query()->create($validated);

        return redirect()->route('admin.court-rentals.settings')->with('success', 'Perlengkapan sewa berhasil ditambahkan.');
    }

    public function update(Request $request, CourtRentalItem $rentalItem, CourtRentalInventoryService $inventory)
    {
        $this->ensureAdmin();
        $validated = $this->validateItem($request, $rentalItem);

        DB::transaction(function () use ($rentalItem, $validated, $inventory) {
            $lockedItem = CourtRentalItem::query()->whereKey($rentalItem->id)->lockForUpdate()->firstOrFail();
            $maximumReserved = $inventory->maximumConcurrentReservedQuantity($lockedItem->id);
            if ((int) $validated['stock_quantity'] < $maximumReserved) {
                throw ValidationException::withMessages([
                    'stock_quantity' => "Stok tidak dapat dikurangi di bawah {$maximumReserved} unit karena masih ada pesanan aktif pada waktu yang bertumpang tindih.",
                ]);
            }

            $lockedItem->update($validated);
        });

        return redirect()->route('admin.court-rentals.settings')->with('success', 'Pengaturan perlengkapan berhasil diperbarui.');
    }

    private function validateItem(Request $request, ?CourtRentalItem $rentalItem = null): array
    {
        $price = $request->input('unit_price');
        if (is_string($price)) {
            $price = preg_replace('/^Rp\s*/i', '', trim($price));
            $price = preg_replace('/\s+/', '', $price);
            $request->merge(['unit_price' => str_replace('.', '', $price)]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('court_rental_items', 'name')->ignore($rentalItem?->id)],
            'unit_price' => ['required', 'integer', 'min:1', 'max:100000000'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:65535'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return [
            'name' => $validated['name'],
            'unit_price' => (int) $validated['unit_price'],
            'stock_quantity' => (int) $validated['stock_quantity'],
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}
