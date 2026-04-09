<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_status' => ['required', 'string', 'max:30'],
            'payment_status' => ['required', 'string', 'max:30'],
            'payment_method' => ['required', 'string', 'max:50'],
            'participants' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'departure_date' => ['nullable', 'date'],
        ];
    }
}
