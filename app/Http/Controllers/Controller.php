<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public   function getStandartResponce(int $status){
	    $result=[];
	    $result["status"]=$status;
	    $result["result"]=[];
	    if($status==401){
		    
	    }
	    
	    if($status==200){
		    
		    
	    }
	    return $result;
	    
    }

}
