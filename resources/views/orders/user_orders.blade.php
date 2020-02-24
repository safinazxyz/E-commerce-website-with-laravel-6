@extends('layouts.frontLayout.front_design')
@section('content')
    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Orders</li>
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
                        <th>Order ID</th>
                        <th>Ordered Products</th>
                        <th>Payment Method</th>
                        <th>Grand Total</th>
                        <th>Created on</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                @foreach($order->orders as $pro)
                                    {{ $pro->product_code }} || {{ $pro->product_name }} <br>
                                @endforeach
                            </td>
                            <td>TL {{ $order->payment_method }}</td>
                            <td>TL {{ $order->grand_total }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td><a href="{{ url('/orders/'.$order->id) }}">View Details</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
