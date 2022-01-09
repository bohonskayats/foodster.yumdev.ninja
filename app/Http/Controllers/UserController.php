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
			//need to delete other codes !!!!!!!!!!!!!	
			$res=UserLoginCode::where('user_id',$user_id)
			->delete();

			
			
			return true;
		}
		return  false;		
		
	}

	
	
	//---------------------------------
	public function getNewCode($user_id,$user){
		$created_at_max=date('Y-m-d H:i:s',strtotime('-'.$this->time_ago.' hour'));
		$res=UserLoginCode::where('created_at',"<",$created_at_max)
			->where('user_id',$user_id)
			->delete();
		$CodesCount = DB::table('user_login_codes')->where('user_id', $user_id)
			        ->count();
		if($CodesCount>=$this->count_per_time){
			// try late
			return -1;

		}
		$new_code=$this->generateRandomString();
		$code_result = UserLoginCode::create([
			'code' => $new_code,
			'user_id'=>$user_id
	 	]);
			
		if($code_result!=null){
				$new_code=$code_result->code;
				$this->sendGCM(true,$user->fcm_token,$user->system);
				//var_dump( $this->sendGCM(true,$user->fcm_token));
				
			}
			else{
				$new_code=-1;
			}
		
		
		return $new_code;
		
		
	}
	public function getResponceForNewCode($new_code){
		
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
                'message' => 'Need new code/ already exist',
            ], 405);

		
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
	    $fcm_token=$request->fcm_token;

        $current_user_data = DB::table('users')
			->where('email', $request->email)
            ->select('users.*')
            ->first();
            
        $new_code=$this->generateRandomString(4); 
           
		if($current_user_data!=null){
			$new_code=getNewCode($current_user_data->id,$current_user_data);
			
			return $this->getResponceForNewCode($new_code);

		
		}
        
		$user = new User($request->all());
		$user->save();   
		$new_code=getNewCode($user->id,$user);

		return response()->json([
                'success' => false,
                'code'=>$new_code,
                'message' => 'see the code',
            ], 200);

    }
    public function login(Request $request)
    {
        
        $input = $request->only('email','remember_token','code','fcm_token','system');
       
        $input_remember_token="";
        $input_fcm_token="";

        $input_code="";
        $input_system="";
        
		if (isset($input['remember_token'])) {
		  $input_remember_token=$input['remember_token'];
		}
		
		if (isset($input['fcm_token'])) {
		  $input_fcm_token=$input['fcm_token'];
		}

		
		if (isset($input['code'])) {
		  $input_code=$input['code'];
		}

		if (isset($input['system'])) {
		  $input_system=$input['system'];
		}


	    //$system=$request->system;
	    $system_ready=0;
	    if(strtolower($input_system)=="android"){
		    $system_ready=1;
	    }
	     if(strtolower($input_system)=="apple"){
		    $system_ready=2;
	    }

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
				$request->request->add(['remember_token',"" ]);
				$request->request->add(['fcm_token',$input_fcm_token ]);
				$request->request->add(['system',$system_ready ]);
				$request->merge(['system' =>$system_ready]);

				//$update_fcm = $request->only('email');
				$this->update($request);
				
				 return response()->json([
		            'success' => true,
		            'message' => "Login success",
		            
		        ],200);

				
			}
			//need new code
			$new_code=$this->getNewCode($current_user_data->id,$current_user_data);
			return $this->getResponceForNewCode($new_code);


	        
        }
        else if ($input_code!=""){

	      	if($this->getCheckCode($current_user_data->id,$input_code)){
		      	//need get/render token
		      	$new_token=Str::random(60);

		      	$request->request->add(['remember_token' => $new_token]);
		      	$request->request->add(['system',$system_ready ]);
		      	$request->merge(['system' => "".$system_ready]);
		      	//$request->request->system=$system_ready;
		      	$request->request->add(['fcm_token',$input_fcm_token ]);

//!!!!!!!!!!!1


		      	return $this->update($request);
	      	}

	      	$new_code=$this->getNewCode($current_user_data->id,$current_user_data);
			return $this->getResponceForNewCode($new_code);

	        
        }
	      	$new_code=$this->getNewCode($current_user_data->id,$current_user_data);
			return $this->getResponceForNewCode($new_code);


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

  // $input = $request->only('remember_token');

  //  unset($input["remember_token"]);



	$input = $request->only('remember_token','fcm_token','system');
	//var_dump($input);exit;
    $updatedUser = User::where('id', $user->id)  ->update($input);
    //->update("reme");
    //$user =  User::find($user->id);
	$user = DB::table('users')
			->where('email', $request->email)
            ->select('users.email','users.remember_token as token','users.id','users.system')
            ->first();
    if($user->system==1){
	    $user->system="Android";
    }   
   else if($user->system==2){
	    	    $user->system="Apple";

    }     
    else{  $user->system="Unknown";}
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

//---------------------------------------------

	function sendGCM( $is_need_body,$fcm_id) {
  // FCM API Url
  $url = 'https://fcm.googleapis.com/fcm/send';

  // Put your Server Response Key here
  $apiKey = config('firebase_api_key_android');
  //var_dump($apiKey);
  //"AAAAqqvhjQ4:APA91bFcqd23vlIna8hpR8ojE_ZOhG0E7RPszvzboUgenO-ga0UQbUaANF_eipWMIpvSk071iFXgH2vw9LLa6CTK4BByy8jgOvUIqvcUPwT3OuSlPHu8_IybA4xnwtRJgoDWelZVQvV2";

  // Compile headers in one variable
  $headers = array (
    'Authorization:key=' . $apiKey,
    'Content-Type:application/json'
  );


  $msg = array
(
	'key1' 	=> 'here is a message. message!++++++!!!???',
	'key2'		=> 'This is a title. title',
	'needreload'=>true
	
);
  
  
  $notifData = [
    'title' => "Test Title1",
 
    'body' => "Test notification body",
  //  'sound' => 'es_chime_musical_4.mp3',
 //   'android_channel_id'=> 'fcm_default_channel'

    
    // 'click_action' => "android.intent.action.MAIN"
  ];

  // Create the api body
  //token from mobile app
  $id=$fcm_id;
  //"c3Smzj7QQ1qe7pz0s3Js1n:APA91bFgyUf9_KxvNxAeRxTw8P67X6OwZKsafTAv3upd78PGthxeujeh-rVcfgzoPf6oPJ_aOvN3Ss6KKoMnmQvdVX5bZR93NM66agu5MBI6SgQSo8tuqFDhlSvOIb20Nj3vL2VsjiUS";
  //"e4QKDFrFQgG4MZWsYkQW2b:APA91bE3QNxglVkLkHXM2qT8rQyUuckN0CQPna1WZtFnlIaqguGcwvCt8SjsW6rXXIbJDYmu-Ph8kvYMoUcP3q5pqE7cejGDfQ6KZ46Tf1K7Ggno4m4y1Xx-a3iuwmnBFuMWwJSPupta";
  //"ejeDifUhS16uQ9BFlidyPj:APA91bGk0FmOF-Qpr3nRswGZGwug5y1ojGTSNe48dyihhs_jbQCsNbLUqXh6-jHXKXkffY8RbssN-JolQ8ENudpvWGTOsRkhJycPOP1w3e4ETtnrSQxzsoFn6fpvpgtI5hFOweh51l0v";
 if($is_need_body){
	  $apiBody = [
    'notification' => $notifData,
   //  'priority'=>'high',

	'android'=> array (
		 'android_channel_id'=> 'fcm_default_channel',

		'notification' => array (
	//		    'sound' => 'es_chime_musical_4',
    'android_channel_id'=> 'fcm_default_channel'

		),
	//      'priority'=>'high'
	   ),
    // 'content_available'=> true,
  //  'priority'=>'high',
   // 'data' => $msg,
    'registration_ids' => array (
                    $id
            ),

    "time_to_live" => 600, // Optional
   // 'to' => '/weather' // Replace 'mytargettopic' with your intended notification audience
  ];
 }
 else{
	  $apiBody = [
   // 'notification' => $notifData,

    
    'data' => $msg,
    'registration_ids' => array (
                    $id
            ),

    "time_to_live" => 600, // Optional
   // 'to' => '/weather' // Replace 'mytargettopic' with your intended notification audience
  ];
 }
 

  // Initialize curl with the prepared headers and body
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $url );
  curl_setopt ($ch, CURLOPT_POST, true );
  curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

  // Execute call and save result
  $result = curl_exec ( $ch );
  //print_r ($result);
  // Close curl after call
  curl_close ( $ch );

  return $result;
}



}


