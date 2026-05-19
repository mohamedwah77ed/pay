<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Cart;
class PaymobController extends Controller
{
     public function pay(Order $order)
{
    $token         = $this->getAuthToken();
    $paymobOrderId = $this->registerOrder($token, $order->total_amount);

    // ✅ احفظ الـ Paymob Order ID
    $order->paymob_order_id = $paymobOrderId;
    $order->save();

    return $this->getPaymentKey($token, $paymobOrderId, $order->total_amount, $order);
}
    public function getAuthToken()
    {
        $response = Http::post(config('services.paymob.base_url') . '/api/auth/tokens', [
            'api_key' => config('services.paymob.api_key')
        ]);

        return $response->json()['token'];
    }

    public function registerOrder($token, $amount)
    {
        $response = Http::post(config('services.paymob.base_url') . '/api/ecommerce/orders', [
            'auth_token'      => $token,
            'delivery_needed' => false,
            'amount_cents'    => $amount * 100,
            'currency'        => 'EGP',
            'items'           => []
        ]);

        return $response->json()['id'];
    }

    public function getPaymentKey($token, $orderId, $amount, $order)
    {
        $response = Http::post(config('services.paymob.base_url') . '/api/acceptance/payment_keys', [
            'auth_token'     => $token,
            'amount_cents'   => $amount * 100,
            'expiration'     => 3600,
            'order_id'       => $orderId,
            'currency'       => 'EGP',
            'integration_id' => config('services.paymob.integration_id'),
             'redirect_url'   => route('paymob.callback'),
            'billing_data'   => [
                'first_name'   => $order->first_name,
                'last_name'    => $order->last_name,
                'email'        => $order->email,
                'phone_number' => $order->phone,
                'country'      => $order->country,
                'city'         => 'NA',
                'street'       => $order->address1,
                'building'     => 'NA',
                'floor'        => 'NA',
                'apartment'    => 'NA',
            ]
        ]);

        $paymentToken = $response->json()['token'];
        $iframeId     = config('services.paymob.iframe_id');

        return redirect('https://accept.paymob.com/api/acceptance/iframes/' . $iframeId . '?payment_token=' . $paymentToken);
    }

    
    public function callback(Request $request)
{
     // dd($request->all());
    $data = $request->all();
   // dd($data['order'], Order::where('id', $data['order'])->first());

    // تحقق إن الدفع نجح
    if($data['success'] == 'true'){

        // جيب الـ Order عن طريق الـ merchant_order_id
        $order = Order::where('paymob_order_id', $data['order'])->first();


        if($order){
            // حدث الـ Order
            $order->payment_status = 'paid';
            $order->status         = 'process';
            $order->save();

            // ربط الـ Cart بالـ Order
            Cart::where('user_id', $order->user_id)
                ->where('order_id', null)
                ->update(['order_id' => $order->id]);

            // امسح الـ Session
            session()->forget(['cart', 'coupon']);

            return redirect()->route('products.index')
                             ->with('success', 'تم الدفع بنجاح');
        }
    }

    // لو الدفع فشل
    return redirect()->route('cart.index')
                     ->with('error', 'فشل الدفع حاول تاني');
}
}