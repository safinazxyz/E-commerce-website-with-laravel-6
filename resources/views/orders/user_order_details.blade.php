@extends('layouts.frontLayout.front_design')
@section('content')
    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="{{ url('/user-orders') }}">Orders</a></li>
                    <li class="active">Order Details {{$orderDetails->id}}</li>
                </ol>
            </div>
        </div>
    </section>
    <section id="do_action">
        <div class="container">
            <div class="heading" align="center">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>

                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Product Size</th>
                        <th>Product Color</th>
                        <th>Product Quantity</th>
                        <th>Product Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orderDetails->orders as $order)
                        <tr>
                            <td>{{ $order->product_code }}</td>
                            <td>{{ $order->product_name}}</td>
                            <td>{{ $order->product_size }}</td>
                            <td>{{ $order->product_color }}</td>
                            <td>{{ $order->product_qty }}</td>
                            <td>TL {{ $order->product_price }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
