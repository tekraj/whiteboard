@extends('admin.master-layout')

@section('content')
    <div class="container-fluid">
        @if(session()->has('success'))
            <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                {{session('success')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <ul class="nav nav-pills" id="ow-donut">
                            <li><a href="{{ route('admins.create') }}">Add New</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        {!! Form::open(['url' => 'admin/admins/search/', 'method' => 'get', 'class' => 'form-horizontal','id' => 'merchants-search-form']) !!}
                        <div class="input-group">
                            {!! Form::text('search_data', isset($search_data) ? $search_data : null, array('class' => 'form-control', 'placeholder' => 'Search Filters...')) !!}
                            <span class="input-group-btn">{!! Form::submit('Go', array('class' => 'btn append-btn btn-primary')) !!}</span>
                        </div><!-- /input-group -->
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{$admin->name}}</td>
                            <td>{{$admin->email}}</td>
                            <td>{{ucfirst($admin->role)}}</td>
                            <td>
                                @if($admin->status===1)
                                    <label class="label text-success">Active</label>
                                @else
                                    <label class="label text-danger">In-Active</label>
                                @endif
                            </td>
                            <td>
                                <a href="{{route("admins.edit",$admin->id)}}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-edit large"></i>
                                </a>
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{route("admins.destroy",$admin->id)}}" method="post" style="display:inline-block">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Do you want to delete this admin')"> <i class="fa fa-trash large"></i></button>
                                        {!! csrf_field() !!}
                                    </form>
                                @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="pull-right link">{!! $admins->render() !!}</div>
            </div>
        </div>





    </div>

@endsection