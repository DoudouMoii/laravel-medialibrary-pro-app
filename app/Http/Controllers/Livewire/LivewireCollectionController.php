<?php

namespace App\Http\Controllers\Livewire;

use App\Http\Requests\Livewire\StoreLivewireCollectionRequest;
use App\Models\FormSubmission;

class LivewireCollectionController
{
    public function create()
    {
        /** @var \App\Models\FormSubmission $formSubmission */
        $formSubmission = FormSubmission::firstOrCreate(['name' => 'test']);

        return view('uploads.livewire.collection', compact('formSubmission'));
    }

    public function store(StoreLivewireCollectionRequest $request)
    {
        /** @var \App\Models\FormSubmission $formSubmission */
        $formSubmission = FormSubmission::first();

        $formSubmission
            ->syncFromMediaLibraryRequest($request->images)
            ->withCustomProperties('extra_field')
            ->toMediaCollection('images');

        $formSubmission
            ->syncFromMediaLibraryRequest($request->downloads)
            ->toMediaCollection('downloads');

        $formSubmission->name = $request->name;
        $formSubmission->save();

        flash()->success('Your form has been submitted');

        return back();
    }
}
