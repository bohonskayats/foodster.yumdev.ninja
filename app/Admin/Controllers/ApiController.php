<?php

namespace App\Admin\Controllers;
use Encore\Admin\Controllers\AdminController;
use Illuminate\Http\Request;

use DB;
use App\Models\User;
use App\Models\Address;

class ApiController extends AdminController
{
   public function user_list_by( Request $request)
	{
	    $q = $request->get('q');
	
	    return User::where('email', 'like', "%$q%")->paginate(null, ['id', 'email as text']);
	}
	
	
	public function user_address_list_by(Request $request)
	{
	        $user_id = $request->get('u');
	        $address_text = $request->get('q');

			$res= array();
			if($user_id==""){
				$user_id="-1";
			}
			
			
			if($address_text==""){
				return Address::where('user_id', $user_id)
				//->where('title', 'like', "%$address_text%")
				//->get(['id', DB::raw('title as text')])
				->paginate(null, ['id', 'title as text']);
				
			}
			else{
				return Address::where('user_id', $user_id)
				->where('title', 'like', "%$address_text%")
				//->get(['id', DB::raw('title as text')])
				->paginate(null, ['id', 'title as text']);
				
			}
			
			/*foreach($res2 as $r2){
				$res[] =$r2 ;
			}*/
			//return $res;

	}

}
