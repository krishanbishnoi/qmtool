<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Support\Facades\Hash;
Use App\User;
Use App\Product;
Use App\ProductImage;
Use App\Category;

class ProcuctController extends Controller
{
    public function add_product(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ]);
        if($validator->fails()) {
            $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            
            $add_product = new Product;
            $add_product->name = $request->name;
            $add_product->category_id = $request->category_id;
            $add_product->price = $request->price;
            $add_product->offer_price = $request->offer_price;
            $add_product->quantity = $request->quantity;
            $add_product->description = $request->description;
            

			if($add_product->save()) {
                       
                $data=array('status'=>1,'message'=>'Product added Successfully');
                                    
              
            } else {
                $data=array('status'=>0,'message'=>"Some error accur.",'data'=> array());
            }                       
            return response(json_encode($data),200);
        }      
    }
}
