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
                        Click button for File Manager
                    </div>
                    <div class="col-xs-12 col-sm-4">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="input-group">
                   <span class="input-group-btn">
                     <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary btn-lg btn-block" style="color:#fff;width:100%;">
                       <i class="fa fa-picture-o"></i> Open File Manager
                     </a>
                   </span>
                    <input id="thumbnail" class="form-control" type="text" name="filepath" style="display:none">
                </div>
                <img id="holder" style="margin-top:15px;max-height:100px;">
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script>
        $(document).ready(function () {
            $('#lfm').filemanager('file');
        });
    </script>
@endsection