@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                    Home</a> <a href="#">Newsletter Subscribers</a> <a href="#" class="current">View Subscribers</a></div>
            <h1>Newsletter Subscribers</h1>
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
        <div style="margin-left: 20px;">
            <a href="{{ url('/admin/export-newsletter-emails') }}" class="btn btn-primary btn-mini">Export</a>
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-th"></i></span>
                            <h5>View Subscribers</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>Subscriber ID</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created on</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($newsletters as $newsletter)
                                    <tr class="gradeX">
                                        <td class="center">{{ $newsletter->id }}</td>
                                        <td class="center">{{ $newsletter->email }}</td>
                                        <td class="center">
                                            @if($newsletter->status==1)
                                               <a href="{{ url('admin/update-newsletter-status/'.$newsletter->id.'/0') }}"><span style="color: #76bf0a">Active</span></a>
                                                @else
                                                <a href="{{ url('admin/update-newsletter-status/'.$newsletter->id).'/1' }}"> <span style="color: red">Inactive</span></a>
                                            @endif
                                        </td>
                                        <td class="center">{{ $newsletter->created_at }}</td>
                                        <td class="center"><a rel="{{ $newsletter->id }}" rel1="delete-newsletter-subscriber"
                                                              href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a></td>
                                    </tr>
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
