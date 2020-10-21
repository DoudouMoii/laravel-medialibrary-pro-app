<?php

namespace App\Http\Requests\Blade;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia;

class StoreBladeCollectionCustomPropertyRequest extends FormRequest
{
    use ValidatesMedia;

    public function rules()
    {
        ld()->clear();

        return [
            'name' => 'required',
            'images' => [$this->validateMultipleMedia()
                ->maxItems(3)
                ->itemName('required|max:30')
                ->customProperty('extra_field', 'required|max:30'),
            ],
        ];
    }
}
