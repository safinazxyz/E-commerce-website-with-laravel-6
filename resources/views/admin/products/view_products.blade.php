@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                    Home</a> <a href="#">Products</a> <a href="#" class="current">View Product</a></div>
            <h1>Products</h1>
        </div>
        <div style="padding-left: 20px;"><a href="{{ url('/admin/add-product/') }}" class="btn btn-primary btn-mini">Add
                Product</a></div>
        @if(Session::has('flash_message_error'))
            <div class="alert alert-error alert-block">
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
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-th"></i></span>
                            <h5>View Products</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Category ID</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Product Color</th>
                                    <th>Product Description</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Feature Item</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $pro)
                                    <tr class="gradeX">
                                        <td>{{ $pro->id }}</td>
                                        <td>{{ $pro->category_id }}</td>
                                        <td>{{ $pro->category_name }}</td>
                                        <td>{{ $pro->product_name }}</td>
                                        <td>{{ $pro->product_code }}</td>
                                        <td>{{ $pro->product_color}}</td>
                                        <td>{{ $pro->description }}</td>
                                        <td>{{ $pro->price }}</td>
                                        <td>
                                            @if(!empty($pro->image))
                                                <img
                                                    src="{{ asset('/images/backend_images/products/small/'.$pro->image) }} "
                                                    style="width: 50px;">
                                            @endif
                                        </td>
                                        <td><span style="color: green">@if($pro->feature_item==1)Yes</span>@else<span style="color: red"> NO </span>@endif</td>
                                        <td class="center">
                                            <a href="{{ url('/admin/edit-product/'.$pro->id) }}"
                                               class="btn btn-primary btn-mini" title="Edit Product">Edit</a>
                                            <a href="#myModal{{ $pro->id }}" data-toggle="modal"
                                               class="btn btn-success btn-mini" title="View Product">View</a>
                                            <a href="{{ url('/admin/add-attributes/'.$pro->id) }}" class="btn btn-success btn-mini" title="Add Attributes">Add</a>
                                            <a href="{{ url('/admin/add-images/'.$pro->id) }}" class="btn btn-success btn-mini" title="Add Images">Add</a>
                                            <a rel="{{ $pro->id }}" rel1="delete-product" <?php /* href="{{ url('/admin/delete-product/'.$pro->id) }}" */?>
                                                href="javascript:" class="btn btn-danger btn-mini deleteRecord" title="Delete Product">Delete</a>
                                        </td>
                                    </tr>
                                    <div id="myModal{{ $pro->id }}" class="modal hide" aria-hidden="true"
                                         style="display: none;">
                                        <div class="modal-header">
                                            <button data-dismiss="modal" class="close" type="button">×</button>
                                            <h3>{{ $pro->product_name }} Full Details</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p>Product ID: {{ $pro->id }}</p>
                                            <p>Category ID: {{ $pro->category_id }}</p>
                                            <p>Product Code: {{ $pro->product_code }}</p>
                                            <p>Product Color: {{ $pro->product_color }}</p>
                                            <p>Price: {{ $pro->price }}</p>
                                            <p>Description: {{ $pro->description }}</p>
                                        </div>
                                    </div>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
