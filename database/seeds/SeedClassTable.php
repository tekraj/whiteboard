<?php

use Illuminate\Database\Seeder;
Use App\Models\StudentClass;
class SeedClassTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = ['10th','11th','12th'];
        foreach($classes as $class){
            if(!StudentClass::where('class_name',$class)->first()){
                StudentClass::create(['class_name'=>$class]);
            }
        }
    }
}
