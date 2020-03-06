@extends('layouts.adminLayout.admin_design')
@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Shipping</a>
            <h1>Shipping Charges</h1>
        </div>
        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                            <h5>Edit Shipping Charges</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="{{url('/admin/edit-shipping/'.$shippingDetails->id)}}" name="edit_shipping" id="edit_shipping" novalidate="novalidate">{{csrf_field()}}
                               <input type="hidden" name="id" value="{{ $shippingDetails->id }}">
                                <div class="control-group">
                                    <label class="control-label">Country</label>
                                    <div class="controls">
                                        <input type="text" name="country" id="country" value="{{ $shippingDetails->country }}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Shipping Charges</label>
                                    <div class="controls">
                                        <textarea name="shipping_charges" id="shipping_charges">{{ $shippingDetails->shipping_charges }}</textarea>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" value="Edit Shipping" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
