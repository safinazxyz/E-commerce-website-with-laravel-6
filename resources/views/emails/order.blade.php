<html>
<head>
    <title>Register Email</title>
</head>
<body>
<table width="700px">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><img src="{{ asset('images/frontend_images/home/logo.png') }}"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Hello {{$name}},</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Thank you for shipping with us. Your order details are below:-</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>{{$order_id}}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table id="example" class="table table-striped table-bordered" style="width:95%" cellspacing="5"
                   cellpadding="5" bgcolor="#f7f4f4">
                <thead>
                <tr bgcolor="#cccccc">
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Product Size</th>
                    <th>Product Color</th>
                    <th>Product Quantity</th>
                    <th>Product Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach($productDetails->orders as $product)
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name}}</td>
                        <td>{{ $product->product_size }}</td>
                        <td>{{ $product->product_color }}</td>
                        <td>{{ $product->product_qty }}</td>
                        <td>TL {{ $product->product_price }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" align="right">
                        Shipping Charges
                    </td>
                    <td>
                        {{ $productDetails->shipping_charges }} TL
                    </td>
                </tr>
                <tr>
                    <td colspan="5" align="right">
                        Coupon Discount
                    </td>
                    <td>
                        {{ $productDetails->coupon_amount }} TL
                    </td>
                </tr>
                <tr>
                    <td colspan="5" align="right">
                        Grand Total
                    </td>
                    <td>
                        {{ $productDetails->grand_total }} TL
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr width="50%">
                    <td>
                        <table>
                            <tr>
                                <td>Bill To:-</td>
                            </tr>
                            <tr>
                                <td>{{$userDetails->name}}</td>
                            </tr>
                            <tr>
                                <td>{{$userDetails->address}}</td>
                            </tr>
                            <tr>
                                <td>{{$userDetails->city}}</td>
                            </tr>
                            <tr>
                                <td>{{$userDetails->state}}</td>
                            </tr>
                            <tr>
                                <td>{{$userDetails->country}}</td>
                            </tr>
                            <tr>
                                <td>{{$userDetails->pincode}}</td>
                            </tr>
                            <tr>
                                <td>{{$userDetails->mobile}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr width="50%">
                    <td>
                        <table>
                            <tr>
                                <td>Ship To:-</td>
                            </tr>
                            <tr>
                                <td>{{$productDetails->name}}</td>
                            </tr>
                            <tr>
                                <td>{{$productDetails->address}}</td>
                            </tr>
                            <tr>
                                <td>{{$productDetails->city}}</td>
                            </tr>
                            <tr>
                                <td>{{$productDetails->state}}</td>
                            </tr>
                            <tr>
                                <td>{{$productDetails->country}}</td>
                            </tr>
                            <tr>
                                <td>{{$productDetails->pincode}}</td>
                            </tr>
                            <tr>
                                <td>{{$productDetails->mobile}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>For any enquiries, you can contact us at
            <a href="mailto:info@ecom-website.com">info@ecom-website.com</a></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Regards,<br>Team E-com</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
