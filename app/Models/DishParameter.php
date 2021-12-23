<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;

use Illuminate\Database\Eloquent\Model;

class DishParameter extends Model
{
	
	
	protected $table = 'dish_parameter';
    use HasFactory;
    use DefaultDatetimeFormat;
    public $timestamps = false;
    
    public function getList(){
        return $this->get();
    }
    public function dishes()
    {
        return $this->belongsToMany(Dish::class);
        
        
   }
    public function parameters()
    {
        return $this->belongsToMany(Parameter::class);
        
        
   }
}
