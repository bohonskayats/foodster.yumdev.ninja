<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\PasswordReset;
use DB;
class CategoryController extends Controller
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
/*public function orders(Request $request)
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
	   
	}*/
public function categories(Request $request)
	{ 
	  
	  /* return response()->json([
                'success' => true,
                'message' => '',
                'results'=>Category::all(['id', 'title']),
                //Category::all()->paginate(null, ['id', 'title'])
            ]);
	   */
	   return response()->json(
                Category::all(['id', 'title']),
                //Category::all()->paginate(null, ['id', 'title'])
            );
	}
	/*
	not need	
		public function categories_for_select(Request $request)
	{ 
	  
	  $res0=Category::all(['id', 'title']);
	 // $res2=Address::where('user_id', $provinceId)->get(['id', DB::raw('title as text')]);
			foreach($res0 as $key=>$value){
				$res2[$value["id"]] =$value["title"] ;
			}
			//var_dump($res2);exit;
	   return response()->json(
               $res2,
                //Category::all()->paginate(null, ['id', 'title'])
            );
	   
	}*/
}


