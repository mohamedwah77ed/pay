@extends('layouts.main')

@section('content')


<h2>سلة المشتريات</h2>

{{-- ضيف --}}
@if(isset($sessionCart))
<table class="table">
    <tr>
        <th>اسم المنتج</th>
        <th>السعر</th>
        <th>الكمية</th>
        <th>إجمالي</th>
        <th></th>
    </tr>

    @foreach($sessionCart as $id => $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td>{{ $item['price'] }}</td>
        <td>{{ $item['quantity'] }}</td>
        <td>{{ $item['price'] * $item['quantity'] }}</td>

          <td>
            <a href="{{ route('cart.decrease', $id) }}" class="btn btn-sm btn-warning">-</a>
            <span class="mx-2">{{ $item['quantity'] }}</span>
            <a href="{{ route('cart.increase', $id) }}" class="btn btn-sm btn-success">+</a>
        </td>
        <td>

            <a href="{{ route('cart.remove', $id) }}">حذف</a>
        </td>
    </tr>
    @endforeach
</table>
@endif


{{-- مسجّل --}}
@if(isset($dbCart))
<table class="table">
    <tr>
        <th>اسم المنتج</th>
        <th>السعر</th>
        <th>الكمية</th>
        <th>إجمالي</th>
        <th></th>
    </tr>

    @foreach($dbCart as $item)
    <tr>
        <td>{{ $item->product->name }}</td>
        <td>{{ $item->product->price }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ $item->product->price * $item->quantity }}</td>
          <td>
            <a href="{{ route('cart.decrease', $item->product_id) }}" class="btn btn-sm btn-warning">-</a>
            <span class="mx-2">{{ $item->quantity }}</span>
            <a href="{{ route('cart.increase', $item->product_id) }}" class="btn btn-sm btn-success">+</a>
        </td>
        <td>
            <a href="{{ route('cart.remove', $item->product_id) }}">حذف</a>
        </td>
    </tr>
    @endforeach
</table>

@endif
@endsection