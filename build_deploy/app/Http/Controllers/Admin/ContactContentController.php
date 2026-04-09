<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ContactContentController extends Controller
{
    public function edit()
    {
        return view('admin.content.contact', ['contact' => SiteContent::contactContent()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_title' => ['required', 'string', 'max:180'],
            'hero_breadcrumb' => ['required', 'string', 'max:180'],
            'form_title' => ['required', 'string', 'max:180'],
            'button_label' => ['required', 'string', 'max:80'],
            'placeholders.name' => ['required', 'string', 'max:80'],
            'placeholders.email' => ['required', 'string', 'max:80'],
            'placeholders.subject' => ['required', 'string', 'max:80'],
            'placeholders.message' => ['required', 'string', 'max:120'],
            'info_cards' => ['required', 'array', 'size:4'],
            'info_cards.*.label' => ['required', 'string', 'max:80'],
            'info_cards.*.value' => ['required', 'string', 'max:255'],
            'info_cards.*.link' => ['nullable', 'string', 'max:255'],
            'hero_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $content = SiteContent::contactContent();
        $content['hero_title'] = $data['hero_title'];
        $content['hero_breadcrumb'] = $data['hero_breadcrumb'];
        $content['form_title'] = $data['form_title'];
        $content['button_label'] = $data['button_label'];
        $content['placeholders'] = $data['placeholders'];
        $content['info_cards'] = $data['info_cards'];

        $heroImage = $request->file('hero_image_upload');
        if ($heroImage instanceof UploadedFile) {
            $content['hero_image'] = $heroImage->store('page/contact', 'public');
        }

        SiteSetting::query()->updateOrCreate(['key' => 'contact'], ['value' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

        return redirect()->route('admin.content.contact.edit')->with('status', 'Konten Contact berhasil diperbarui.');
    }
}
