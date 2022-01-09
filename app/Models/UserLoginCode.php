<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginCode extends Model
{
        use HasApiTokens, HasFactory, Notifiable;
    use ModelTree, AdminBuilder;

        protected $fillable = [
        'code',
        'user_id',
    ];

}
