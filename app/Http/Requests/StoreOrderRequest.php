<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|numeric|min:1',
            'services.*.estimated_date' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'User ID does not exist.',
            'services.required' => 'Services are required.',
            'services.*.service_id.required' => 'Service is required.',
            'services.*.service_id.exists' => 'Service ID does not exist.',
            'services.*.quantity.required' => 'Quantity is required.',
            'services.*.quantity.numeric' => 'Quantity must be a number.',
            'services.*.quantity.min' => 'Quantity must be at least 1.',
            'services.*.estimated_date.required' => 'Estimated date is required.',
            'services.*.estimated_date.numeric' => 'Estimated date must be a number.',
            'services.*.estimated_date.min' => 'Estimated date must be at least 1.',
        ];
    }
}
