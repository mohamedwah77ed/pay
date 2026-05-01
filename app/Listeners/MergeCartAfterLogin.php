<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\CartItem;

class MergeCartAfterLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $sessionCart = session()->get('cart', []);

        foreach ($sessionCart as $product_id => $item) {

            $existing = CartItem::where('user_id', $user->id)
                ->where('product_id', $product_id)
                ->first();

            if ($existing) {
                $existing->quantity += $item['quantity'];
                $existing->save();
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product_id,
                    'quantity' => $item['quantity']
                ]);
            }
        }

        session()->forget('cart');
    }
}