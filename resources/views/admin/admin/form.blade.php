@extends('admin.master-layout')
@section('head')
@endsection
@section('content')
    <div class="container-fluid">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::open(['url' => ($admin->id>0 ? route('admins.update',$admin->id) : route('admins.store')), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'filter-form', 'files' => false]) !!}
        @if($admin->id>0)
            <input type="hidden" name="_method" value="PATCH">
        @endif
        <div class="card">
            <div class="card-header">

                <div class="row">
                    <div class="col-sm-6">
                        {{$admin->id > 0 ? "Edit {$admin->name}" : "Add new Admin"}}
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{url('admin/admins')}}" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('name', 'Name*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('name',$admin->name, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('email', 'Email*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::email('email',$admin->email, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ Form::label('status', 'Status', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-9">
                                {{ Form::select('status',[''=>'Select','1'=>'Active','0'=>'In-active'],$admin->status, ['class' => 'form-control','autocomplete'=>str_random(7)]) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ Form::label('password', ($admin->id>0 ?'Password': 'Password*'), ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-9">
                                {{ Form::password('password', ['class' => 'form-control',($admin->id>0 ?'required': '')=>true,'autocomplete'=>str_random(7)]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">

                    <label class="col-sm-2 text-center form-control-label">Role</label>
                    <div class="col-sm-10">
                        <div class="form-check-inline form-check">
                            @foreach($roles as $role)
                                <label for="role-{{$role->name}}" class="form-check-label " style="margin-right:20px;">
                                    <input type="radio" id="role-{{$role->name}}" name="role" value="{{$role->id}}"
                                           class="form-check-input" {{$admin->roles->contains('id',$role->id)?'checked' : ''}}>{{$role->display_name}}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-dot-circle-o"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop
@section('javascript')
@endsection