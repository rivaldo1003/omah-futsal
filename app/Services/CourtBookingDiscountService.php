<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class CourtBookingDiscountService
{
    public function normalizedValue(string $type, string $value): float
    {
        if ($type === 'none') {
            return 0.0;
        }

        $value = trim($value);
        if ($value === '') {
            return 0.0;
        }

        if ($type === 'fixed') {
            if (!preg_match('/^[\d.,\s]+$/', $value)) {
                throw ValidationException::withMessages(['discount_value' => 'Masukkan nominal diskon berupa angka.']);
            }
            $digits = preg_replace('/\D+/', '', $value);
            if ($digits === '') {
                throw ValidationException::withMessages(['discount_value' => 'Masukkan nominal diskon berupa angka.']);
            }

            return (float) $digits;
        }

        if (!preg_match('/^\d+(?:[.,]\d{1,2})?$/', $value)) {
            throw ValidationException::withMessages(['discount_value' => 'Masukkan persentase diskon yang valid.']);
        }

        return (float) str_replace(',', '.', $value);
    }

    public function forCourtAmount(string $type, string $value, string $reason, float $courtAmount): array
    {
        $normalizedValue = $this->normalizedValue($type, $value);
        if ($type === 'none' || $normalizedValue <= 0) {
            return ['type' => 'none', 'value' => 0, 'amount' => 0, 'reason' => null];
        }

        if ($type === 'percentage' && $normalizedValue > 100) {
            throw ValidationException::withMessages(['discount_value' => 'Persentase diskon tidak boleh lebih dari 100%.']);
        }
        if ($type === 'fixed' && $normalizedValue > $courtAmount) {
            throw ValidationException::withMessages(['discount_value' => 'Diskon nominal tidak boleh melebihi tarif lapangan.']);
        }
        if (trim($reason) === '') {
            throw ValidationException::withMessages(['discount_reason' => 'Alasan diskon wajib diisi agar potongan dapat ditelusuri.']);
        }

        $discountAmount = $type === 'percentage'
            ? round($courtAmount * $normalizedValue / 100, 2)
            : round($normalizedValue, 2);

        return [
            'type' => $type,
            'value' => $normalizedValue,
            'amount' => min(round($courtAmount, 2), $discountAmount),
            'reason' => trim($reason),
        ];
    }
}
