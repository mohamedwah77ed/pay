<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cart;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    $orders = Order::with('user')
                   ->orderBy('created_at', 'desc')
                   ->get();

    return view('admin.orders.index', compact('orders'));
}

    /**
     * Show the form for creating a new resource.
     */
    // في الـ OrderController
public function create()
{
    $cartItems = Cart::where('user_id', auth()->user()->id)
                     ->where('order_id', null)
                     ->get();

    if($cartItems->isEmpty()){
        session()->flash('error', 'السلة فاضية');
        return redirect()->route('cart.index');
    }

    return view('order.index', compact('cartItems'));
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
      //dd($request->all());
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'address1'   => 'required|string|max:500',
        'address2'   => 'nullable|string|max:500',
        'country'    => 'required|string|max:100',
        'phone'      => 'required|numeric|digits_between:10,15',
        'post_code'  => 'nullable|string|max:20',
        'email'      => 'required|email|max:255',
        'coupon'     => 'nullable|numeric',
        'shipping'   => 'nullable|exists:shippings,id',
    ]);

    $cartItems = Cart::where('user_id', auth()->user()->id)
                     ->where('order_id', null)
                     ->get();

    if ($cartItems->isEmpty()) {
        session()->flash('error', 'Cart is Empty!');
        return back();
    }

    try {
        $shippingPrice = 0;
        $shipping      = null;
        if ($request->filled('shipping')) {
            $shipping      = Shipping::find($request->input('shipping'));
            $shippingPrice = $shipping ? (float)$shipping->price : 0;
        }

        $couponDiscount = session('coupon') ? (float)session('coupon')['value'] : 0;
        $subTotal       = $cartItems->sum('amount');

        $order               = new Order();
        $order->order_number = 'ORD-' . strtoupper(Str::random(10));
        $order->user_id      = auth()->user()->id;
        $order->first_name   = $validated['first_name'];
        $order->last_name    = $validated['last_name'];
        $order->email        = $validated['email'];
        $order->phone        = $validated['phone'];
        $order->country      = $validated['country'];
        $order->address1     = $validated['address1'];
        $order->address2     = $validated['address2'] ?? null;
        $order->post_code    = $validated['post_code'] ?? null;
        $order->sub_total    = $subTotal;
        $order->quantity     = $cartItems->sum('quantity');
        $order->coupon       = $couponDiscount;
        $order->shipping_id  = $shipping?->id;
        $order->total_amount = $subTotal + $shippingPrice - $couponDiscount;
        $order->status       = 'new';
        $order->save();

        // ✅ وجه للـ Paymob مع الأوردر
        return redirect()->route('paymob.pay', $order->id);

    } catch (\Exception $e) {
         dd($e->getMessage());
        session()->flash('error', 'Something went wrong.');
        return back()->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
