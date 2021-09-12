<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    public $timestamps = false;
    
    public function getList(){
        return $this->get();
    }
    public function Category(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }


}
