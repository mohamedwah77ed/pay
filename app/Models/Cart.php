<?php

namespace App\Models;
use  App\Models\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
     protected $fillable=['user_id','product_id','order_id','quantity','amount','price'];
    
     public static function getAllProductFromCart(){
       return Cart::with('product')->where('user_id',auth()->user()->id)->get();
     }
            public static function getUserCart($product_id)
        {
            return self::where('user_id', auth()->user()->id)
                    ->where('product_id', $product_id)
                    ->first();
        }
           
/*
public function scopeUserCart($query, $product_id)
{
    return $query->where('user_id', auth()->user()->id)
                 ->where('product_id', $product_id);
}
     */
    
    
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

}
    //public function order(){
       // return $this->belongsTo(Order::class,'order_id');
    //}