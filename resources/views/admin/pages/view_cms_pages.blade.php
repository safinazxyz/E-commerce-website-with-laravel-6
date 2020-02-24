@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                    Home</a> <a href="#">Cms Pages</a> <a href="#" class="current">View Cms Page</a></div>
            <h1>Cms Pages</h1>
        </div>
        <div style="padding-left: 20px;"><a href="{{ url('/admin/add-cms-page/') }}" class="btn btn-primary btn-mini">Add
                Cms Page</a></div>
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
                            <h5>View Cms Pages</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>Page ID</th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Created on</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cmsPages as $cms)
                                    <tr class="gradeX">
                                        <td>{{ $cms->id }}</td>
                                        <td>{{ $cms->title }}</td>
                                        <td>{{ $cms->url }}</td>
                                        <td>{{ $cms->description }}</td>
                                        <td><span style="color: green">@if($cms->status==1)Active</span>@else<span
                                                style="color: red"> Inactive </span>@endif</td>
                                        <td>{{ $cms->created_at }}</td>
                                        <td class="center">
                                            <a href="#myModal{{ $cms->id }}" data-toggle="modal"
                                               class="btn btn-success btn-mini" title="View Cms Page">View</a>
                                            <a href="{{ url('/admin/edit-cms-page/'.$cms->id) }}"
                                               class="btn btn-primary btn-mini" title="Edit Cms Page">Edit</a>

                                            <a rel="{{ $cms->id }}" rel1="delete-cms-page"
                                               <?php /* href="{{ url('/admin/delete-product/'.$pro->id) }}" */?>
                                               href="javascript:" class="btn btn-danger btn-mini deleteRecord"
                                               title="Delete Cms Page">Delete</a>
                                        </td>
                                    </tr>
                                    <div id="myModal{{ $cms->id }}" class="modal hide" aria-hidden="true"
                                         style="display: none;">
                                        <div class="modal-header">
                                            <button data-dismiss="modal" class="close" type="button">×</button>
                                            <h3>{{ $cms->product_name }} Full Details</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Title: </strong>{{ $cms->title }}</p>
                                            <p><strong>URL: </strong>{{ $cms->url }}</p>
                                            <p><strong>Status: </strong>{{ $cms->status }}</p>
                                            <p><strong>Created On: </strong>{{ $cms->created_at }}</p>
                                            <p><strong>Description: </strong>{{ $cms->description }}</p>
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
