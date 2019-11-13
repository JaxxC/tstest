<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Form;
use App\Models\FormFile;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class FormSaveAndGetTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_form_save_with_files()
    {
        $files = [
            [
                'name' => 'testfilename.ext',
                'originalName' => 'testfileoriginalname.ext'
            ],
            [
                'name' => 'testfilename2.ext',
                'originalName' => 'testfileoriginalname2.ext'
            ]
        ];

        $this->json('POST', '/api/form', [
            'name' => 'Test Form',
            'formFiles' => $files
        ])->assertJson([
            'data' => [
                'files' => [
                    [
                        'name' => 'testfilename.ext',
                        'originalName' => 'testfileoriginalname.ext'
                    ],
                    [
                        'name' => 'testfilename2.ext',
                        'originalName' => 'testfileoriginalname2.ext'
                    ]
                ],
                'name' => 'Test Form',
            ]
        ])->assertJsonFragment([
                'name' => 'testfilename.ext',
                'originalName' => 'testfileoriginalname.ext'
        ])->assertJsonFragment([
                'name' => 'testfilename2.ext',
                'originalName' => 'testfileoriginalname2.ext'
        ])->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('forms', [
            'name' => 'Test Form',
        ]);
        $this->assertDatabaseHas('form_files', [
            'name' => 'testfilename.ext',
            'original_name' => 'testfileoriginalname.ext',
        ]);
        $this->assertDatabaseHas('form_files', [
            'name' => 'testfilename2.ext',
            'original_name' => 'testfileoriginalname2.ext',
        ]);
    }

    public function test_form_save_without_files()
    {
        $this->json('POST', '/api/form', [
            'name' => 'Test Form',
            'formFiles' => []
        ])->assertJson([
            'data' => [
                'files' => [],
                'name' => 'Test Form',
            ]
        ])->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('forms', [
            'name' => 'Test Form',
        ]);
    }

    public function test_form_get()
    {
        $form = factory(Form::class)->create();
        $file = $form->files()->save(factory(FormFile::class)->make());
        $this->json('GET', '/api/form/' . $form->id)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'files'
                ]])
            ->assertJson([
            'data' => [
                    'id' => $form->id,
                    'name' => $form->name,
                    'files' => []
                ]
            ])->assertJsonFragment([
                    "formId" => $form->id,
                    'id' => $file->id,
                    'name' => $file->name,
                    'originalName' => $file->original_name,
            ])
            ->assertStatus(Response::HTTP_OK);

    }

    public function test_form_list()
    {
        factory(Form::class, 3)->create();
        $this->json('GET', '/api/forms')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'files'
                    ]
                ]
            ])->assertStatus(Response::HTTP_OK);

    }
}
