<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SaveFormRequest;
use App\Models\Form;
use App\Http\Resources\FormResource;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = Form::all();

        return FormResource::collection($forms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SaveFormRequest $request)
    {
        $form = Form::create([
            'name' => $request->name
        ]);
        $files = [];
        foreach ($request->formFiles as $file){
            $files[] = [
                'name' => $file['name'],
                'original_name' => $file['originalName']
            ];
        }

        $form->files()->createMany($files);

        return new FormResource($form);
    }

    /**
     * Display the specified resource.
     *
     * @param  Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        return new FormResource($form);
    }

}
