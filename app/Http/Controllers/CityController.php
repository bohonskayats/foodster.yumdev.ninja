<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\PasswordReset;
use DB;
class CityController extends Controller
{
   
	public function cities_list(Request $request)
	{
	    $q = $request->get('q');
		if( $q==""){
			 return City::paginate(null, ['id', 'title as text']);

		}
	    return City::where('title', 'like', "%$q%")->paginate(null, ['id', 'title as text']);
	}



}


