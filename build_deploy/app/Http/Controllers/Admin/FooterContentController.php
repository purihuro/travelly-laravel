<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SiteContent;
use Illuminate\Http\Request;

class FooterContentController extends Controller
{
    public function edit()
    {
        return view('admin.content.footer', ['footer' => SiteContent::footerContent()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'brand_title' => ['required', 'string', 'max:120'],
            'brand_text' => ['nullable', 'string', 'max:255'],
            'questions_title' => ['required', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:80'],
            'email' => ['nullable', 'string', 'max:120'],
            'copyright_text' => ['required', 'string', 'max:255'],
            'socials' => ['required', 'array', 'size:3'],
            'socials.*.icon' => ['required', 'string', 'max:80'],
            'socials.*.url' => ['nullable', 'string', 'max:255'],
            'menu_links' => ['required', 'array', 'min:1'],
            'menu_links.*.label' => ['required', 'string', 'max:80'],
            'menu_links.*.route' => ['required', 'string', 'max:80'],
            'help_links_left' => ['required', 'array', 'size:4'],
            'help_links_left.*' => ['required', 'string', 'max:120'],
            'help_links_right' => ['required', 'array', 'size:2'],
            'help_links_right.*' => ['required', 'string', 'max:120'],
        ]);

        SiteSetting::query()->updateOrCreate(['key' => 'footer'], ['value' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

        return redirect()->route('admin.content.footer.edit')->with('status', 'Konten footer berhasil diperbarui.');
    }
}
