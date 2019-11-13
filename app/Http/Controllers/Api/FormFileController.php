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
     *
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
     * 
     * @param FormFile $file
     * @return \Illuminate\Http\Response
     */
    public function download(FormFile $file)
    {
        return response()->download($file->path, $file->original_name);
    }
}
