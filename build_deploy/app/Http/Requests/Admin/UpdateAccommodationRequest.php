<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccommodationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $accommodation = $this->route('accommodation');

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', Rule::unique('accommodations', 'slug')->ignore($accommodation?->id)],
            'type' => ['required', 'in:hotel,villa,homestay'],
            'room_type' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:150'],
            'highlight' => ['nullable', 'string', 'max:160'],
            'amenities' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
