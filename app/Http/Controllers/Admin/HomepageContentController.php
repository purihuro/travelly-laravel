<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomepageContentRequest;
use App\Models\SiteSetting;
use App\Support\SiteContent;
use Illuminate\Http\UploadedFile;

class HomepageContentController extends Controller
{
    public function edit()
    {
        $homepage = SiteContent::homepageContent();

        return view('admin.homepage.edit', compact('homepage'));
    }

    public function update(UpdateHomepageContentRequest $request)
    {
        $validated = $request->validated();
        $homepage = SiteContent::homepageContent();

        foreach ($validated['hero_slides'] as $index => $slide) {
            $homepage['hero_slides'][$index]['title'] = $slide['title'];
            $homepage['hero_slides'][$index]['subtitle'] = $slide['subtitle'] ?? '';
            $homepage['hero_slides'][$index]['button'] = $slide['button'] ?? '';
            $homepage['hero_slides'][$index]['button_route'] = $slide['button_route'] ?? 'shop';

            $upload = $request->file("hero_slide_uploads.$index");
            if ($upload instanceof UploadedFile) {
                $homepage['hero_slides'][$index]['image'] = $upload->store('homepage/hero', 'public');
            }
        }

        foreach ($validated['service_features'] as $index => $feature) {
            $homepage['service_features'][$index]['icon'] = $feature['icon'];
            $homepage['service_features'][$index]['bg_class'] = $feature['bg_class'];
            $homepage['service_features'][$index]['title'] = $feature['title'];
            $homepage['service_features'][$index]['text'] = $feature['text'] ?? '';
            $homepage['service_features'][$index]['is_active'] = (bool) ($feature['is_active'] ?? false);
        }

        $homepage['category_showcase']['main_title'] = $validated['category_showcase']['main_title'];
        $homepage['category_showcase']['main_text'] = $validated['category_showcase']['main_text'] ?? '';
        $homepage['category_showcase']['button_label'] = $validated['category_showcase']['button_label'] ?? '';
        $homepage['category_showcase']['button_route'] = $validated['category_showcase']['button_route'] ?? 'shop';

        $mainCategoryUpload = $request->file('category_image_uploads.main_image');
        if ($mainCategoryUpload instanceof UploadedFile) {
            $homepage['category_showcase']['main_image'] = $mainCategoryUpload->store('homepage/categories', 'public');
        }

        foreach ($validated['category_showcase']['cards'] as $index => $card) {
            $homepage['category_showcase']['cards'][$index]['title'] = $card['title'];
            $upload = $request->file("category_image_uploads.cards.$index");
            if ($upload instanceof UploadedFile) {
                $homepage['category_showcase']['cards'][$index]['image'] = $upload->store('homepage/categories', 'public');
            }
        }

        $homepage['featured_section'] = [
            'subheading' => $validated['featured_section']['subheading'] ?? '',
            'title' => $validated['featured_section']['title'],
            'text' => $validated['featured_section']['text'] ?? '',
            'button_label' => $validated['featured_section']['button_label'] ?? '',
            'button_route' => $validated['featured_section']['button_route'] ?? 'shop',
        ];

        $homepage['newsletter'] = [
            'title' => $validated['newsletter']['title'],
            'text' => $validated['newsletter']['text'] ?? '',
            'placeholder' => $validated['newsletter']['placeholder'] ?? '',
            'button_label' => $validated['newsletter']['button_label'] ?? '',
        ];

        SiteSetting::query()->updateOrCreate(
            ['key' => 'homepage'],
            ['value' => json_encode($homepage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
        );

        return redirect()->route('admin.homepage.edit')->with('status', 'Konten homepage berhasil diperbarui.');
    }
}
