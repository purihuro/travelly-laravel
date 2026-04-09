<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AboutContentController extends Controller
{
    public function edit()
    {
        return view('admin.content.about', ['about' => SiteContent::aboutContent()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_title' => ['required', 'string', 'max:180'],
            'hero_breadcrumb' => ['required', 'string', 'max:180'],
            'intro_heading' => ['required', 'string', 'max:220'],
            'paragraph_1' => ['nullable', 'string'],
            'paragraph_2' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'cta_label' => ['nullable', 'string', 'max:80'],
            'cta_route' => ['nullable', 'string', 'max:120'],
            'newsletter.title' => ['required', 'string', 'max:180'],
            'newsletter.text' => ['nullable', 'string', 'max:255'],
            'newsletter.placeholder' => ['nullable', 'string', 'max:120'],
            'newsletter.button_label' => ['nullable', 'string', 'max:80'],
            'counters' => ['required', 'array', 'size:4'],
            'counters.*.number' => ['required', 'string', 'max:20'],
            'counters.*.label' => ['required', 'string', 'max:120'],
            'testimony.subheading' => ['nullable', 'string', 'max:120'],
            'testimony.title' => ['required', 'string', 'max:180'],
            'testimony.text' => ['nullable', 'string', 'max:255'],
            'hero_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'intro_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'counters_background_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $content = SiteContent::aboutContent();
        foreach (['hero_title', 'hero_breadcrumb', 'intro_heading', 'paragraph_1', 'paragraph_2', 'video_url', 'cta_label', 'cta_route'] as $field) {
            $content[$field] = $data[$field] ?? '';
        }
        $content['newsletter'] = $data['newsletter'];
        $content['counters'] = $data['counters'];
        $content['testimony'] = $data['testimony'];

        foreach (['hero_image_upload' => 'hero_image', 'intro_image_upload' => 'intro_image', 'counters_background_upload' => 'counters_background'] as $uploadKey => $targetKey) {
            $file = $request->file($uploadKey);
            if ($file instanceof UploadedFile) {
                $content[$targetKey] = $file->store('page/about', 'public');
            }
        }

        SiteSetting::query()->updateOrCreate(['key' => 'about'], ['value' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

        return redirect()->route('admin.content.about.edit')->with('status', 'Konten About berhasil diperbarui.');
    }
}
