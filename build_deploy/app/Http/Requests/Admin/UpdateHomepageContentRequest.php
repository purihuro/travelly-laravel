<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomepageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_slides' => ['required', 'array', 'size:2'],
            'hero_slides.*.title' => ['required', 'string', 'max:180'],
            'hero_slides.*.subtitle' => ['nullable', 'string'],
            'hero_slides.*.button' => ['nullable', 'string', 'max:80'],
            'hero_slides.*.button_route' => ['nullable', 'string', 'max:120'],
            'hero_slide_uploads' => ['nullable', 'array'],
            'hero_slide_uploads.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'service_features' => ['required', 'array', 'size:4'],
            'service_features.*.icon' => ['required', 'string', 'max:120'],
            'service_features.*.bg_class' => ['required', 'string', 'max:50'],
            'service_features.*.title' => ['required', 'string', 'max:150'],
            'service_features.*.text' => ['nullable', 'string', 'max:255'],
            'service_features.*.is_active' => ['nullable', 'boolean'],
            'category_showcase.main_title' => ['required', 'string', 'max:180'],
            'category_showcase.main_text' => ['nullable', 'string', 'max:255'],
            'category_showcase.button_label' => ['nullable', 'string', 'max:80'],
            'category_showcase.button_route' => ['nullable', 'string', 'max:120'],
            'category_showcase.cards' => ['required', 'array', 'size:4'],
            'category_showcase.cards.*.title' => ['required', 'string', 'max:120'],
            'category_image_uploads.main_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'category_image_uploads.cards' => ['nullable', 'array'],
            'category_image_uploads.cards.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'featured_section.subheading' => ['nullable', 'string', 'max:120'],
            'featured_section.title' => ['required', 'string', 'max:180'],
            'featured_section.text' => ['nullable', 'string', 'max:255'],
            'featured_section.button_label' => ['nullable', 'string', 'max:80'],
            'featured_section.button_route' => ['nullable', 'string', 'max:120'],
            'newsletter.title' => ['required', 'string', 'max:180'],
            'newsletter.text' => ['nullable', 'string', 'max:255'],
            'newsletter.placeholder' => ['nullable', 'string', 'max:120'],
            'newsletter.button_label' => ['nullable', 'string', 'max:80'],
        ];
    }
}
