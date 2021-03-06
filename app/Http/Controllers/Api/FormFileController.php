<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\UploadFileRequest;
use App\Models\FormFile;

class FormFileController extends Controller
{

    /**
     * Upload new file.
     * File renames after upload to random generated name for preventing
     * issues with OS related forbidden characters in filename
     * @return \Illuminate\Http\Response
     */
    public function upload(UploadFileRequest $request)
    {
        $file = $request->file('file');
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file->storeAs(config('uploads.folder'), $fileName);

        return response()->json([
            'success' => true,
            'fileName' => $fileName,
        ]);
    }

    /**
     * Download file
     * File downloads with original name
     *
     * @param FormFile $file
     * @return \Illuminate\Http\Response
     */
    public function view(FormFile $file)
    {
        return response()->file($file->path, ['Content-Disposition' => 'filename=' . $file->original_name]);
    }
}
