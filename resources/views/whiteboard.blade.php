<!Doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <!--<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">-->
    <link rel="stylesheet" href="{{asset('painting-app/plugins/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('painting-app/font-awesome/css/fontawesome.css')}}">


    <link rel="stylesheet" href="{{asset('painting-app/js-tree/themes/default/style.min.css')}}">
    <link href="{{asset('painting-app/plugins/node-waves/waves.css')}}" rel="stylesheet"/>
    <!-- Animation Css -->
    <link href="{{asset('painting-app/plugins/animate-css/animate.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('painting-app/plugins/jquery-ui/jquery-ui.min.css')}}">
    <link href="{{asset('painting-app/css/board-css.min.css')}}" rel="stylesheet">
    <link href="{{asset('painting-app/css/themes/all-themes.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('painting-app/plugins/spectrum/spectrum.css')}}">
    <link href="{{asset('painting-app/equation-editor/mathquill.css')}}" rel="stylesheet">
    <link href="{{asset('painting-app/equation-editor/matheditor.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('painting-app/izitoast/css/iziToast.min.css')}}">
    <link rel="stylesheet" href="{{asset('painting-app/ripple-css/ripple.min.css')}}">
    <link rel="stylesheet" href="{{asset('painting-app/css/bootstrap-dropdownhover.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('painting-app/css/style.css')}}">
    <title>White Board</title>

</head>
<body data-imageurl="{{url("utility/save-canvas-image/{$type}")}}">
<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid" style="padding:0;">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav top-navbar">
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           href="#">File</a>
                        <ul class="dropdown-menu">
                            <li>
                                <input type="file" class="hidden js-load-whiteboard"
                                       data-url="{{url("utility/read-sav-file/{$type}")}}">
                                <a href="#" class="js-load-whiteboard-click">Load WhiteBoard</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#" class="js-save-whiteboard"
                                   data-url="{{url("utility/save-canvas-to-sav/{$type}")}}">Save WhiteBoard As</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            {{--<li>--}}
                            {{--<a href="#" >Properties</a>--}}

                            {{--</li>--}}
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="{{url($type)}}">End Session</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#print-modal">
                                    Print
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="{{url($type)}}">Exit</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                           data-hover="dropdown">Edit</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="js-refresh-participant-list">Refresh Participant List</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" data-hover="dropdown">Sessions</a>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="js-refresh-session">Refresh</a></li>
                            <li><a href="#" class="js-wrap-up-session">Wrap Up</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                           data-hover="dropdown">Tools</a>
                        <ul class="dropdown-menu">
                            <li><a href="#tech-support-modal" data-toggle="modal">Send TechSupport Report</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                   data-hover="dropdown">Contact</a>
                                <ul class="dropdown-menu sub-dropdown-menu">
                                    <li><a href="#" class="js-contact-session-monitor">Session Monitor</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link" href="#session-log-modal" data-toggle="modal">Session Log</a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">Favorites</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right prev-next-navbar">
                    <li>
                        <a class="btn btn-primary btn-sm" id="new-board" data-toggle="tooltip" data-placement="bottom"
                           title="Clear this board and draw on new board">New Board</a>

                    </li>
                    <li>
                        <button class="btn btn-default btn-sm btn-back " id="canvas-back-state" data-toggle="tooltip"
                                data-placement="bottom" title="Back to previous board"><img
                                    src="{{asset('painting-app/images/icons/ic_keyboard_arrow_left_black_24dp_1x.png')}}">
                            <span>Back</span></button>
                    </li>
                    <li>
                        <button class="btn btn-default btn-sm btn-next" id="canvas-next-state" data-toggle="tooltip"
                                data-placement="bottom" title="Next Board"><span>Next</span> <img
                                    src="{{asset('painting-app/images/icons/ic_keyboard_arrow_right_black_24dp_1x.png')}}">
                        </button>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>

<div class="container-fluid top-functions">
    <div class="row">
        <div class="col-sm-6 top-nav-tools">
            <a href="#" class="btn btn-primary btn-square" id="mouse-cursor">
                <img src="{{asset('painting-app/images/computer-mouse-cursor.png')}}">
            </a>
            <button class="btn btn-primary btn-square js-tools" data-tool="drag" data-toggle="tooltip"
                    data-placement="top" title="Drag Shapes"
                    data-cursor="url({{asset('painting-app/images/drag.png')}}), auto"
                    style="background:url({{asset('painting-app/images/drag.png')}}) no-repeat center;width:35px;height:26px;position:relative;">
                &nbsp;
            </button>
            <a href="#" class="btn btn-primary btn-square active" id="enable-drawing" data-toggle="tooltip"
               data-placement="bottom" title="Switch to Whiteboard">
                <span class="font">Wb</span>
            </a>
            <a href="#" class="btn btn-primary btn-square" id="reader-mode-indicator" data-toggle="tooltip"
               data-placement="bottom" title="Switch To Webshare">
                <img src="{{asset('painting-app/images/internet.png')}}">
            </a>
            <!--<a href="#" class="btn btn-primary btn-square">-->
            <!--<span class=""><i class="fa fa-pie-chart" aria-hidden="true"></i></span>-->
            <!--</a>-->
            <a href="#" class="btn btn-primary btn-square" id="browse-cloud"
               data-url="{{url("utility/read-cloud-file/{$type}")}}" data-toggle="tooltip" data-placement="bottom"
               title="WhiteBoard Cloud">
                <img src="{{asset('painting-app/images/cloud.png')}}" style="width:20px;" alt="">
            </a>
            <a href="#session-note-modal" class="btn btn-primary btn-square" title="Session Note" data-toggle="modal">
                <span class=""><img src="{{asset('painting-app/images/note.png')}}"></span>
            </a>
            <a href="#notification-modal" class="btn btn-primary btn-square"  data-toggle="modal" data-placement="bottom"
               title="Notifications">
                <img src="{{asset('painting-app/images/email.png')}}">
            </a>
            <a href="#" class="btn btn-primary btn-square" data-toggle="modal" data-target="#print-modal"
               title="Print This drawing">
                <img src="{{asset('painting-app/images/printer-.png')}}">
            </a>
            <a href="{{url('utility/share-drawing/'.$type)}}"  class="btn btn-primary btn-square" id="share-drawing"  data-toggle="tooltip" data-placement="bottom"
               title="Share drawing">
                <img src="{{asset('painting-app/images/share-connection-sing.png')}}">
            </a>

            <a target="_blank" href="http://www.brainfuse.com/jsp/user/emailTranscriptQC.jsp?e=26e1afc948a1ad6d&u=ee1be66ef9286547" class="btn btn-primary btn-square" data-toggle="tooltip" data-placement="bottom"
               title="Send to mail">
                <img src="{{asset('painting-app/images/black-back-closed-envelope-shape.png')}}">
            </a>
            <a href="#" class="btn btn-primary btn-square" data-toggle="tooltip" data-placement="bottom"
               title="Slide View Mode">
                <img src="{{asset('painting-app/images/monitor.png')}}">
            </a>
            <span style="font-size:12px;"><b>Slide 1/1</b></span>
        </div>
        <div class="col-sm-6 text-right">
            <button class="btn btn-warning btn-public js-public-mode">Public</button>
        </div>
    </div>
</div>

<div class="container-fluid can" style="padding-left:0;padding-right:0;">
    <div class="row">
        <div class="col-md-1 tools-list">
            <div class="text-center my-1 option-menu-wrapper">
                <a href="#" class="btn btn-default js-tools" id="pencil-tool" data-tool="pencil" data-toggle="tooltip"
                   data-placement="top" title="Pencil"
                   data-cursor="url({{asset('painting-app/images/pencil.png')}}), auto">
                    <img src="{{asset('painting-app/images/pencil-w.png')}}">
                </a>
                <ul class="option-menu" style="display: none;">
                    <li>
                        <a href="#" class="line-width js-line-width active" data-line="1">
                            <span style="border-width: 1px;"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="line-width js-line-width" data-line="2">
                            <span style="border-width: 4px;"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="line-width js-line-width" data-line="3">
                            <span style="border-width: 6px;"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="line-width js-line-width" data-line="4">
                            <span style="border-width: 8px;"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="text-center my-1 option-menu-wrapper">
                <a href="#" class="btn btn-default js-tools" data-tool="eraser" data-toggle="tooltip"
                   data-placement="top" title="Eraser" data-cursor="crosshair">
                    <img src="{{asset('painting-app/images/eraser-w.png')}}">
                </a>
                <div class="option-menu eraser-slider" style="display: none;">
                    <div id="eraser-slider"></div>
                </div>

            </div>
            <div class="text-center my-1 ">
                <a href="#" class="btn btn-default js-tools" id="enable-text-tool" data-tool="text"
                   data-cursor="url({{asset('painting-app/images/text.png')}}), auto" data-toggle="tooltip"
                   data-placement="top" title="Text">
                    <img src="{{asset('painting-app/images/text-w.png')}}" style="width:42px">
                </a>
            </div>
            <div class="text-center my-1 font-menu-wrapper  option-menu-wrapper">
                <a href="#" class="btn btn-default btn-square mx-auto js-fonts js-tools">
                    <img src="{{asset('painting-app/images/1.png')}}" style="width:42px">
                </a>
                <div class="option-menu font-menu" style="display: none;">
                    <h4>Font Properties</h4>
                    <div class="row font-wrapper">
                        <div class="col-sm-5">
                            <h5>Font</h5>
                            <ul>
                                <li><a href="#" class="js-font active" data-font="serif">Times New Roman</a></li>
                                <li><a href="#" class="js-font " data-font="arial">Arial</a></li>
                                <li><a href="#" class="js-font " data-font="monospace">Courier New</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-5">
                            <h5>Font Style</h5>
                            <ul>


                                <li><a href="#" class="js-font-style active" data-style="normal">Normal</a></li>


                                <li><a href="#" class="js-font-style " data-style="bold">Bold</a></li>


                                <li><a href="#" class="js-font-style " data-style="italic">Italic</a></li>


                                <li><a href="#" class="js-font-style " data-style="bold italic">Bold italic</a></li>


                            </ul>
                        </div>
                        <div class="col-sm-2">
                            <h5>Size</h5>
                            <ul>
                                <li><a href="#" class="js-font-size " data-size="8">8</a></li>

                                <li><a href="#" class="js-font-size " data-size="9">9</a></li>

                                <li><a href="#" class="js-font-size " data-size="10">10</a></li>

                                <li><a href="#" class="js-font-size " data-size="11">11</a></li>

                                <li><a href="#" class="js-font-size " data-size="12">12</a></li>

                                <li><a href="#" class="js-font-size " data-size="13">13</a></li>

                                <li><a href="#" class="js-font-size " data-size="14">14</a></li>

                                <li><a href="#" class="js-font-size " data-size="16">16</a></li>

                                <li><a href="#" class="js-font-size active" data-size="18">18</a></li>

                                <li><a href="#" class="js-font-size " data-size="20">20</a></li>

                                <li><a href="#" class="js-font-size " data-size="24">24</a></li>

                                <li><a href="#" class="js-font-size " data-size="30">30</a></li>

                                <li><a href="#" class="js-font-size " data-size="36">36</a></li>

                                <li><a href="#" class="js-font-size " data-size="42">42</a></li>

                                <li><a href="#" class="js-font-size " data-size="48">48</a></li>


                            </ul>
                        </div>
                        <div class="col-sm-12">
                            <div class="demo-font-text js-text-demo">
                                -- Hello World --
                            </div>
                        </div>
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-default" id="change-font">OK</button>
                            <button class="btn btn-default" id="cancel-font" style="width:50px !important;">Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center my-1 option-menu-wrapper">
                <a href="#" style="background:#000;" class="btn js-tools" data-toggle="tooltip" data-placement="top"
                   title="Colors" id="color-indicator" data-color="#000">
                    <img src="{{asset('painting-app/images/color-w.png')}}">
                </a>
                <div class="option-menu color-menu" style="display: none;">
                    <ul class="color-pallet row">
                        <li class="col-sm-2"><a href="#" data-color="#e6194b" style="background:#e6194b"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#3cb44b" style="background:#3cb44b"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#ffe119" style="background:#ffe119"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#0082c8" style="background:#0082c8"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#f58231" style="background:#f58231"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#911eb4" style="background:#911eb4"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#46f0f0" style="background:#46f0f0"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#f032e6" style="background:#f032e6"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#d2f53c" style="background:#d2f53c"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#fabebe" style="background:#fabebe"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#008080" style="background:#008080"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#e6beff" style="background:#e6beff"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#aa6e28" style="background:#aa6e28"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#fffac8" style="background:#fffac8"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#800000" style="background:#800000"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#aaffc3" style="background:#aaffc3"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#808000" style="background:#808000"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#ffd8b1" style="background:#ffd8b1"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#000080" style="background:#000080"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#808080" style="background:#808080"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#FFFFFF" style="background:#FFFFFF"
                                                class="color-span js-color-code"></a></li>
                        <li class="col-sm-2"><a href="#" data-color="#000000" style="background:#000000"
                                                class="color-span js-color-code"></a></li>
                    </ul>
                    <div>
                        <a href="#" id="color-spectrum" class="color-spectrum">More Colors</a>
                    </div>
                </div>
            </div>
            <div class="text-center my-1">
                <a href="#" id="paste-tool" class="btn btn-default btn-square mx-auto" data-toggle="tooltip" data-placement="top"
                   title="Click on paste tool and then click on whiteboard">
                    <img src="{{asset('painting-app/images/paste.png')}}">
                </a>
            </div>
            <div class="text-center my-1">
                <a href="#" class="btn btn-default js-tools text-center" style="line-height: 30px;" data-tool="line"
                   data-cursor="url({{asset('painting-app/images/line-icon.png')}}), auto" data-toggle="tooltip"
                   data-placement="top" title="Draw Line">
                    <img src="{{asset('painting-app/images/line-icon.png')}}" style="width:20px;height:20px;">
                </a>
            </div>

            <div class="text-center my-1 option-menu-wrapper">
                <a href="#" style="background:#000;" class="btn btn-default js-tools" data-toggle="tooltip"
                   data-placement="top" title="Graphs" id="color-indicator">
                    <img src="{{asset('painting-app/images/graph-w.png')}}" alt="">
                </a>

                <ul class="option-menu graph-menu" style="display: none;">
                    <li>
                        <a href="" class="js-tools" data-tool="xgraph" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/graph-x.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="xygraph" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/graph-xy.png')}}" alt="">
                        </a>
                    </li>
                </ul>

            </div>
            <div class="text-center my-1 option-menu-wrapper">
                <a href="#" class="btn btn-default js-tools" data-toggle="tooltip" data-placement="top" title="Shapes">
                    <img src="{{asset('painting-app/images/shapes-w.png')}}">
                </a>
                <ul class="option-menu shape-menu" style="display: none">
                    <li>
                        <a href="" class="js-tools" data-tool="line-sarrow"
                           data-cursor="url({{asset('painting-app/images/line-icon.png')}}), auto">
                            <img src="{{asset('painting-app/images/line-single-arrow.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="line-darrow"
                           data-cursor="url({{asset('painting-app/images/line-icon.png')}}), auto">
                            <img src="{{asset('painting-app/images/line-double-arrow.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="rectangle"
                           data-cursor="url({{asset('painting-app/images/line-icon.png')}}), auto">
                            <img src="{{asset('painting-app/images/square.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="rectangle-filled"
                           data-cursor="url({{asset('painting-app/images/line-icon.png')}}), auto">
                            <img src="{{asset('painting-app/images/square-filled.png')}}" alt="">
                        </a>
                    </li>

                    <li>
                        <a href="" class="js-tools" data-tool="oval" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/circle.png')}}" alt="">
                        </a>
                    </li>

                    <li>
                        <a href="" class="js-tools" data-tool="oval-filled" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/circle-filled.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="cylinder" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/cylinder.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="cone" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/cone.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="cube" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/cube.png')}}" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="" class="js-tools" data-tool="pyramid" data-cursor="crosshair">
                            <img src="{{asset('painting-app/images/prism.png')}}" alt="">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="text-center my-1">
                <a href="#" class="btn btn-default js-tools" data-toggle="tooltip" data-placement="top" title="Undo"
                   id="undo-tool">
                    <img src="{{asset('painting-app/images/undo-w.png')}}" style="width: 42px;">
                </a>
            </div>
            <div class="text-center my-1">
                <a href="#" class="btn btn-default btn-square mx-auto js-show-equation-modal">
                    <span data-toggle="tooltip" data-placement="top" title="Click here to open equation editor"> <img
                                src="{{asset('painting-app/images/math-w.png')}}"></span>
                </a>
            </div>
            <div class="text-center my-1 science-menu-wrapper  js-enable-symbol">
                <a href="#" class="btn btn-default btn-square mx-auto">
                    <img src="{{asset('painting-app/images/science-w.png')}}" style="width: 42px;">
                </a>
                <ul class="option-menu symbol-dropdown" style="display:none;">
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol=":">:</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≤">≤</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≥">≥</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="◦">◦</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≈">≈</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∈">∈</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="×">×</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="±">±</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∧">∧</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∨">∨</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≡">≡</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≅">≅</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≠">≠</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∼">∼</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∝">∝</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≺">≺</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="⪯">⪯</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="⊂">⊂</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="⊆">⊆</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="≻">≻</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="⪰">⪰</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="⊥">⊥</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∣">∣</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∥">∥</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∂">∂</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="∞">∞</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Γ">Γ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Δ">Δ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Θ">Θ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Λ">Λ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Ξ">Ξ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Π">Π</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Σ">Σ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Υ">Υ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Φ">Φ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Ψ">Ψ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="Ω">Ω</
                        >
                    </li>

                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="α">α</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="β">β</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="γ">γ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="δ">δ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol=">">ε</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ϵ">ϵ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ζ">ζ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="η">η</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="θ">θ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ϑ">ϑ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ι">ι</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="κ">κ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="λ">λ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="μ">μ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ν">ν</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ξ">ξ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="π">π</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ϖ">ϖ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ρ">ρ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ϱ">ϱ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="σ">σ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ς">ς</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="τ">τ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="υ">υ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="φ">φ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ϕ">ϕ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="χ">χ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol=">">ψ</
                        >
                    </li>
                    <li>
                        <button class="btn btn-sm btn-default js-science-symbol" data-symbol="ω">ω</
                        >
                    </li>
                </ul>
            </div>

            <div class="text-center my-1">
                <a href="#" class="btn btn-default btn-square mx-auto" id="enable-subscript" data-toggle="tooltip"
                   data-placement="top" title="Click here to type subscript">
                    <img src="{{asset('painting-app/images/asub-w.png')}}" alt="">
                </a>

            </div>

            <div class="text-center my-1">
                <a href="#" class="btn btn-default btn-square mx-auto" id="enable-superscript" data-toggle="tooltip"
                   data-placement="top" title="Click here to type superscript">
                    <img src="{{asset('painting-app/images/a2-w.png')}}" alt="">
                </a>
            </div>

            <div class="text-center my-1">
                <a href="#" class="btn btn-default js-tools" id="clear-canvas" data-toggle="modal"
                   data-target="#save-modal">
                    <span class="" data-toggle="tooltip" data-placement="top" title="Click here to clear the board"><img
                                src="{{asset('painting-app/images/clear.png')}}"></span>
                </a>
            </div>

        </div>

        <div class="col-md-11 border px-0 canvas-list">

            <div class="canvas-writing writing">

                <div class="canvas-wrapper" style="height:60vh;">
                    <canvas class="drawing-board" id="drawing-board"></canvas>
                    <canvas id="fake-canvas" class="fake-canvas"></canvas>
                    <canvas id="resize-canvas" style="display: none;"></canvas>
                    <div>
                        <div id="text-holder" contenteditable="true" spellcheck="false">

                        </div>
                    </div>
                    <div id="pdf-reader" style="display: none;"></div>
                    <div id="drag-div"></div>
                </div>


            </div>

            <div class="card chat-card">
                <div class="header">
                    <div class="row ">
                        <div class="col-sm-4">
                            <a class="nav-link active" id="participants-tab" data-toggle="tab" href="#participants"
                               role="tab" style="background-color:lightgrey; border:2px solid grey; "
                               aria-controls="home" aria-selected="true"><img
                                        src="{{asset('painting-app/images/multiple-users-silhouette.png')}}"> <b
                                        style="color:black;">Participants</b></a>

                        </div>
                        <div class="col-sm-8">
                            <a class="nav-link active" id="participant-tab" data-toggle="tab" href="#chat" role="tab"
                               style="border:2px solidgrey;" aria-controls="home" aria-selected="true"><img
                                        src="{{asset('painting-app/images/comments.png')}}"><b style="color:black;">
                                    Chat</b></a>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="participant-list" style="height:23vh;">
                                <ul id="online-users">

                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-9">

                            <div class="chat-room" style="height: 18vh;">
                                <ul id="chat-board">

                                </ul>
                            </div>
                            <form id="chat-input-area" class="chat-input">
                                <div class="input-group">
                                    <div contentEditable="true" class="form-control" name="chat-input"
                                         id="chat-input"></div>
                                    <ul class="input-group-addon list-inline">
                                        <li class="dropdown">
                                            <a herf="#" class=" dropdown-toggle" data-toggle="dropdown" role="button"
                                               aria-expanded="false"><img
                                                        src="{{asset('painting-app/images/icons/ic_tag_faces_black_24dp_1x.png')}}"
                                                        alt=""></a>
                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <li class="body">
                                                    <div class="slimScrollDiv"
                                                         style="position: relative; overflow: hidden; width: auto; height: 254px;">
                                                        <ul class="menu smily-menu"
                                                            style="overflow: hidden; width: auto; height: 254px;">
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{:)2}{wave}</span>
                                                                    <span>goodbye!</span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{wave}</span>
                                                                    <span>raise hand</span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{cool}</span>
                                                                    <span>Great Job</span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{gq}</span>
                                                                    <span>can I help you?</span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{lightbulb}</span>
                                                                    <span>I have an idea!</span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{hmm}</span>
                                                                    <span>Try Again!</span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{:|3}</span>
                                                                    <span>yes!</span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                   class=" waves-effect waves-block js-emoji"><span>{:)}</span>
                                                                    <span>hello!</span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <li style="margin-left:-26px;margin-right: 5px;">
                                            <button type="submit" class="input-group-addon">Send</button>
                                        </li>
                                        <li>
                                            <input type="file" id="attach-file" style="display: none;">
                                            <a href="#" class="js-attach-file"><img
                                                        src="{{asset('painting-app/images/icons/ic_add_a_photo_black_24dp_1x.png')}}"
                                                        alt=""></a>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

</div>
<div id="loader" style="display: none;">
    <div class="lds-ring">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<div style="display: none;" id="canvas-image-holder">
</div>
<div class="modal" id="symbol-modal">
    <div class="modal-dialog">
        <div class="modal-container">
            <div class="modal-content" style="width:inherit;">
                <div class="modal-header">
                    <h4 class="text-left">Math Editor</h4>
                    <button class="btn btn-close btn-sm" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="height:auto;max-height:none;">
                    <div id="equation-editor-wrapper">
                    </div>
                    <div class="modal-footer">
                        <!--<button class="btn btn-default btn-sm">Copy To Editor</button>-->
                        <button class="btn btn-default btn-sm" id="clear-math-editor">Clear</button>
                        <button class="btn btn-default btn-sm" id="toLatex">Insert</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="save-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Do you want to save this painting?</h5>
                <button class="btn btn-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default js-clear-canvas" data-ans="no">Cancel</button>
                <a href="#" class="btn btn-default js-clear-canvas" data-ans="yes">Save</a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="cloud-modal">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 400px;">
            <div class="modal-header">
                <h5>CloudPack Explorer</h5>
                <button class="btn btn-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="height:300px;overflow: auto;">
                <div id="tree-holder">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="print-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-center">Printing Dialog</h5>
                <button class="btn btn-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="print-portions">
                    <p><b>Slide Printing Option</b></p>
                    <div class="row">
                        <div class="col-sm-4">
                            <input type="radio" value="current-slide" checked name="print_option"
                                   id="radio-current-slide">
                            <label for="radio-current-slide">
                                Current Slide
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <input type="radio" value="all-slide" name="print_option" id="print-all-slides">
                            <label for="print-all-slides">
                                All Slide
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <input type="radio" value="chat-only" name="print_option" id="print-chat-only">
                            <label for="print-chat-only">
                                Chat Only
                            </label>
                        </div>
                    </div>
                </div>
                <div class="print-options">
                    <p><b>Paper Orientation</b></p>
                    <div class="row">
                        <div class="col-sm-4">
                            <input type="radio" name="print_mode" value="potrait" checked id="radio-portrait">
                            <label for="radio-portrait" for="radio-portrait">Potrait</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="radio" name="print_mode" value="landscape" id="radio-landscape">
                            <label for="radio-landscape">
                                Landscape
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="print-data">Ok</button>
                <button class="btn" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="file-download-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn btn-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">

            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="user-input-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Please Select User Name and Type</h4>
            </div>
            <form action="" id="prompt-form">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="user_type" class="col-sm-4">Type</label>
                        <div class="col-sm-8">
                            <select name="user_type" id="user_type" class="form-control">
                                <option value="student">Student</option>
                                <option value="tutor">Tutor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user_name" class="col-sm-4">User Name</label>
                        <div class="col-sm-8">
                            <select name="user_name" id="user_name" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="subject" class="col-sm-4">Select Subject</label>
                        <div class="col-sm-8">
                            <select name="subject" id="subject" class="form-control">
                                <option value="algebra">Algebra</option>
                                <option value="mechancs">Mechanics</option>
                                <option value="electronics">Electronics</option>
                                <option value="calculus">Calculus</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="tech-support-modal">
    <div class="modal-dialog">
        <form method="post"  id="send-tech-report" data-url="{{url("utility/send-report/{$type}")}}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="margin-0 text-center"> Send Tech Support Report</h5>
                        <a href="#" class="btn btn-close" data-dismiss="modal">&times;</a>
                    </div>
                    <div class="modal-body">
                        {{Form::textarea('tech_support',null,['class'=>'form-control','id'=>'support-question','style'=>'resize:none;height:100px','required'=>'true'])}}
                    </div>
                    <div class="modal-footer text-center pad-tb-10">
                        <button type="submit" class="btn btn-default btn-lg">Send</button>
                    </div>
                </div>
        </form>
    </div>
</div>
<div class="modal" id="session-log-modal">
    <div class="modal-dialog" style="width:60%;">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header ">
                <h4 class="text-center margin-0">Session Logs</h4>
            </div>
            <div class="modal-body">
                {{Form::open(['url'=>url("utility/search-logs/{$type}"),'id'=>'session-lod-form'])}}
                <input type="hidden" value="{{$subject->id}}" name="subject_id">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon" id="end-date-label ">Date Range</span>
                            <input name="daterange" type="text" class="form-control js-daterangepicker"
                                   aria-describedby="end-date-label" value="{{date('m/d/Y')}}" style="padding:2px 10px;">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-default" type="submit">Filter</button>
                    </div>
                </div>
                {{Form::close()}}
                <h4 class="text-primary">Session Activities:</h4>
                <div style="max-height:500px;overflow-y: auto;">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Subject</th>
                            @if($type=='student')
                                <th>Tutor Name</th>
                            @endif
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Minutes</th>
                            <th>Session ID</th>
                        </tr>
                        </thead>
                        <tbody id="session-log-data">
                        @include('utility.session-table',compact('sessions','type'))
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="session-note-modal">
    <div class="modal-dialog">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header">
                <h5>New Session Note</h5>
                <a href="#" class="btn btn-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="modal-body">
                {{Form::open(['url'=>url("utility/save-session-note"),'method'=>'post','id'=>'session-note-form'])}}
                    <div class="text-center">
                        {{Form::textarea('note','',['class'=>'form-control','style'=>'resize:none;height:50px;','required'=>true])}}
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <input type="hidden" name="subject_id" value="{{$subject->id}}">
                        <input type="hidden" name="user_type" value="{{$type}}">

                        <button class="btn btn-default" style="margin-top:10px;">Save</button>
                    </div>
                {{Form::close()}}
                <div class="old-session-notes" style="height:300px;overflow-y: auto;">
                    <h4>Your Session Notes</h4>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Note</th>
                                <th>Date</th>
                            </tr>

                        </thead>
                        <tbody id="session-note-data">
                            @foreach($sessionNotes as $note)
                                <tr>
                                    <td>{{$note->note}}</td>
                                    <td>{{\Carbon\Carbon::parse($note->create_dat)->format('d M Y H:i')}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer text-right">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="notification-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Notifications</h5>
                <a href="#" class="btn btn-close" data-dismiss="modal">&times;</a>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Notification</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                            <tr>
                                <td>{{$notification->message}}</td>
                                <td>{{\Carbon\Carbon::parse($notification->created_at)->format('d M Y H:i')}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer text-right">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="file" id="input-image" style="display:none">
<ul class="student-request-list"></ul>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script>
    var user = {!! $user !!};
</script>
<script src="{{asset('painting-app/js/jquery.min.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var base_url = '{{url('/')}}';

</script>
<script src="{{asset('painting-app/emoji-js/emoji.js')}}"></script>
<script src="{{asset('painting-app/izitoast/js/iziToast.min.js')}}"></script>
<script src="{{asset('painting-app/chatjs/socket.io.js')}}"></script>
<script src="{{asset('painting-app/js/proper.min.js')}}"></script>
<script src="{{asset('painting-app/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('painting-app/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('painting-app/js/bootstrap-dropdownhover.min.js')}}"></script>
<script src="{{asset('painting-app/plugins/node-waves/waves.js')}}"></script>

<script src="{{asset('painting-app/equation-editor/mathquill.min.js?ver=1.1')}}"></script>
<script src="{{asset('painting-app/equation-editor/matheditor.js')}}"></script>
<script src="{{asset('painting-app/js/dom-to-image.js')}}"></script>
<script src="{{asset('painting-app/js-tree/jstree.js')}}"></script>
<script src="{{asset('painting-app/plugins/spectrum/spectrum.js')}}"></script>
<script src="{{asset('painting-app/nice-scrollbar/jquery.nicescroll.js')}}"></script>
<script>
    $("html").niceScroll();
</script>
<script src="{{asset('painting-app/momentjs/moment.js')}}"></script>
<script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(document).ready(function () {
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('.js-daterangepicker').find('span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
        }

        $('.js-daterangepicker').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'YYYY/MM/DD'
            },
            ranges: {
                'All Time': [moment('1970-1-1'), moment()],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "opens": "left",
        }, cb);
        cb(start, end);
    });
</script>
<script src="{{asset('painting-app/chatjs/chat.js')}}"></script>
<script src="{{asset('painting-app/js/canvas.js?ver=1.2')}}"></script>
</body>
</html>




