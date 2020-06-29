<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\StoresFormSubmissions;
use App\Http\Requests\StoreMultipleUploadsRequest;
use App\Models\FormSubmission;

class LivewireMultipleAttachmentsController
{
    use StoresFormSubmissions;

    public function create()
    {
        return view('uploads.livewire.attachment-multiple');
    }

    public function store(StoreMultipleUploadsRequest $request)
    {
        /** @var \App\Models\FormSubmission $formSubmission */
        $formSubmission = FormSubmission::create([
            'name' => $request->name ?? 'nothing'
        ]);

        $formSubmission
            ->addFromMediaLibraryRequest($request->media)
            ->toMediaCollection('images');

        flash()->success('Your form has been submitted');

        return back();
    }
}
