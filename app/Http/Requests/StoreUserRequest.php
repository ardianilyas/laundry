<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Normalisasi nomor sebelum validasi dijalankan
        $phone = preg_replace('/[^0-9]/', '', $this->phone ?? '');

        // Jika dimulai dengan 0 → ubah ke 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Jika belum dimulai dengan 62 → tambahkan
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        $this->merge([
            'phone' => $phone,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users,phone',
        ];
    }
}
