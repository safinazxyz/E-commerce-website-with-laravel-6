@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                    Home</a> <a href="#">Enquiries</a> <a href="#" class="current">View Enquiries</a></div>
            <h1>Enquiries</h1>
        </div>
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
                            <h5>View Enquiries</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <div id="app">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Created on</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="enquiry in enquiries" type="1">
                                        <td>@{{ enquiry.name }}</td>
                                        <td>@{{ enquiry.email }}</td>
                                        <td>@{{ enquiry.subject }}</td>
                                        <td style="maxlength= 100;">@{{ enquiry.message }}</td>
                                        <td>@{{ enquiry.created_at }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
