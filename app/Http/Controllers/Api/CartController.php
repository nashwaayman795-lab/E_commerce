<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;

class CartController extends Controller
{
    public function add(Request $request){
        $user = $request->user();
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
            'product_id'=>['required','integer','exists:products,id'],
            'quantity'=>['required','integer','min:1']
        ]);

        $cartItem = CartItem::create([
            'user_id'=>$user->id,
            'product_id'=>$validate['product_id'],
            'quantity'=>$validate['quantity']
        ]);

        return response()->json([
            'status'=> true,
            'message'=>[
                'ar'=>'تم اضافة المنتج للسلة',
                'en'=>'product added to cart successfully'
            ],
            'data'=>[
                'id'=>$cartItem->id,
                'user_id'=>$cartItem->user_id,
                'product_id'=>$cartItem->product_id,
                'product'=>$cartItem->product?->name,
                'quantity'=>$cartItem->quantity,
            ]
            ]);
    }

    public function index(Request $request){
        $user = $request->user();
        if(!$user){
            return response()->json([
                'status'=> false,
                'message'=>[
                    'ar'=>'يجب تسجيل الدخول',
                    'en'=>'Unauthenticated'
                ]
                ]);
        }

        $cartItem= CartItem::with('product')->where('user_id',$user->id)->get();
          if($cartItem->isEmpty()){
            return response()->json([
                'status'=> false,
                'message'=> [
                    'ar'=>'السلة فارغة',
                    'en'=>'Cart is empty'
                ],
                'data'=>[]
                
            ]);
        }
        
         return response()->json([
            'status'=> true,
            'message'=>[
                'ar'=>'تم جتب منجات السلة بنجاح',
                'en'=>'Cart fetched'
            ],
            'data'=>$cartItem
        ]);
    }
}
