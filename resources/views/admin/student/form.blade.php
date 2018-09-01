@extends('admin.master-layout')
@section('head')
    <link rel="stylesheet" href="{{asset('vendor/jquery-ui/jquery-ui.css')}}">
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
        {!! Form::open(['url' => ($student->id>0 ? route('students.update',$student->id) : route('students.store')), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'filter-form', 'files' => true,'autocomplete'=>str_random(7)]) !!}
        @if($student->id>0)
            <input type="hidden" name="_method" value="PATCH">
        @endif
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        {{$student->id > 0 ? "Edit {$student->name}" : "Add new Tutor"}}
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{url('admin/students')}}" class="btn btn-default">Back</a>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('name', 'Name*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('name',$student->name, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('email', 'Email*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::email('email',$student->email, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ Form::label('class_id', 'Class*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-9">
                                {{ Form::select('class_id',([''=>'Selet Class']+$classes),$student->class_id, ['class' => 'form-control','autocomplete'=>str_random(7)]) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ Form::label('password',  'Password*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="password" value="{{$student->password_plain}}" name="password" autocompleted="{{str_random(7)}}" required="true" class="form-control">
                                    <label  for="edit-schedule-start-date" class="input-group-addon js-show-passwaord">
                                        <span class="fa fa-eye"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_no', 'Contact No*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('contact_no',$student->contact_no, ['class' => 'form-control','required'=>true,'pattern'=>'^\d{10}$','autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('address', 'Address*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('address',$student->address, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('school_name', 'School*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('school_name',$student->school_name, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('gender', 'Gender*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) !!}
                            <div class="col-sm-9">
                                {!! Form::select('gender',[''=>'Select Gender','male'=>'Male','female'=>'female','others'=>'Others'],$student->gender, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Select Subjects</label>
                    <div class="col-sm-8">
                        @foreach($subjects as $key=>$subject)
                            <label>
                                <input type="checkbox" name="subjects[{{$key}}]" value="{{$subject->id}}" {{$student->subjects->contains('id',$subject->id)?'checked':''}}> {{$subject->name}}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ Form::label('dob', 'Date Of Birth*', ['class' => 'col-sm-4 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-8">
                                {{ Form::text('dob',$student->dob, ['class' => 'form-control js-date-picker','required'=>true]) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ Form::label('status', 'Status*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-9">
                                {{ Form::select('status',[''=>'Select','1'=>'Active','0'=>'In-active'],$student->status, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]) }}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-7">

                        <div class="form-group row">
                            {{ Form::label('profile_pics', 'Profile Picture', ['class' => 'col-sm-4 text-right','style'=>'margin-top:8px']) }}
                            <div class="col-sm-8">
                                <div class="card js-profile-pic-card {{ ($student->id>0 && !empty($student->profile_pic)) ? '': 'hidden'}}">
                                    <div class="card-body">
                                        <div class="mx-auto d-block">
                                            <img class="rounded-circle mx-auto d-block js-profile-pic-img" src="{{url("storage/students-profiles-{$student->profile_pic}")}}" alt="Card image cap">
                                        </div>
                                    </div>
                                </div>
                                {{ Form::file('profile_pic', ['class' => 'js-profile-pic hidden']) }}
                                <a href="#" class="btn btn-primary btn-sm js-sub-file-btn"> Select Profile Pic</a>
                            </div>
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
    <script src="{{asset('vendor/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.js-sub-file-btn').click(function(e){
                e.preventDefault();
                $(this).siblings('.js-profile-pic').click();
            });
            $('.js-profile-pic').change( function () {
                var file = this.files[0];
                var $parent = $(this).parent();
                if (!file.name.match(/\.(jpg|jpeg|png|gif)$/)) {
                    alert('Invalid Image');
                    return false;
                }
                $parent.find('.js-profile-pic-card').removeClass('hidden');
                var reader = new FileReader();
                reader.onload = function (e) {
                    $parent.find('.js-profile-pic-img').attr('src', e.target.result);

                };
                reader.readAsDataURL(file);
            });
            $('.ui-datepicker-next,.ui-datepicker-prev').click(function(e){
                e.preventDefault();
            })
            $('.js-date-picker').datepicker({ maxDate: new Date, minDate: new Date(1970, 6, 12),dateFormat: 'yy-mm-dd',});
        });
    </script>
@endsection