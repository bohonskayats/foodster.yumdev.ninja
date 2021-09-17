<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
   use ModelTree, AdminBuilder;

    public $timestamps = false;
    
    public function getList(){
        return $this->get();
    }
     public function __construct(array $attributes = [])
	    {
	        parent::__construct($attributes);
	
	        $this->setParentColumn('parent_id');
	        $this->setOrderColumn('order');
	        $this->setTitleColumn('title');
	    }
}
