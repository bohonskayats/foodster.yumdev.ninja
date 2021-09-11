<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    public $timestamps = false;

    //use ModelTree;

    public function getList(){
        return $this->get();
    }
    

}
