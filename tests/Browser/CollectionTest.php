<?php

namespace Tests\Browser;

use App\Models\FormSubmission;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CollectionTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        FormSubmission::truncate();
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function an_existing_collection_can_be_displayed(string $routeName)
    {
        /** @var FormSubmission $formSubmission */
        $formSubmission = FormSubmission::create(['name' => 'test']);

        $formSubmission
            ->addMedia($this->getStubPath('space.png'))
            ->preservingOriginal()
            ->toMediaCollection('images');

        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->assertValue('@medialibrary-field-name', 'space');
        });
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function it_can_upload_and_display_a_single_file(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->type('name', 'My name')
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->waitForText('Download')
                ->press('@submit')
                ->assertSee('Your form has been submitted')
                ->assertVisible('@thumb');

            $this->assertCount(1, FormSubmission::first()->getMedia('images'));
        });
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function uploading_a_file_and_removing_it_before_submitting_the_form_will_not_attach_media_to_the_model(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->type('name', 'My name')
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->waitForText('Download')
                ->assertVisible('@thumb')
                ->press('@remove')
                ->waitUntilMissing('@thumb')
                ->press('@submit')
                ->assertSee('Your form has been submitted')
                ->assertMissing('@thumb');

            $this->assertCount(0, FormSubmission::first()->getMedia('images'));
        });
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function the_name_field_of_a_collection_item_is_required(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->type('name', 'My name')
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->waitForText('Download')
                ->type('@medialibrary-field-name', '   ') // needed to make test pass
                ->type('@medialibrary-field-name', '')
                ->screenshot('before submit')
                ->press('@submit')
                ->assertSee('The name field is required');
        });
    }

    /** @test
     *
     * @dataProvider routeNames
     */
    public function it_will_not_display_the_main_uploader_when_the_maximum_amount_of_uploads_has_been_reached(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->type('name', 'My name')
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->pause(500)
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->pause(500)
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->pause(500)
                ->assertMissing('@main-uploader');
        });
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function a_new_collection_item_can_be_replaced(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->type('name', 'My name')
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->pause(500)
                ->attach('@uploader', $this->getStubPath('logo.png'))
                ->pause(200)
                ->press('@submit');

            $this->assertEquals('logo.png', FormSubmission::first()->getFirstMedia('images')->file_name);
        });
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function the_collection_will_only_allow_acceptable_files(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->attach('@main-uploader', $this->getStubPath('test.pdf'))
                ->pause(200)
                ->assertSee('You must upload a file of type');
        });
    }

    public function routeNames(): array
    {
        return [
            ['vue.collection'],
            ['react.collection'],
            ['livewire.collection'],
        ];
    }
}
