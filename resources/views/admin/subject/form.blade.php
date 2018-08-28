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
        {!! Form::open(['url' => ($subject->id>0 ? route('subjects.update',$subject->id) : route('subjects.store')), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'filter-form', 'files' => false]) !!}
        @if($subject->id>0)
            <input type="hidden" name="_method" value="PATCH">
        @endif
        <div class="card">
            <div class="card-header">

                <div class="row">
                    <div class="col-sm-6">
                        {{$subject->id > 0 ? "Edit {$subject->name}" : "Add new Subject"}}
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{url('admin/subjects')}}" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('name', 'Name*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('name',$subject->name, ['class' => 'form-control','required'=>true]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ Form::label('status', 'Status*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-9">
                                {{ Form::select('status',[''=>'Select','1'=>'Active','0'=>'In-active'],$subject->status, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group row">
                    {{ Form::label('description', 'Description', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) }}
                    <div class="col-sm-9">
                        {{ Form::textarea('description',$subject->description, ['class' => 'form-control']) }}
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
    <script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description');
    </script>
@stop