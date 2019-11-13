<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;

class FileUploadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_upload_works()
    {
        $file = __DIR__.'/assets/test.ext';
        $name ='test.ext';
        $path = sys_get_temp_dir().'/'.$name;

        copy($file, $path);

        $file = new UploadedFile($path, $name, 'image/png', null, true);
        $response = $this->call('POST', '/api/file/upload', [], [], ['file' => $file], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);
        $content = json_decode($response->getContent());
        $this->assertObjectHasAttribute('fileName', $content);

        $uploaded = storage_path('app/' . config('uploads.folder') . '/' .$content->fileName);
        $this->assertFileExists($uploaded);

        @unlink($uploaded);

    }
}
