<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    
    
        protected $fillable = [
        'street',
        'apartment',
        'intercom',
         'floor',
        'city_id',
        'user_id',
        'title'

    ];
    public function getList(){
        return $this->get();
    }
    
    public function User(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function City(){
        return $this->hasOne(City::class, 'id', 'city_id');
    }

}
