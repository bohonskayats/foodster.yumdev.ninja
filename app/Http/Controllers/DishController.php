<?php

namespace App\Http\Controllers;
use \Encore\Admin\Traits\Resizable;

use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Parameter;
use App\Models\DishParameter;

use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\PasswordReset;
use DB;
use File;
class DishController extends Controller {


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
	public function all_dishes(Request $request) {
		$res2 = Dish:: paginate(5, ['id', 'title', 'description', 'picture', 'base_price']);
		$res_0 = [];
		foreach($res2 as $r2){
			$elem = $r2;
			$elem["description"] = strip_tags($r2["description"]);
			$file_name = File:: name($elem["picture"]);
			$file_extention = File:: extension($elem["picture"]);
			$file_path = File:: dirname($elem["picture"]);
			$elem["thumbnail_small"] = $file_path.'/'.$file_name."-small.".$file_extention;
			$elem["thumbnail_middle"] = $file_path.'/'.$file_name."-middle.".$file_extention;
			$elem["description"] = str_replace('&nbsp', " ", $elem["description"]);
			$res_0[] = $elem;
		}



		return response() -> json($res_0);
	}
	//-------------------
	public function all_dishes_full(Request $request) {

		$whereData = [
			//	['dish_parameter.dish_id', $elem["id"]],
				['publish', '=', '1']
			];

		$res2 = Dish:: where($whereData)-> paginate(5, ['id', 'title', 'description', 'picture', 'base_price']);

		$res_id = [];
		foreach($res2 as $r2){
			$res_id[] = $r2["id"];
		}

		$paramenter_index = -1;
		$res_0 = [];

		foreach($res2 as $r2){
			$elem = $r2;
			$elem_params = array();
			$whereData = [
				['dish_parameter.dish_id', $elem["id"]],
				['publish', '=', '1']
			];
			$res3 = DishParameter:: join('parameters', 'parameters.id', '=', 'dish_parameter.parameter_id')            					//->where('dish_parameter.dish_id', $elem["id"])
				-> where($whereData)
				-> orderBy('order', 'desc')
				-> get();

			if (isset($res3) && count($res3) > 0)
				for ($i = 0; $i < count($res3); $i++) {
					$paramenter_index = $i;
					//$parameter_data["id"] = $res3[$paramenter_index]["id"];
					$parameter_data["parameter_id"] = $res3[$paramenter_index]["parameter_id"];
					$parameter_data["title"] =  $res3[$paramenter_index]["title"];
					$parameter_data["units"] =  $res3[$paramenter_index]["units"];
					$parameter_data["price"] =  $res3[$paramenter_index]["price"];
					$parameter_data["count"] =  0;
					$parameter_data["value"] =  $res3[$paramenter_index]["value"];

					array_push($elem_params, $parameter_data);

				}
			$elem["parameters"] = $elem_params;
			$elem["description"] = strip_tags($r2["description"]);
			$file_name = File:: name($elem["picture"]);
			$file_extention = File:: extension($elem["picture"]);
			$file_path = File:: dirname($elem["picture"]);
			$elem["thumbnail_small"] = $file_path.'/'.$file_name."-small.".$file_extention;
			$elem["thumbnail_middle"] = $file_path.'/'.$file_name."-middle.".$file_extention;
			$elem["description"] = str_replace('&nbsp', " ", $elem["description"]);
			$res_0[] = $elem;
		}



		return response() -> json($res_0);
	}
	//------------------
	public function dishes_by_category_help(Request $request) {
		$categoryId = $request -> get('q');
		$res2 = Dish:: where('category_id', $categoryId) -> get(['id', DB:: raw('title as text')]);


		return response() -> json([
			'success' => true,
			'message' => '',
			'results'=> $res2,
			//Category::all()->paginate(null, ['id', 'title'])
		]);

	}
	public function dishes_by_category(Request $request) {

		$categoryId = $request -> get('q');
		$res_0 = [];
		$res2 = Dish:: where('category_id', $categoryId) -> paginate(5, ['id', 'title', 'description', 'picture', 'base_price']);
		foreach($res2 as $r2){
			$elem = $r2;

			$elem["description"] = strip_tags($r2["description"]);
			$file_name = File:: name($elem["picture"]);
			$file_extention = File:: extension($elem["picture"]);
			$file_path = File:: dirname($elem["picture"]);
			$elem["thumbnail_small"] = $file_path.'/'.$file_name."-small.".$file_extention;
			$elem["thumbnail_middle"] = $file_path.'/'.$file_name."-middle.".$file_extention;
			$elem["description"] = str_replace('&nbsp', " ", $elem["description"]);
			$res_0[] = $elem;
		}



		return response() -> json($res_0);


	}



	public function top_dishes(Request $request) {
		$page = $request -> get('q');

		return response() -> json([
			'success' => true,
			'message' => '',
			'results'=> Dish:: all(['id', 'title']),
			//Category::all()->paginate(null, ['id', 'title'])
		]);

	}
	public function top_dishes_list(Request $request) {
		$res = Dish:: paginate(5, ['id', 'title', 'description', 'picture']);
		foreach($res as $r2){
			$elem = $r2;
			$elem["description"] = strip_tags($r2["description"]);
			$file_name = File:: name($elem["picture"]);
			$file_extention = File:: extension($elem["picture"]);
			$file_path = File:: dirname($elem["picture"]);
			$elem["thumbnail_small"] = $file_path.'/'.$file_name."-small.".$file_extention;
			$elem["thumbnail_middle"] = $file_path.'/'.$file_name."-middle.".$file_extention;
			$elem["description"] = str_replace('&nbsp', " ", $elem["description"]);
			$res_0[] = $elem;
		}
		return response() -> json($res_0);

	}
	public function recommended_dishes(Request $request) {

		return response() -> json([
			'success' => true,
			'message' => '',
			'results'=> Dish:: all(['id', 'title']),
			//Category::all()->paginate(null, ['id', 'title'])
		]);

	}


	//---------------------
	/*
	*/
	public function dish($dish_id) {
		$res_0 = [];

		$res = Dish:: where('id', $dish_id) -> get(['id', 'title', 'description', 'picture', 'base_price']);
		//->paginate(1,['id', 'title','description','picture','base_price']);
		foreach($res as $r2){
			$elem = $r2;
			$elem["description"] = strip_tags($r2["description"]);
			$file_name = File:: name($elem["picture"]);
			$file_extention = File:: extension($elem["picture"]);
			$file_path = File:: dirname($elem["picture"]);
			$elem["thumbnail_small"] = $file_path.'/'.$file_name."-small.".$file_extention;
			$elem["thumbnail_middle"] = $file_path.'/'.$file_name."-middle.".$file_extention;
			$elem["description"] = str_replace('&nbsp', " ", $elem["description"]);
			$res_0[] = $elem;
		}
		return response() -> json($res_0);
	}


}


