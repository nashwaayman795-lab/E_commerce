<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request){
    $user =$request->user();
    if(!$user){
        return response()->json([
            'status'=> false,
            'message'=>[
                'ar'=>'يجب تسجيل الدخول',
                'en'=>'Unauthenticated'
            ]
            ]);
    }
        $validate= $request->validate([
            'name'=>['required','string','max:255'],
            'description'=>['nullable','string','max:2000'],
            'price'=>['required','numeric'],
            'quantity'=>['required','integer','min:1'],
            'user_id'=>['required','integer','exists:users,id'],
            'cover'   =>['required','image','mimes:jpg,png,jpeg,gif','max:2024'],
        ]);

        $product= Product::create($validate);

        $product ->addMediaFromRequest('cover')->toMediaCollection('cover');
        
        return response()->json([
            'status'=> true,
            'message'=>[
                'ar'=>'تم بنجاح',
                'en'=>'successfully'
            ],
            'data'=>[
                'id'=>$product->id,
                'name'=>$product->name,
                'description'=>$product->description,
                'price'=>$product->price,
                'quantity'=>$product->quantity,
                'user_id'=>$product->user_id,
                'user'=>$product->user?->name,
                'cover' => $product->cover_url,
            ],
            'data'=> new ProductResource($product)
            ]);
    }

    public function destroy(Request $request,$id){
        $user =$request->user();
        if(!$user){
            return response()->json([
                'status'=> false,
                'message'=>[
                    'ar'=>'يجب تسجيل الدخول',
                    'en'=>'Unauthenticated'
                ]
                ]);
        }

        $product= Product::find($id);

        if(!$product){
            return response()->json([
                'status'=> false,
                'message'=>[
                    'ar'=>'غير موجود',
                    'en'=>'not found'
                ]
                ]);
        }

        $product->delete();

        return response()->json([
            'status'=> true,
            'message'=>[
                'ar'=>'تم الحذف بنجاح',
                'en'=>'deleted successfully'
            ]
            ]);
    }

    public function update(Request $request,$id){
        $user=$request->user();

        if(!$user){
            return response()->json([
                'status'=> false,
                'message'=>[
                    'ar'=>'يجب تسجيل الدخول',
                    'en'=>'Unauthenticated'
                ]
                ]);
        }

        $product= Product::findOrFail($id);
        if(!$product){
            return response()->json([
                'status'=> false,
                'message'=>[
                    'ar'=>'غير موجود',
                    'en'=>'not found'
                ]
                ]);
        }

         $validate= $request->validate([
            'name'=>['required','string','max:255'],
            'description'=>['nullable','string','max:2000'],
            'price'=>['required','numeric'],
            'quantity'=>['required','integer'],
            'user_id'=>['required','integer','exists:users,id']
        ]);
        
        $product->update($validate);
        return response()->json([
            'status'=> true,
            'message'=>[
                'ar'=>'تم بنجاح',
                'en'=>'update successfully'
            ]
            ]);
    }

      public function search(Request $request){
        $product= Product::where('name','like','%'.$request->name.'%')->get();

        if($product->isEmpty()){
            return response()->json([
                'status'=> false,
                'message'=> 'no products found',
                'data'=>[]
                
            ]);
        }

        return response()->json([
            'status'=> true,
            'message'=>'product fetched successfully',
            'data'=> ProductResource::collection($product)
        ]);
    }

       public function index(){
        $product=Product::all();
        return response()->json([
            'status'=> true,
            'message'=> 'successfully',
            'data'=> ProductResource::collection($product)
        ]);
    }

    public function show($id){
        $product =Product::findOrFail($id);
        return response()->json([
            'status'=>true,
            'message'=>'successfully',
            'data'=> new ProductResource($product)
        ]);
    }
}
