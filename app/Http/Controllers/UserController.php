<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
 use Laravel\Passport\HasApiTokens;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLoginCode;

use DB;

use Auth;
//use JWTAuth;
/*
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
*/
use JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\PasswordReset;

class UserController extends Controller
//class UserController extends Authenticatable implements JWTSubject
{
	    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','user_list']]);
    }

	public int $count_per_time=3;
	public int $time_ago=1;//hours
	//--------------------------------

	public function getCheckCode($user_id,$code){
		$created_at_max=date('Y-m-d H:i:s',strtotime('-'.$this->time_ago.' hour'));
		$res=UserLoginCode::where('created_at',"<",$created_at_max)
			->where('user_id',$user_id)
			->delete();
		$CodesCount = DB::table('user_login_codes')
				->where('user_id', $user_id)
				->where('code', $code)
			    ->count();
		if($CodesCount>0){
			return true;
		}
		return  false;		
		
	}

	
	
	//---------------------------------
	public function getResponceForNewCode($user_id){
		$created_at_max=date('Y-m-d H:i:s',strtotime('-'.$this->time_ago.' hour'));
		//strtotime('-$time_ago seconds')
		//var_dump($created_at_max);exit;
		$res=UserLoginCode::where('created_at',"<",$created_at_max)
			->where('user_id',$user_id)
			->delete();
			
			
			// Query builder
		$CodesCount = DB::table('user_login_codes')->where('user_id', $user_id)
			        ->count();
			
			// Eloquent
			//$wordCount = Wordlist::where('id', '<=', $correctedComparisons)->count();			
			
			
			//	DishOrder:: where('order_id', $order_id) -> delete ();
			//var_dump($CodesCount);
		if($CodesCount>=$this->count_per_time){
				// try late
			return -1;/*response()->json([
	                'success' => false,
	                'code'=>-1,
	                'message' => 'Try later too many login attempts',
	           ], 405);*/

		}
		$code_result = UserLoginCode::create([
			'code' => $new_code,
			'user_id'=>$user_id
	 	]);
			
		if($code_result!=null){
				$new_code=$code_result->code;
			}
			else{
				$new_code=-1;
			}
		
		
		return $new_code;/*response()->json([
                'success' => true,
                'code'=>$new_code,
                'message' => 'Is already exist',
        ], 405);*/
		
		
	}
	
	//--------------------------------
	
	
	public function sendFCMNotification($type,$fcm_token){
		 
	
	}
	public function generateRandomString($length = 4) {
    $characters = '0123456789';
    //abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


	
    public function register(Request $request){
	   // var_dump($this->guard()->factory()->getTTL() * 60);
	   // exit;
	    $fcm_token=$request->fcm_token;
        //$plainPassword=$request->password;
        //$password=bcrypt($request->password);
        //$request->request->add(['password' => $password]);
         $current_user_data = DB::table('users')
			->where('email', $request->email)
            ->select('users.*')
            ->first();
            
        $new_code=$this->generateRandomString(4); 
           
		if($current_user_data!=null){
			$new_code=getResponceForNewCode($current_user_data->id);
			//how many code from 
			// date('Y-m-d', strtotime('+1 week')) ."\n";
			//date('Y-m-d H:i:s','1299762201428')
			//$created_at_max=date('Y-m-d H:i:s',strtotime('+0 hour'));
			//var_dump($created_at_max);
			/*$created_at_max=date('Y-m-d H:i:s',strtotime('-'.$this->time_ago.' hour'));//strtotime('-$time_ago seconds')
			//var_dump($created_at_max);exit;
			$res=UserLoginCode::where('created_at',"<",$created_at_max)
			->where('user_id',$current_user_data->id)
			->delete();
			
			
			// Query builder
			$CodesCount = DB::table('user_login_codes')->where('user_id', $current_user_data->id)
			            ->count();
			
			// Eloquent
			//$wordCount = Wordlist::where('id', '<=', $correctedComparisons)->count();			
			
			
			//	DishOrder:: where('order_id', $order_id) -> delete ();
			//var_dump($CodesCount);
			if($CodesCount>=$this->count_per_time){
				// try late
				return response()->json([
	                'success' => false,
	                'code'=>-1,
	                'message' => 'Try later too many login attempts',
	            ], 405);

			}
			$code_result = UserLoginCode::create([
				    'code' => $new_code,
				    'user_id'=>$current_user_data->id
	 			]);
			
			if($code_result!=null){
				$new_code=$code_result->code;
			}
			else{
				$new_code=-1;
			}
			return response()->json([
                'success' => true,
                'code'=>$new_code,
                'message' => 'Is already exist',
            ], 405);
		*/	
			if($new_code==-1){
				return response()->json([
	                'success' => false,
	                'code'=>$new_code,
	                'message' => 'Try later too many login attempts',
	            ], 405);

			}
			return response()->json([
                'success' => true,
                'code'=>$new_code,
                'message' => 'Is already exist',
            ], 405);

		
		}					   //remember_token
		/*$new_token=Str::random(60);
		
		$request->request->add(['remember_token' => $new_token]);*/
        // create the user account 
        
        
        //var_dump($request->all());
        //$created=User::create($request->all());
        
		$user = new User($request->all());
		//$user->remember_token = $new_token;
		$user->save();   
		$new_code=getResponceForNewCode($user->id);

		return response()->json([
                'success' => false,
                'code'=>$new_code,
                'message' => 'see the code',
            ], 200);

		     
        //$request->request->add(['password' => $plainPassword]);
        // login now..
        //return $this->login($request);
    }
    public function login(Request $request)
    {
        
        $input = $request->only('email','remember_token','code');
        $input_remember_token="";
        $input_code="";
		if (isset($input['remember_token'])) {
		  $input_remember_token=$input['remember_token'];
		}
		if (isset($input['code'])) {
		  $input_code=$input['code'];
		}
		//var_dump($input_remember_token);
		//var_dump($input_code);
        //var_dump($input);exit;

        $current_user_data = DB::table('users')
			->where('email', $request->email)
            ->select('users.*')
            ->first();

        if($current_user_data==null){
	        //user not exist;
	        return response()->json([
                'success' => false,
                'message' => 'user not exist',
            ], 401);
        }
        
        if($input_remember_token!=""){
	      	$r_token=$current_user_data->remember_token;    
			if($r_token==$input_remember_token && $r_token!=null 
			&& trim($r_token)!=""){
				 return response()->json([
		            'success' => true,
		            'message' => "Login success",
		            
		        ],200);

				
			}
			//need new code
			$new_code=getResponceForNewCode($current_user_data->id);
			if($new_code==-1){
				return response()->json([
                'success' => false,
                'message' => 'Too many',
            ], 401);

			}
			
	        return response()->json([
                'success' => false,
                'code'=>$new_code,
                'message' => 'Invalid or Old token, you get new code',
            ], 405);


	        
        }
        else if ($input_code!=""){
	      	if($this->getCheckCode($current_user_data->id,$input_code)){
		      	//need get/render token
		      	$new_token=Str::random(60);
		      	$request->request->add(['remember_token' => $new_token]);

		      	return $this->update($request);
	      	}
	      	
	      	$new_code=getResponceForNewCode($current_user_data->id);

			if($new_code==-1){
				return response()->json([
	                'success' => false,
	                'message' => 'Too many',
	            ], 401);

			}
			
	        return response()->json([
                'success' => false,
                'code'=>$new_code,
                'message' => 'Invalid or Old token, you get new code',
            ], 405);


	        
        }

		//need new code
		//...

        var_dump($input);exit;
        
        $current_user_data = DB::table('users')
			->where('email', $request->email)
            ->select('users.*')
            ->first();
            
            //var_dump($current_user_data);
        $r_token=$current_user_data->remember_token;    
		if($current_user_data->remember_token==$request->remember_token && $r_token!=null 
		&& trim($r_token)!=""){
			
		}
		else{
			return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Old token',
            ], 401);
		}
       /* $credentials = $request->only('email');x

       if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        JWTAuth::attempt($input);
		*/
        /*return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
*/
        // get the user 
        $user = Auth::user();
       
        return response()->json([
            'success' => true,
            'token' => $r_token,
            'user' => $user
        ]);
    }
    public function logout(Request $request)
    {
        if(!User::checkToken($request)){
            return response()->json([
             'message' => 'Token is required',
             'success' => false,
            ],422);
        }
        
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
 protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
    public function checkToken(Request $request){
       
       return  true;
       
       }    
    
    public function getCurrentUser(Request $request){
       if(!$this->checkToken($request)){//User::checkToken
           return response()->json([
            'message' => 'Token is required'
           ],422);
       }
        
        /*$user = JWTAuth::parseToken()->authenticate();
       // add isProfileUpdated....
       $isProfileUpdated=false;
        if($user->isPicUpdated==1 && $user->isEmailUpdated){
            $isProfileUpdated=true;
            
        }
        $user->isProfileUpdated=$isProfileUpdated;
*/

        $user = DB::table('users')
			->where('email', $request->email)
            ->select('users.email','users.remember_token as token','users.id')
            ->first();


        return $user;
}

   
public function update(Request $request){
    $user=$this->getCurrentUser($request);
    if(!$user){
        return response()->json([
            'success' => false,
            'message' => 'User is not found'
        ]);
    }
  // var_dump($request)//;exit;
  // $input = $request->only('remember_token');

  //  unset($input["remember_token"]);
  // var_dump($user);exit;

//var_dump($user);exit;
$input = $request->only('remember_token','fcm_token');
    $updatedUser = User::where('id', $user->id)  ->update($input);
    //->update("reme");
    //$user =  User::find($user->id);
 $user = DB::table('users')
			->where('email', $request->email)
            ->select('users.email','users.remember_token as token','users.id')
            ->first();
    return response()->json([
        'success' => true, 
        'message' => 'Information has been updated successfully!',
        'user' =>$user
    ]);
}

	public function user_list(Request $request)
	{
		///    $user=$this->getCurrentUser($request);

	    $q = $request->get('q');
	
	    return User::where('email', 'like', "%$q%")->paginate(null, ['id', 'email as text']);
	}



}


