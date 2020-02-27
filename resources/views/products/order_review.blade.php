@extends('layouts.frontLayout.front_design')
@section('content')

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Order Review</li>
                </ol>
            </div><!--/breadcrums-->
            <div class="shopper-informations">
                <div class="row">
                    @if(Session::has('flash_message_error'))
                        <div class="alert alert-error alert-block" style="background-color:#f2dfd0">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{!! session('flash_message_error') !!}</strong>
                        </div>
                    @endif
                    @if(Session::has('flash_message_success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{!! session('flash_message_success') !!}</strong>
                        </div>
                    @endif
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="login-form">
                            <h2>Billing Address</h2>
                            <div class="form-group">
                                {{ $userDetails->name }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->address }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->city }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->state }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->country }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->pincode }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->mobile }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <h2></h2>
                    </div>
                    <div class="col-sm-4">
                        <div class="signup-form">
                            <h2>Shipping Adress</h2>
                            <div class="form-group">
                                {{ $shippingDetails->name }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->address }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->city }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->state }}
                            </div>
                            <div class="form-group">
                                {{$shippingDetails->country}}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->pincode }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->mobile }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="review-payment">
                <h2>Review & Payment</h2>
            </div>

            <div class="table-responsive cart_info">
                <table class="table table-condensed">
                    <thead>
                    <tr class="cart_menu">
                        <td class="image">Item</td>
                        <td class="description"></td>
                        <td class="price">Price</td>
                        <td class="quantity">Quantity</td>
                        <td class="total">Total</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total_amount = 0; ?>
                    @foreach($userCart as $cart)
                        <tr>
                            <td class="cart_product">
                                <a href=""><img style="width: 80px!important;"
                                                src="{{asset('images/backend_images/products/small/'.$cart->image)}}"
                                                alt=""></a>
                            </td>
                            <td class="cart_description">
                                <h4><a href="">{{ $cart->product_name }}</a></h4>
                                <p>{{ $cart->product_code }} | {{ $cart->size }}</p>
                            </td>
                            <td class="cart_price">
                                <p>TL {{ $cart->price }}</p>
                            </td>
                            <td class="cart_quantity">
                                <div class="cart_quantity_button">
                                    <a class="cart_quantity_up"
                                       href="{{ url('/cart/update-quantity/'.$cart->id.'/1') }}"> + </a>
                                    <input class="cart_quantity_input" type="text" name="quantity"
                                           value="{{ $cart->quantity }}" autocomplete="off" size="2">
                                    @if($cart->quantity>1)
                                        <a class="cart_quantity_down"
                                           href="{{ url('/cart/update-quantity/'.$cart->id.'/-1') }}">
                                            - </a>
                                    @endif
                                </div>
                            </td>
                            <td class="cart_total">
                                <p class="cart_total_price">TL {{ $cart->price*$cart->quantity }}</p>
                            </td>
                            <td class="cart_delete">
                                <a class="cart_quantity_delete deleteRecord'"
                                   href="{{ url('/cart/delete-product/'.$cart->id) }}"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>
                        <?php $total_amount = $total_amount + ($cart->price * $cart->quantity); ?>
                    @endforeach
                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">
                            <table class="table table-condensed total-result">
                                <tr>
                                    <td>Cart Sub Total</td>
                                    <td>TL {{$total_amount}}</td>
                                </tr>
                                <tr class="shipping-cost">
                                    <td>Shipping Cost (+)</td>
                                    <td>TL 0</td>
                                </tr>
                                <tr class="shipping-cost">
                                    <td>Discount Amount (-)</td>
                                    <td>
                                        @if(!empty(Session::get('CouponAmount')))
                                            TL {{ Session::get('CouponAmount')}}</td>
                                    @else
                                        TL 0
                                    @endif
                                </tr>
                                <tr>
                                    <td>Grand Total</td>
                                    <td>
                                        <span>TL {{ $grand_total = $total_amount - Session::get('CouponAmount') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <form name="paymentForm" id="paymentForm" action="{{ url('/place-order') }}"
                  method="post">{{ csrf_field() }}
                <input type="hidden" name="grand_total" value="{{ $grand_total }}">
                <div class="payment-options">
					<span>
						<label><strong>Select Payment Method:</strong></label>
					</span>
                    @if($codpincodeCount>0)
                        <span>
						<label><input type="radio" name="payment_method" id="COD"
                                      value="COD"><strong>COD</strong></label>
					</span>
                    @endif
                    @if($prepaidpincodeCount>0)
                        <span>
						<label><input type="radio" name="payment_method" id="Paypal"
                                      value="Paypal"><strong>Paypal</strong></label>
					</span>
                    @endif
                        <span style="float: right;">
                        <span id="chooseMethod"></span>
                        <button type="submit" class="btn btn-default" onclick="return selectPaymentMethod();">Place Order</button>
                    </span>
                </div>
            </form>
        </div>
    </section> <!--/#cart_items-->
@endsection


