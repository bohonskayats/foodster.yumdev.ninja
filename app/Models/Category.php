<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;

class Category extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    public $timestamps = false;

    use ModelTree, AdminBuilder;


	 public function __construct(array $attributes = [])
	    {
	        parent::__construct($attributes);
	
	        $this->setParentColumn('parent_id');
	        $this->setOrderColumn('order');
	        $this->setTitleColumn('title');
	    }
	//}


 /*   public static function getList(){
	            return $this->where(['publish'=>1])->orderBy('order', 'DESC')->limit(3)->get();

        //return $this->get();
    }

    protected $table = 'categories';
    public function getList2(){
        return $this->where(['publish'=>1])->orderBy('order', 'DESC')->limit(3)->get();
    }

public function toArray()
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
           // 'count'=>count($this->classes),
            //'course_id'=>$this->course_id,
            //'classes'=>new ClassResource($this->whenLoaded('classes')->slice(0,3)),

        ];
    }
*/
}
