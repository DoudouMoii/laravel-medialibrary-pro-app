<?php

namespace Tests\Browser;

use App\Models\FormSubmission;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AttachmentTest extends DuskTestCase
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
    public function it_can_handle_a_single_upload(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->type('name', 'My name')
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->waitForText('Remove')
                ->press('@submit')
                ->assertSee('Your form has been submitted');

            $this->assertCount(1, FormSubmission::get());

            $this->assertEquals('space.png', FormSubmission::first()->getFirstMedia('images')->file_name);
        });
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function it_will_not_lose_the_uploaded_file_when_submitting_a_form_results_in_validation_errors(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->waitForText('Remove')
                ->press('@submit');

            $this->assertCount(0, FormSubmission::all());

            $browser
                ->assertSee('Please correct the errors in the form')
                ->assertVisible('@thumb')
                ->type('name', 'My name failing test')
                ->press('@submit')
                ->assertSee('Your form has been submitted');

            $this->assertCount(1, FormSubmission::all());
            $this->assertEquals('space.png', FormSubmission::first()->getFirstMedia('images')->file_name);
        });
    }

    /**
     * @test
     *
     * @dataProvider routeNames
     */
    public function it_can_show_a_validation_error_on_replacement(string $routeName)
    {
        $this->browse(function (Browser $browser) use ($routeName) {
            $browser
                ->visit(route($routeName))
                ->type('name', 'My name')
                ->attach('@main-uploader', $this->getStubPath('space.png'))
                ->pause(500)
                ->attach('@uploader', $this->getStubPath('data.json'))
                ->pause(200)
                ->assertSee('You must upload a file of type');
        });
    }

    public function routeNames(): array
    {
        return [
            ['vue.attachment'],
            ['react.attachment'],
            ['livewire.attachment'],
        ];
    }
}
