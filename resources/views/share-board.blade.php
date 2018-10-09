<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <meta name ="csrf-token" content="{{csrf_token()}}">
    <!-- Title Page-->
    <title>Share Drawing</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">
    <!-- Bootstrap CSS-->
    <link href="{{asset('vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">
    <!-- Main CSS-->
    <link href="{{asset('css/theme.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('css/style.css')}}" rel="stylesheet" media="all">
    <style>
        .dropezone{
            width: 40%;
            padding: 50px;
            border: 2px dashed #ccc;
            background: #f9f9f9;
            text-align: center;
            display: inline-block;
            cursor: pointer;
        }
    </style>
    @yield('head')
</head>

<body class="animsition">
<div class="page-wrapper">
        <!-- PAGE CONTAINER-->
    <div class="page-container" style="padding-left:0;">
        <header class="header-desktop" style="left:0;">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="header-wrap">
                        <form class="form-header" action="" method="POST">
                            <label for="">Send a Document to @</label>
                        </form>
                        <div class="header-button">
                            <div class="noti-wrap">
                            </div>
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="image">
                                        <img src="{{url('storage/students-profiles-'.$user->profile_pic)}}" alt="{{$user->name}}" />
                                    </div>
                                    <div class="content">
                                        <a class="js-acc-btn" href="#">{{$user->name}}</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown">
                                        <div class="info clearfix">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="{{url('storage/students-profiles-'.$user->profile_pic)}}" alt="{{$user->name}}" />
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h5 class="name">
                                                    <a href="#">{{$user->name}}</a>
                                                </h5>
                                                <span class="email">{{$user->email}}</span>
                                            </div>
                                        </div>
                                        <div class="account-dropdown__body">
                                            <div class="account-dropdown__item">
                                                <a href="#">
                                                    <i class="zmdi zmdi-account"></i>Account</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="#">
                                                    <i class="zmdi zmdi-settings"></i>Setting</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="#">
                                                    <i class="zmdi zmdi-money-box"></i>Billing</a>
                                            </div>
                                        </div>
                                        <div class="account-dropdown__footer">
                                            <a href="{{url('tutor/logout')}}">
                                                <i class="zmdi zmdi-power"></i>Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="main-content">
            <div class="section__content ">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            Hi <span class="text-primary">{{$user->name}}</span>,  To share your document drop file or upload
                        </div>
                        <div class="card-body text-center">
                            <div class="dropezone" id="dragzone">
                                <div class="svg">
                                    <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                                </div>
                                <p>
                                    <b>Choose a file</b> or drop here
                                </p>

                            </div>
                            <input type="file" name="sharedrawing" style="display: none;" id="share-file" multiple="true" data-url="{{url("utility/share-files/{$type}?user={$sharedUser->id}&userType={$sharedUserType}")}}">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Document Shared by you
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>To</th>
                                        <th>Time Uploaded</th>
                                    </tr>
                                </thead>
                                <tbody id="shared-doc">
                                    @foreach($documentShared as $doc)
                                        <tr>
                                            <td><a href="{{url("storage/shared_docs-{$doc->image}")}}">{{$doc->image}}</a></td>
                                            <td>{{$doc->shared_to}}</td>
                                            <td>{{$doc->created_at}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Document Received by you
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>From</th>
                                    <th>Time Uploaded</th>
                                </tr>
                                </thead>
                                <tbody >
                                @foreach($receivedDocs as $doc)
                                    <tr>
                                        <td><a href="{{url("storage/shared_docs-{$doc->image}")}}">{{$doc->image}}</a></td>
                                        <td>{{$doc->shared_by}}</td>
                                        <td>{{$doc->created_at}}</td>
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
</div>
<!-- Jquery JS-->
<script src="{{asset('vendor/jquery-3.2.1.min.js')}}"></script>
<!-- Bootstrap JS-->
<script src="{{asset('vendor/bootstrap-4.1/popper.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
<script>
    var base_url = '{{url('')}}';
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function(){
        $('#dragzone').on(
            'dragover',
            function(e) {
                e.preventDefault();
                e.stopPropagation();
            }
        )
        $('#dragzone').on(
            'dragenter',
            function(e) {
                e.preventDefault();
                e.stopPropagation();
            }
        )
        $('#dragzone').on(
            'drop',
            function(e){
                if(e.originalEvent.dataTransfer){
                    if(e.originalEvent.dataTransfer.files.length) {
                        e.preventDefault();
                        e.stopPropagation();
                        /*UPLOAD FILES HERE*/
                        upload(e.originalEvent.dataTransfer.files);
                    }
                }
            }
        );
        $('#dragzone').click(function(){
            $('#share-file').click();
        });

        $('#share-file').change(function(){
            var files = this.files;
            console.log(this.files);
            upload(files)
        });

        function upload(files){
           var data= new FormData();
           for(var i in files) {
               data.append('files['+i+']', files[i]);
           }
           var url =  $('#share-file').data().url;
           $.ajax({
               url : url,
               type : 'post',
               data: data,
               processData : false,
               contentType  :false,
               success : function(response){
                  var html = '';
                  var docs = response.docs;
                  var sharedUser = response.sharedUser;
                  for(let i in docs){
                      var img = response[i];
                      html +='<tr>\n' +
                          '                                            <td><a href="'+base_url+'/storage/shared_docs-'+img.image+'">'+img.image+'</a></td>\n' +
                          '                                            <td>'+sharedUser.name+'</td>\n' +
                          '                                            <td>'+img.created_at+'</td>\n' +
                          '                                        </tr>'
                   }
                   $('#shared-doc').html(html);
               }
           });
        }
    })
</script>


@yield('javascript')
</body>

</html>
<!-- end document-->
