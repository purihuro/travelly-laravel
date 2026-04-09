<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCulinaryPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'culinary_venue_id' => ['nullable', 'integer', 'exists:culinary_venues,id', 'required_without:venue_name'],
            'venue_name' => ['nullable', 'string', 'max:150', 'required_without:culinary_venue_id'],
            'venue_location' => ['nullable', 'string', 'max:150'],
            'venue_description' => ['nullable', 'string', 'max:1000'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', 'unique:culinary_packages,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price_per_person' => ['required', 'numeric', 'min:0'],
            'preparation_time' => ['nullable', 'integer', 'min:1'],
            'serving_size' => ['nullable', 'string', 'max:100'],
            'availability_from' => ['nullable', 'date'],
            'availability_to' => ['nullable', 'date', 'after_or_equal:availability_from'],
            'max_bookings' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'current_bookings' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'availability_status' => ['nullable', 'string', 'in:available,sold_out,discontinued'],
            'is_active' => ['nullable', 'boolean'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ];
    }
}
