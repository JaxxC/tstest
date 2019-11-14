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
                'title' => 'test title',
                'originalName' => 'testfileoriginalname.ext'
            ],
            [
                'name' => 'testfilename2.ext',
                'title' => 'test title2',
                'originalName' => 'testfileoriginalname2.ext'
            ]
        ];

        $this->json('POST', '/api/form', [
            'name' => 'Test Form',
            'formFiles' => $files
        ])->assertJson([
            'data' => [
                'files' => $files,
                'name' => 'Test Form',
            ]
        ])->assertJsonFragment([
                'name' => 'testfilename.ext',
                'title' => 'test title',
                'originalName' => 'testfileoriginalname.ext'
        ])->assertJsonFragment([
                'name' => 'testfilename2.ext',
                'title' => 'test title2',
                'originalName' => 'testfileoriginalname2.ext'
        ])->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('forms', [
            'name' => 'Test Form',
        ]);
        $this->assertDatabaseHas('form_files', [
            'name' => 'testfilename.ext',
            'title' => 'test title',
            'original_name' => 'testfileoriginalname.ext',
        ]);
        $this->assertDatabaseHas('form_files', [
            'name' => 'testfilename2.ext',
            'title' => 'test title2',
            'original_name' => 'testfileoriginalname2.ext',
        ]);
    }

    public function test_form_save_with_invalid_data()
    {
        $filesCorrectFormat = [
            [
                'name' => 'testfilename.ext',
                'title' => 'test title',
                'originalName' => 'testfileoriginalname.ext'
            ],
            [
                'name' => 'testfilename2.ext',
                'title' => 'test title2',
                'originalName' => 'testfileoriginalname2.ext'
            ]
        ];
        $filesWrongFormat = [
            [
                'name' => 'testfilename.ext',
                'originalName' => 'testfileoriginalname.ext'
            ],
            [
                'name' => 'testfilename2.ext',
                'originalName' => 'testfileoriginalname2.ext'
            ]
        ];
        // No name field
        $this->json('POST', '/api/form', [
            'formFiles' => $filesCorrectFormat
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        // Empty files array
        $this->json('POST', '/api/form', [
            'name' => 'Test Form',
            'formFiles' => []
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        // No files array
        $this->json('POST', '/api/form', [
            'name' => 'Test Form'
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        //Files array items wrong format
        $this->json('POST', '/api/form', [
            'name' => 'Test Form',
            'formFiles' => $filesWrongFormat
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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
                ]
            ])->assertJson([
                'data' => [
                    'id' => $form->id,
                    'name' => $form->name,
                    'files' => []
                ]
            ])->assertJsonFragment([
                    "formId" => $form->id,
                    'id' => $file->id,
                    'name' => $file->name,
                    'title' => $file->title,
                    'originalName' => $file->original_name,
            ])
            ->assertStatus(Response::HTTP_OK);

    }

    public function test_form_list()
    {
        $forms = factory(Form::class, 3)->create();
        $test = $this->json('GET', '/api/forms')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'files'
                    ]
                ]
            ]);
        foreach($forms as $form){
            $test->assertJsonFragment([
                'id' => $form->id,
                'name' => $form->name
            ]);
        }
        $test->assertStatus(Response::HTTP_OK);

    }
}
