<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Products;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index()
    {
        $products = Cart::getAllProductFromCart();
        return view('cart.index', compact('products'));
    }

    public function addToCart(Request $request)
    {
        
        if (empty($request->slug)) {
            return back()->with('error', 'Invalid Products');
        }

        $product = Products::where('slug', $request->slug)->first();

        if (empty($product)) {
            return back()->with('error', 'Invalid Products');
        }

        $already_cart = Cart::getUserCart($product->id);

        if ($already_cart) {
            if ($already_cart->product->stock <= $already_cart->quantity) {
                return back()->with('error', 'Stock not sufficient!');
            }
            $already_cart->quantity = $already_cart->quantity + 1;
            $already_cart->amount   = $already_cart->price * $already_cart->quantity;
            $already_cart->save();
        } else {
            if ($product->stock <= 0) {
                return back()->with('error', 'Stock not sufficient!');
            }
            $cart             = new Cart;
            $cart->user_id    = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price      = $product->price;
            $cart->quantity   = 1;
            $cart->amount     = $product->price;
            $cart->save();
        }

        return back()->with('success', 'Product successfully added to cart');
    }

    public function increaseCart(Request $request)
    {
        $cart = Cart::getUserCart($request->product_id);

        if (!$cart) {
            return back()->with('error', 'Product not found');
        }

        if ($cart->product->stock <= $cart->quantity) {
            return back()->with('error', 'Stock not sufficient!');
        }

        $cart->quantity = $cart->quantity + 1;
        $cart->amount   = $cart->price * $cart->quantity;
        $cart->save();

        return back()->with('success', 'Cart updated');
    }

    public function decreaseCart(Request $request)
    {
        $cart = Cart::getUserCart($request->product_id);

        if (!$cart) {
            return back()->with('error', 'Product not found');
        }

        if ($cart->quantity <= 1) {
            $cart->delete();
            return back()->with('success', 'Product removed');
        }

        $cart->quantity = $cart->quantity - 1;
        $cart->amount   = $cart->price * $cart->quantity;
        $cart->save();

        return back()->with('success', 'Cart updated');
    }

    public function cartDelete(Request $request)
    {
        $cart = Cart::find($request->id);

        if ($cart) {
            $cart->delete();
            return back()->with('success', 'Cart successfully removed');
        }

        return back()->with('error', 'Error please try again');
    }
}