<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\PasswordReset;
use DB;
class AddressController extends Controller
{
   
	public function user_address_list(Request $request)
	{
	    $q = $request->get('q');
	
	    return Address::where('title', 'like', "%$q%")->paginate(null, ['id', 'title as text']);
	}

	public function user_address_list_by(Request $request)
	{
	    $provinceId = $request->get('q');

			$res= array();
			$res2=Address::where('user_id', $provinceId)->get(['id', DB::raw('title as text')]);
			foreach($res2 as $r2){
				$res[] =$r2 ;
			}
		return $res;
  //  return Address::where('user_id', $provinceId)->get(['id', DB::raw('title as text')]);

	 //   $q = $request->get('q');
	
	  //  return Address::where('title', 'like', "%$q%")->paginate(null, ['id', 'title as text']);
	}


}


