@extends('layouts.master', ['pageTitle' => 'Livewire: attachment'])

@section('content')

    <x-h2>Livewire: attachment</x-h2>

    <x-form method="POST">
        @csrf

        <x-field label="name">
            <x-input id="name" name="name" placeholder="Your first name" />
        </x-field>

        <x-field label="file">
            <x-media-library-attachment name="media" rules="mimes:png" />
        </x-field>

        <x-button data-testing-role="submit">Submit</x-button>
    </x-form>

@endsection