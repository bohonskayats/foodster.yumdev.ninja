<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
     
    public function getList(){
        return $this->get();
    }
    public function User(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function PaymentMethod(){
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_id');
    }
    public function dishes()
    {
       
        return $this->belongsToMany(Dish::class);
    }

}
