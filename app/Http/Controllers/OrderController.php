<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\PasswordReset;
use DB;
class OrderController extends Controller
{
   
	/*public function user_address_list(Request $request)
	{
	    $q = $request->get('q');
	
	    return Address::where('title', 'like', "%$q%")->paginate(null, ['id', 'title as text']);
	}

	public function user_address_list_by(Request $request)
	{
	        $provinceId = $request->get('q');
			$r["id"]=-1;
			$r["text"]="sdfsd-1fsd";
			$res[] =$r ;
			$r["id"]=-2;
			$r["text"]="sdfsdf-2sd";
			$res[] =$r ;
			$res2=Address::where('user_id', $provinceId)->get(['id', DB::raw('title as text')]);
			foreach($res2 as $r2){
				$res[] =$r2 ;
			}
			return $res;
	}
*/
public function orders(Request $request)
	{ 
	  if(auth()->user()){
	   	$user_id=auth()->user()->id;
	   	if($user_id){
		   	$result=Controller::getStandartResponce(200);
		   	$result["result"]=Order::where('user_id', '=', $user_id)->paginate(null, ['id', 'total_price']);//'title as text'
		   	return $result;
	   	}
		 return  Controller::getStandartResponce(401);
	  }
	  return  Controller::getStandartResponce(401);
	   
	}
public function order_by_id(Request $request)
	{ 
	  if(auth()->user()){
	   	$user_id=auth()->user()->id;
	   	if($user_id){
		   	$result=Controller::getStandartResponce(200);
		   	$result["result"]=Order::where('user_id', '=', $user_id)->paginate(null, ['id', 'total_price']);//'title as text'
		   	return $result;
	   	}
		 return  Controller::getStandartResponce(401);
	  }
	  return  Controller::getStandartResponce(401);
	   
	}
}


