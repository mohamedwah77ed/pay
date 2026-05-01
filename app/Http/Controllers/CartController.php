<?php

namespace App\Http\Controllers;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

public function addToCart($product_id)
{
    $product = Product::findOrFail($product_id);

    // لو المستخدم مش مسجل - Session
    if (!auth()->check()) {
        $cart = session()->get('cart', []);

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity']++;
        } else {
            $cart[$product_id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'تم إضافة المنتج للسلة');
    }

    // لو مسجل دخول - Database
    $user = auth()->user();

    $item = CartItem::where('user_id', $user->id)
        ->where('product_id', $product_id)
        ->first();

    if ($item) {
        $item->quantity++;
        $item->save();
    } else {
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
            'quantity' => 1,
        ]);
    }

    return back()->with('success', 'تم إضافة المنتج للسلة');
}


 public function cart()
{
    if (!auth()->check()) {
        // ضيف
        $cart = session()->get('cart', []);
        return view('cart.index', ['sessionCart' => $cart]);
    }

    // مستخدم مسجّل
    $items = CartItem::with('product')
        ->where('user_id', auth()->id())
        ->get();

    return view('cart.index', ['dbCart' => $items]);
}
public function remove($product_id)
{
    // ضيف
    if (!auth()->check()) {
        $cart = session()->get('cart', []);
        unset($cart[$product_id]);
        session()->put('cart', $cart);
        return back();
    }

    // مسجل
    CartItem::where('user_id', auth()->id())
        ->where('product_id', $product_id)
        ->delete();

    return back();
}
public function increase($product_id)
{
    // ضيف
    if (!auth()->check()) {
        $cart = session()->get('cart', []);

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity']++;
            session()->put('cart', $cart);
        }

        return back();
    }

    // مستخدم
    $item = CartItem::where('user_id', auth()->id())
        ->where('product_id', $product_id)
        ->first();

    if ($item) {
        $item->quantity++;
        $item->save();
    }

    return back();
}

public function decrease($product_id)
{
    // ضيف
    if (!auth()->check()) {
        $cart = session()->get('cart', []);

        if (isset($cart[$product_id])) {

            // لو الكمية 1 → نحذف المنتج
            if ($cart[$product_id]['quantity'] <= 1) {
                unset($cart[$product_id]);
            } else {
                $cart[$product_id]['quantity']--;
            }

            session()->put('cart', $cart);
        }

        return back();
    }

    // مستخدم مسجل
    $item = CartItem::where('user_id', auth()->id())
        ->where('product_id', $product_id)
        ->first();

    if ($item) {
        if ($item->quantity <= 1) {
            $item->delete();
        } else {
            $item->quantity--;
            $item->save();
        }
    }

    return back();
}
}
