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
                            <li><a href="{{ route('tutors.create') }}"><b>Add New Tutor</b></a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        {!! Form::open(['url' => 'admin/tutors/search/', 'method' => 'get', 'class' => 'form-horizontal','id' => 'merchants-search-form']) !!}
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
                        <th>Subject</th>
                        <th>Contact No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tutors as $tutor)
                        <tr>
                            <td>{{$tutor->name}}</td>
                            <td>{{$tutor->email}}</td>
                            <td>{{$tutor->subject ? ucfirst($tutor->subject->name):''}}</td>
                            <td>{{$tutor->contact_no}}</td>
                            <td>
                                @if($tutor->status)
                                    <label class="label text-success">Active</label>
                                @else
                                    <label class="label text-danger">In-Active</label>
                                @endif
                            </td>
                            <td>
                                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                    <a href="{{route("tutors.edit",$tutor->id)}}"
                                       class="btn btn-outline-primary btn-sm"  data-toggle="tooltip" data-placement="top" title="Edit Tutor">
                                        <i class="fa fa-edit large"></i>
                                    </a>
                                @endif
                                <a href="{{route("tutors.show",$tutor->id)}}" class="btn btn-outline-success btn-sm"  data-toggle="tooltip" data-placement="top" title="View Detail">
                                    <i class="fa fa-eye large"></i>
                                </a>
                                <a href="{{url("admin/tutors/payments/{$tutor->id}")}}"
                                   class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Payment Detail">
                                    <i class="fa fa-credit-card large"></i>
                                </a>
                                <a href="{{url("admin/tutors/sessions/{$tutor->id}")}}"
                                   class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Session Detail">
                                    <i class="fa fa-clock-o large"></i>
                                </a>
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{route("tutors.destroy",$tutor->id)}}" method="post"
                                          style="display:inline-block">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Do you want to delete this tutor')" data-toggle="tooltip" data-placement="top" title="Delete This Tutor"><i
                                                    class="fa fa-trash large"></i></button>
                                        {!! csrf_field() !!}
                                    </form>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="pull-right link">{!! $tutors->render() !!}</div>
            </div>
        </div>


    </div>

@endsection