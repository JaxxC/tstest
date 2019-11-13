<?php

use Illuminate\Database\Seeder;
use App\Models\FormFile;
use App\Models\Form;

class FormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Form::class, 3)->create()->each(function ($form) {
            for($i = 1; $i<=3; $i++){
                $form->files()->save(factory(FormFile::class)->make());
            }
        });
    }
}
