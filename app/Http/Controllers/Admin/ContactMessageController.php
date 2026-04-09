<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactMessageRequest;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = class_exists(ContactMessage::class) ? [] : [];

        return view('admin.contacts.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        return view('admin.contacts.show', compact('contactMessage'));
    }

    public function edit(ContactMessage $contactMessage)
    {
        return view('admin.contacts.edit', compact('contactMessage'));
    }

    public function update(UpdateContactMessageRequest $request, ContactMessage $contactMessage)
    {
        $data = $request->validated();

        return redirect()->route('admin.contacts.index');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        return redirect()->route('admin.contacts.index');
    }
}
