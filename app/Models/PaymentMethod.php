<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;

class PaymentMethod extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    public $timestamps = false;

    use ModelTree, AdminBuilder;
    
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
