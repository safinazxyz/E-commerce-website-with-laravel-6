@extends('layouts.adminLayout.admin_design')
@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                    Home</a> <a href="#">Admins/Sub-Admins</a> <a href="#" class="current">Add Admin/Sub-Admin</a></div>
            <h1>Admin/Sub-Admin</h1>
        </div>
        <div class="container-fluid">
            <hr>
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
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"> <i class="icon-info-sign"></i> </span>
                            <h5>Add Admin/Sub-Admin</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="{{url('/admin/add-admin')}}"
                                  name="add_admin" id="add_admin" novalidate="novalidate">{{csrf_field()}}
                                <div class="control-group">
                                    <label class="control-label">Type</label>
                                    <div class="controls">
                                       <select name="type" id="type" style="width: 220px;" >
                                           <option>Select Type</option>
                                           <option value="Admin">Admin</option>
                                           <option value="Sub Admin">Sub Admin</option>
                                       </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Username</label>
                                    <div class="controls">
                                        <input type="text" name="username" id="username">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Password</label>
                                    <div class="controls">
                                        <input type="password" name="password" id="password">
                                    </div>
                                </div>
                                <div class="control-group" id="access">
                                    <label class="control-label">Access</label>
                                    <div class="controls">
                                        <input type="checkbox" name="categories_view_access" id="categories_view_access" value="1" >&nbsp;View only Categories<br>
                                        <input type="checkbox" name="categories_edit_access" id="categories_edit_access" value="1" >&nbsp;View & Edit Categories<br>
                                        <input type="checkbox" name="categories_full_access" id="categories_full_access" value="1" >&nbsp;Full Categories<br>
                                        <input type="checkbox" name="products_access" id="products_access" value="1" >&nbsp;Products<br>
                                        <input type="checkbox" name="orders_access" id="orders_access" value="1" >&nbsp;Orders<br>
                                        <input type="checkbox" name="users_access" id="users_access" value="1">&nbsp;Users<br>
                                    </div>
                                </div>
                            <div class="control-group">
                                <label class="control-label">Enable</label>
                                <div class="controls">
                                    <input type="checkbox" name="status" id="status" value="1">
                                </div>
                            </div>
                                <div class="form-actions">
                                    <input type="submit" value="Add Admin" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
