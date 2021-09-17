<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    public $timestamps = false;
    
    public function getList(){
        return $this->get();
    }
}
