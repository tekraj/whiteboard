if (!HTMLCanvasElement.prototype.toBlob) {
    Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
        value: function (callback, type, quality) {
            var canvas = this;
            setTimeout(function () {
                var binStr = atob(canvas.toDataURL(type, quality).split(',')[1]),
                    len = binStr.length,
                    arr = new Uint8Array(len);

                for (var i = 0; i < len; i++) {
                    arr[i] = binStr.charCodeAt(i);
                }
                callback(new Blob([arr], {type: type || 'image/png'}));
            });
        }
    });
}

//plugin to move cursor
$.fn.selectRange = function (start, end) {
    if (end == undefined) {
        end = start;
    }
    return this.each(function () {
        if ('selectionStart' in this) {
            this.selectionStart = start;
            this.selectionEnd = end;
        } else if (this.setSelectionRange) {
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
}

function canvasDrawing(user, socket) {

    $('#symbol-modal').on('mousedown', '.matheditor-btn-span', function () {
        $(this).addClass('active');
    }).on('mouseup', '.matheditor-btn-span', function () {
        $(this).removeClass('active');
    });
    var mathEditor = new MathEditor('equation-editor-wrapper');
    mathEditor.setTemplate('floating-toolbar', 300, 300);
    /**
     * ======================================================
     * *************** variable declaration *****************
     * =====================================================
     */
    var drawingC = document.getElementById('drawing-board'),
        drawingCanvas = drawingC.getContext('2d'),
        fakeC = document.getElementById('fake-canvas'),
        fakeCanvas = fakeC.getContext('2d'),
        rC = document.getElementById('resize-canvas'),
        resizeCanvas = rC.getContext('2d'),
        checkCursorPosition = 0,
        cursor = 'default',
        currentColor = '#000',
        font = $('.js-font.active').data().font,
        fontSize = $('.js-font-size.active').data().size,
        fontStyle = $('.js-font-style.active').data().style,
        fontWeight = 'normal',
        lineSize = 1,
        position = getCoords(drawingC),
        textEnabled = false,

        draggingShape = {},
        eraserSize = lineSize,
        dc = $('#drawing-board'),
        parentDiv = dc.parent(),
        fa = $('#fake-canvas'),
        dragDiv = $('#drag-div'),
        textDivWidth = 50,
        imageHolder = $('#canvas-image-holder'),
        $enableTextTool = $('#enable-text-tool'),
        $tools = $('.js-tools'),
        textLeftCord,
        currentMouse = {x: 0, y: 0},
        textTopCord,
        pencilPoints = [],
        shiftPressed = false,
        textAnimation,
        textCursor = true,
        pixColor = [],
        drag = [],
        textwritten = true,
        $canvasWrapper = $('.canvas-wrapper'),
        symbolEnabled = false,
        scienceEnabled = false,
        graphColor = '#ccc',
        fakeCanvasMaxLenght = 10000,
        textHolder = $('#text-holder'),
        mouseDown,
        lineStartPoint = {x: 0, y: 0},
        lineEndPoint = {x: 0, y: 0},
        j = 0,
        pdfEnabled = false,
        pdfReaderWrapper = $('#pdf-reader'),
        background = '#fff',
        eraserPoints = [],
        publicModeEnabled = false,
        $onlineUsers = $('#online-users'),
        isNewDrawing = false,
        loader = $('#loader'),
        currentTool = 'text';
    var foreignCanvasData = [];
    dc.css({'cursor': cursor});
    var parentHeight = dc.parent().height();
    var parentWidth = dc.parent().width();
    dc.attr({'height': parentHeight - 8, 'width': parentWidth - 5});
    fa.attr({'height': parentHeight - 8, 'width': parentWidth - 5});
    dc.parent().scroll(function () {
        position = getCoords(drawingC);
    });
    /**
     * ======================================================
     * *************** script to change the value of variables
     * =====================================================
     */

    //default font-indicator
    $('#demo-font-text,#text-holder').css({'font-family': font, 'font-size': fontSize, 'font-style': fontStyle});
    // textInput.css({'font-family':font,'font-size':fontSize,'font-style':fontStyle});
    $('.js-enable-symbol').mouseover(function () {
        symbolEnabled = true;
    })
    //enable subscript and superscript
    $('#enable-subscript').mouseover(function () {
        textEnabled = true;
        symbolEnabled = true;
    }).click(function (e) {
        e.preventDefault();
        if (!$enableTextTool.hasClass('active')) {
            alert('Please select text and click anywhere in the board.');
            return false;
        }
        pasteHtmlAtCaret('<sub style="color:' + currentColor + ';">&#8203;</sub>', textHolder[0]);

    });
    //clear math editor
    $('#clear-math-editor').click(function (e) {
        e.preventDefault();
        $('#equation-editor-wrapper').find('.mq-root-block').removeClass('mq-hasCursor').addClass('mq-empty').html('')
    });
    $('#enable-superscript').mouseover(function () {
        textEnabled = true;
        symbolEnabled = true;
    }).click(function (e) {
        e.preventDefault();
        if (!$enableTextTool.hasClass('active')) {
            alert('Please select text and click anywhere in the board.');
            return false;
        }
        pasteHtmlAtCaret('<sup style="color:' + currentColor + '">&#8203;</sup>', textHolder[0]);
    });

    $('.js-science-symbol').click(function (e) {
        e.preventDefault();
        if (!$enableTextTool.hasClass('active')) {
            alert('Please select text first.')
            return false;
        }

        var symbol = $(this).data().symbol;
        pasteHtmlAtCaret('<span style="color:' + currentColor + '">' + symbol + '</span>', textHolder[0]);

    });

    $('.font-menu-wrapper').mouseenter(function () {
        symbolEnabled = true;
    });
    var symbolModal = $('#symbol-modal');
    $('.js-show-equation-modal').click(function (e) {
        e.preventDefault();
        writeTextDivToCanvas(textLeftCord, textTopCord, function () {
            textHolder.html('');
            textHolder.hide();
            symbolModal.modal('show');
            setTimeout(function () {
                $('#equation-editor-wrapper').find(".mq-root-block").append('<span>&#8203;</span>').click();
            }, 100);
        });

    });
    $('.color-menu  ').mouseover(function () {
        symbolEnabled = true;
    }).mouseleave(function () {
        symbolEnabled = false;
    });
    //change default color
    $('.js-color-code').click(function (e) {
        e.preventDefault();
        var color = $(this).data().color;
        $('.js-color-code').removeClass('active');
        $(this).addClass('active');
        $('#color-indicator').css({'background': color});
        $('#color-indicator').attr('data-color',color);
        $('#color-indicator').data().color = color;
        $('#canvas-text-input').css('color', color);
        currentColor = color;

        if (currentTool == 'text') {
            if (textHolder.text().trim().length < 1) {
                textHolder.css('color', currentColor);
                textHolder[0].focus();
            } else {
                pasteHtmlAtCaret("<span style='color:" + currentColor + ";'>&#8203;</span>", textHolder[0]);
            }
        }


    });

    //change tools
    $('.option-menu-wrapper').mouseover(function () {
        //show options if exists
        var optionMenu = $(this).find('.option-menu');
        if (optionMenu.length > 0) {
            optionMenu.show();
        }
    }).mouseleave(function () {
        var optionMenu = $(this).find('.option-menu');
        if (optionMenu.length > 0) {
            optionMenu.hide();
        }
    });
    $tools.click(function (e) {

        e.preventDefault();
        if (pdfEnabled) {
            alert('Currently You are in Read Mode');
            return false;
        }

        $tools.removeClass('active');
        $(this).addClass('active');

        currentTool = $(this).data().tool;
        var toolCursor = $(this).data().cursor;
        currentColor = $('#color-indicator').data('color');
        if (currentTool == 'text') {
            textHolder.show();
            if (textHolder.text().trim().length < 1) {
                textHolder.css('color', currentColor);
                textHolder[0].focus();
            }
        } else {
            textEnabled = false;
            textHolder.blur
            textHolder.hide();
        }
        if (toolCursor) {
            dc.css({'cursor': toolCursor});
        }
    });

    $('#mouse-cursor').click(function(e){
        e.preventDefault();
        $('#enable-text-tool').click();
    });

    $('#session-note-form').submit(function(e){
        e.preventDefault();
        if($(this).hasClass('sending'))
            return false;

        var $this = $(this);
        var url = $(this).attr('action');
        var data = $(this).serializeArray();
        $this.addClass('sending');

        $.ajax({
            type : 'post',
            url:url,
            data: data,
            beforeSend : function(){
                $this.find('.btn').text('Saving..')
            },
            success : function (response) {
                if(response.status){
                    $this.removeClass('sending');
                    $this.find('.btn').text('Save');
                    $this.find('textarea').text('').val('');
                    iziToast.show({
                        class: 'success',
                        message: 'Session Log Saved',
                        color: 'green',
                        icon: '',
                        position: 'topRight',
                        timeout: 5000
                    });
                    var note = response.note
                    $('#session-note-data').append('<tr><td>'+note.note+'</td><td>'+note.date+'</td></tr>')
                }else{
                    alert('Sorry unable to save note');
                }

            }
        })
    });
    // code for eraser slider
    $('#eraser-slider').slider({
        create: function () {
            $('#eraser-slider .ui-slider-handle').text(2);
        },
        slide: function (event, ui) {
            $('#eraser-slider .ui-slider-handle').text(ui.value)
            eraserSize = ui.value;
        },
        range: "max",
        min: 2,
        max: 20,
    });
    //change line width
    $('.js-line-width').click(function (e) {
        e.preventDefault();
        $('.js-line-width').removeClass('active');
        $(this).addClass('active');
        lineSize = $(this).data().line;
        $('.option-menu').hide();
    });

    //change font family
    $('.js-font').click(function (e) {
        e.preventDefault();
        $('.js-font').removeClass('active');
        $(this).addClass('active');
        var activeFont = $(this).data().font;
        $('.js-text-demo').css({'font-family': activeFont});
    });
    $('.js-font-style').click(function (e) {
        e.preventDefault();
        $('.js-font-style').removeClass('active');
        $(this).addClass('active');
        var activeFontStyle = $(this).data().style;
        if (activeFontStyle == 'italic' || activeFontStyle == 'bold italic') {
            $('.js-text-demo').css({'font-style': 'italic'});
        } else {
            $('.js-text-demo').css({'font-style': 'normal'});
        }

        if (activeFontStyle == 'bold' || activeFontStyle == 'bold italic') {
            $('.js-text-demo').css({'font-weight': 'bold'});
        } else {
            $('.js-text-demo').css({'font-weight': 'normal'});
        }

    });
    $('.js-font-size').click(function (e) {
        e.preventDefault();
        $('.js-font-size').removeClass('active');
        $(this).addClass('active');
        var activeFontSize = $(this).data().size;
        $('.js-text-demo').css({'font-size': activeFontSize});
    });

    $('#change-font').click(function (e) {
        e.preventDefault();
        font = $('.js-font.active').data().font;
        fontSize = $('.js-font-size.active').data().size;
        fontStyle = $('.js-font-style.active').data().style;

        var css = {'font-family': font, 'font-size': fontSize};
        if (fontStyle == 'italic' || fontStyle == 'bold italic') {
            css['font-style'] = 'italic';
        } else {
            css['font-style'] = 'normal';
        }

        if (fontStyle == 'bold' || fontStyle == 'bold italic') {
            css['font-weight'] = 'bold';
            fontWeight = 'bold'
        } else {
            css['font-weight'] = 'bold';
            fontWeight = 'normal';

        }
        $('.js-text-demo').css(css);
        if (currentTool == 'text') {
            if (textHolder.text().trim().length < 1) {
                textHolder.css('color', currentColor);
                textHolder.css(css);
                textHolder[0].focus();
            } else {
                pasteHtmlAtCaret("<span style='color:" + currentColor + ";font-family:" + font + ";font-size:" + fontSize + "px;font-style:" + fontStyle + ";font-weight:" + fontWeight + "'>&#8203;</span>", textHolder[0]);
            }
        }
        $('.font-menu').hide();
    });

    $('#cancel-font').click(function (e) {
        e.preventDefault();
        $('.option-menu').hide();
    });

    //browse cloud
    $('#browse-cloud').click(function (e) {
        e.preventDefault();
        $('.top-nav-tools .btn-square').removeClass('active');
        $(this).addClass('active');
        var url = $(this).data().url;
        $.ajax({
            type: 'get',
            url: url,
            success: function (response) {
                $('#tree-holder').jstree({
                    'core': {
                        'data': response
                    }
                });
                $('#cloud-modal').modal('show');
            }
        })
    });

    //read file
    $(document).on('click', '.js-read-cloud-file', function () {
        $canvasWrapper.addClass('no-scroll');
        var file = $(this).attr('file');
        $('#cloud-modal').modal('hide');
        $tools.removeClass('.active');
        $('#enable-drawing').removeClass('active');
        $canvasWrapper.find('canvas').hide();
        $('#reader-mode-indicator').addClass('active');
        pdfReaderWrapper.show();
        pdfEnabled = true;
        var width = $canvasWrapper.width();
        var height = $canvasWrapper.height();
        pdfReaderWrapper.html(' <object data="' + file + '" type="application/pdf" width="'+width+'" height="'+height+'"></object>');
    });

    //read sav file

    $('.js-load-whiteboard-click').click(function(e){
        e.preventDefault();
        $(this).siblings('.js-load-whiteboard').click();
    });

    $('.js-load-whiteboard').change(function(){
        var file = this.files[0];
        var url = $(this).data().url;
        var fd = new FormData();
        fd.append('sav-file',file);
        $.ajax({
            type:'post',
            url : url,
            data:fd,
            processData:false,
            contentType:false,
            beforeSend: function (){
                loader.show();
            },
            success : function(response){
                loader.hide();
                if(response.status){
                    canvasData = response.data;
                    streamCanvasDrawing(canvasData, publicModeEnabled,false,lineEndPoint);
                    canvasData.forEach(function(element){
                        canvasObjects.push(element);
                        drawMultipleShapes(element,true);

                    });

                }
            }
        })
    });


    //save to sav file
    $('.js-save-whiteboard').click(function(){
        var url = $(this).data().url;
        if(canvasObjects.length<1)
            return false;

        $.ajax({
            type:'post',
            url : url,
            data:{data:canvasObjects},
            beforeSend: function (){
                loader.show();
            },
            success : function(response){
                loader.hide();
               if(response.status){
                   window.location.href = base_url+'/utility/download-file?file='+response.file;
               }
            }
        })
    });
    //enable canvas
    $('#enable-drawing').click(function (e) {
        $canvasWrapper.removeClass('no-scroll');
        e.preventDefault();
        $tools.removeClass('.active');
        $('#browse-cloud').removeClass('active');
        $(this).addClass('active');
        $canvasWrapper.find('canvas').show();
        position = getCoords(drawingC)
        pdfReaderWrapper.hide();
        pdfEnabled = false;
        $('#reader-mode-indicator').removeClass('active');
    });

    //insert symbols
    $('#toLatex').on('click', function () {
        currentTool = 'text';
        insertSymbols(function () {
            symbolEnabled = false;
            $('.modal').modal('hide');
            $('#equation-editor-wrapper').find('.mq-root-block').html('');
        });
    });
    var fileDownloadModal = $('#file-download-modal');
    //clear canvas
    $('.js-clear-canvas').click(function () {
        var imageSaveUrl = $('body').data().imageurl;
        var ans = $(this).data().ans;
        if (ans == 'yes') {
            var href = drawingC.toDataURL("image/png");
            $.ajax({
                type: 'post',
                url: imageSaveUrl,
                data: {imageData: href.replace('data:image/png;base64,', '')},
                success: function (response) {
                    drawingCanvas.clearRect(0, 0, drawingC.width, drawingC.height);
                    canvasObjects = [];
                    foreignCanvasData = [];
                    redrawCanvas();
                    if(user.userType=='tutor' && $onlineUsers.find('li.active').length>0){
                        var rec = $onlineUsers.find('li.active').find('.js-online-users').data().user;
                        socket.emit('redraw-canvas', {receiver: rec,type:'new-board'});
                    }

                    $enableTextTool.click();
                    if (response) {
                        fileDownloadModal.find('.modal-body').html('<h4>Your drawing is saved. <a href="' + response.file + '" download>Click here to download it.</a></h4>')
                        fileDownloadModal.modal('show');
                    }
                }
            });

        }else{
            drawingCanvas.clearRect(0, 0, drawingC.width, drawingC.height);
            canvasObjects = [];
            foreignCanvasData = [];
            redrawCanvas();
            if(user.userType=='tutor' && $onlineUsers.find('li.active').length>0){
                var rec = $onlineUsers.find('li.active').find('.js-online-users').data().user;
                socket.emit('redraw-canvas', {receiver: rec,type:'new-board'});
            }

            $enableTextTool.click();
        }
        textHolder.html('');
        textHolder.hide();
        $('.modal').modal('hide');

        drag = [];
        currentColor = '#000';
        lineSize = 2;
        font = 'serif';
        fontSize = 18;
        fontStyle = 'normal';
        textHolder.css({'font-size': fontSize, 'color': currentColor, 'font-style': 'normal', 'font-weight': 'normal'});
        dc.attr({'height': parentHeight - 8, 'width': parentWidth - 5});
        fa.attr({'height': parentHeight - 8, 'width': parentWidth - 5});
        // rC.attr({'height': parentHeight - 8, 'width': parentWidth - 5});
        pencilPoints = [];
        $enableTextTool.click();
        $('#color-indicator').css('background', '#000');

    });

    //detect shit key pressed for straight lines and squares
    $('body').keydown(function (e) {
        if (e.keyCode == 16)
            shiftPressed = true;
    });

    $('body').keyup(function (e) {

        if (currentTool == 'p-line') {
            drawLine(lineStartPoint.x, lineStartPoint.y, lineEndPoint.x, lineEndPoint.y);
            currentTool = 'pencil';
            fa.hide();
        }
        shiftPressed = false;

    });

    //function for pushing the values in the arraysx
    function pushPencilPoints(x, y, tf) {
        pencilPoints.push({x: x, y: y});
    }

    $('#input-image').change(function(){
        if(this.files && this.files[0]) {
            var file = this.files[0];
            var fileName = file.name;
            fileName = fileName.split('.');
            var extension = fileName[fileName.length-1];
            if(['jpg','jpeg','png'].indexOf(extension)>=0) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    $enableTextTool.click();
                    $('#paste-tool').removeClass('active');
                    var img = new Image;
                    img.onload = function () {
                        var width = img.width;
                        var height = img.height;
                        var canvasHeight = drawingC.height;
                        var canvasWidth = drawingC.width;
                        var left = currentMouse.x + width;
                        var top = currentMouse.y + height;
                        if (left > canvasWidth) {
                            parentDiv.scrollLeft(left);
                            rC.width = canvasWidth;
                            rC.height = canvasHeight;
                            resizeCanvas.drawImage(drawingC, 0, 0);
                            dc.attr('width', left);
                            fa.attr('width', left);
                            drawingCanvas.drawImage(rC, 0, 0);
                        }
                        if (top > canvasHeight) {
                            parentDiv.scrollLeft(top);
                            rC.width = canvasWidth;
                            rC.height = canvasHeight;
                            resizeCanvas.drawImage(drawingC, 0, 0);
                            dc.attr('height', top);
                            fa.attr('height', top);
                            drawingCanvas.drawImage(rC, 0, 0);
                        }
                        drawingCanvas.drawImage(img, currentMouse.x, currentMouse.y);
                        saveCanvasObjects('image', {
                            startX: currentMouse.x,
                            startY: currentMouse.y,
                            endX: currentMouse.x + img.width,
                            endY: currentMouse.y + img.height,
                            image: event.target.result
                        });
                    };

                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }else{
                alert('Please select image');
            }
        }
    });
    /**
     * ======================================================
     * *************** event handlers
     * =====================================================
     */

    //click event for body

    //mouseDown Event Handler
    dc.mousedown(function (e) {
        currentColor = $('#color-indicator').data().color;
        eraserPoints = [];
        mouseDown = true;
        var left = e.pageX - position.left,
            top = e.pageY - position.top;
        lineStartPoint.x = left;
        lineStartPoint.y = top;
        lineEndPoint.x = left;
        lineEndPoint.y = top;
        if (currentTool === 'pencil') {
            pushPencilPoints(left, top)
            drawPencil(drawingCanvas, currentColor, lineSize);
        } else if (currentTool === 'text') {
            textEnabled = true;
            writeTextDivToCanvas(textLeftCord, textTopCord, function () {
                textHolder.show();
                textHolder.css({
                    'color': currentColor,
                    'font-size': fontSize,
                    'font-family': font,
                    'font-weight': fontWeight,
                    'font-style': fontStyle
                }).html('');
                $enableTextTool.addClass('active');
                dc.css('cursor', 'url(images/text.png), auto');
                $enableTextTool.addClass('border');
                var isPrevTextField = checkTextEdit(left, top);
                if (isPrevTextField !== false) {
                    textHolder.css(isPrevTextField.cssObj).html(isPrevTextField.html);
                    textLeftCord = isPrevTextField.left;
                    textTopCord = isPrevTextField.top - 2;
                    textHolder.css({left: textLeftCord, top: textTopCord});
                } else {
                    textHolder.css({left: left, top: top});
                    textLeftCord = left;
                    textTopCord = top;
                }
                if (isPrevTextField == false) {
                    setTimeout(function () {
                        textHolder[0].focus();
                    }, 50);
                }

            });

        } else if (currentTool == 'line' || currentTool == 'cube' || currentTool == 'rectangle' || currentTool == 'oval' || currentTool == 'cone' || currentTool == 'pyramid' || currentTool == 'xgraph' || currentTool == 'xygraph' || currentTool == 'cylinder' || currentTool == 'rectangle-filled' || currentTool == 'oval-filled' || currentTool == 'line-sarrow' || currentTool == 'line-darrow') {
            fa.show();
            lineStartPoint.x = left;
            lineStartPoint.y = top;
        } else if (currentTool == 'drag') {
            var init = initializeDrag(left, top);
            if (init) {
                redrawCanvas();
                showDragUIAnimation(left, top);
            }
        }else if(currentTool=='paste'){
            $('#input-image').click();
        }


    }).mouseleave(function () {
        textEnabled = false;
        textwritten = false;
    });
    //mousemove
    $('body').on('mousemove', function (e) {

        var left = e.pageX - position.left;
        var top = e.pageY - position.top;
        currentMouse.x = left;
        currentMouse.y = top;

        if (mouseDown) {
            lineEndPoint.x = left;
            lineEndPoint.y = top;
            var canvasHeight = drawingC.height;
            var canvasWidth = drawingC.width;

            if (left > canvasWidth) {
                parentDiv.scrollLeft(left);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('width', left);
                fa.attr('width', left);
                drawingCanvas.drawImage(rC, 0, 0);
            }
            if (top > canvasHeight) {
                parentDiv.scrollLeft(top);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('height', top);
                fa.attr('height', top);
                drawingCanvas.drawImage(rC, 0, 0);
            }
            if (currentTool == 'drag') {
                showDragUIAnimation(left, top)
            } else if (currentTool == 'paint-bucket') {
                drawingCanvas.fillRect(0, 0, c.width, c.height);
                drawingCanvas.beginPath();
                drawingCanvas.rect(0, 0, c.width, c.height);
                drawingCanvas.fillStyle = currentColor;
                drawingCanvas.fill();
                background = currentColor;
                paintBucket = false;
                drag = [];


            } else if (currentTool == 'eraser') {
                fa.show();
                showEraserAnimation(left, top, eraserSize);
                eraseActualDrawing(left, top, eraserSize);
            } else if (currentTool == 'pencil') {

                //if shift is pressed draw straight line
                if (shiftPressed) {
                    fa.show();
                    if (pencilPoints.length > 0) {
                        lineStartPoint.x = pencilPoints[pencilPoints.length - 1].x;
                        lineStartPoint.y = pencilPoints[pencilPoints.length - 1].y;
                    } else {
                        lineStartPoint.x = left;
                        lineStartPoint.y = top;
                    }
                    pencilPoints = [];
                    currentTool = 'p-line';
                } else {
                    pushPencilPoints(left, top, currentColor, lineSize, true);
                    drawPencil(drawingCanvas);
                }

            } else if (currentTool == 'line' || currentTool == 'p-line') {
                drawLineAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'cube') {
                drawCubeAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'rectangle') {
                drawRectangleAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'oval') {
                drawCircleAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'cylinder') {
                drawCylinderAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'cone') {
                drawConeAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'pyramid') {
                drawPyramidAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'xgraph') {
                drawXGraphAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'xygraph') {
                drawXYGraphAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize);
            } else if (currentTool == 'rectangle-filled') {
                drawRectangleAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, false, true);
            }
            else if (currentTool == 'oval-filled') {
                drawCircleAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, false, true);
            } else if (currentTool == 'line-sarrow') {
                drawLineAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, false, 'single');
            } else if (currentTool == 'line-darrow') {
                drawLineAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, false, 'double');
            }
        }

    });

    //mouseup
    $('body').on('mouseup', function (e) {

        var left = e.pageX - position.left;
        var top = e.pageY - position.top;
        if (mouseDown) {
            dragDiv.hide();
            fa.hide();
            if (currentTool == 'drag') {
                drawShapeAgain();
            } else if (currentTool == 'pencil') {
                var minMaxPoints = findMinMaxXYPoints(pencilPoints);
                saveCanvasObjects('pencil', {
                    lineSize: lineSize,
                    color: currentColor,
                    pencilPoints: pencilPoints,
                    minMaxPoints: minMaxPoints
                });
            } else if (currentTool == 'line') {
                drawLine(lineStartPoint.x, lineStartPoint.y, left, top);
                saveCanvasObjects('line', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'cube') {
                drawCube(lineStartPoint.x, lineStartPoint.y, left, top);
                saveCanvasObjects('cube', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'rectangle') {
                drawRectangle(lineStartPoint.x, lineStartPoint.y, left, top);
                saveCanvasObjects('rectangle', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'oval') {
                drawCircleAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true);
                saveCanvasObjects('oval', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'cylinder') {
                drawCylinderAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true);
                saveCanvasObjects('cylinder', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'cone') {
                drawConeAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true);
                saveCanvasObjects('cone', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'pyramid') {
                drawPyramidAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true);
                saveCanvasObjects('pyramid', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'xgraph') {
                drawXGraphAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true);
                saveCanvasObjects('xgraph', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'xygraph') {
                drawXYGraphAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true);
                saveCanvasObjects('xygraph', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'rectangle-filled') {
                drawRectangleAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true, true);
                saveCanvasObjects('rectangle-filled', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'oval-filled') {
                drawCircleAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true, true);
                saveCanvasObjects('oval-filled', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'line-sarrow') {
                drawLineAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true, 'single');
                saveCanvasObjects('line-sarrow', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'line-darrow') {
                drawLineAnimation(lineStartPoint.x, lineStartPoint.y, left, top, currentColor, lineSize, true, 'double');
                saveCanvasObjects('line-darrow', {
                    startX: lineStartPoint.x,
                    startY: lineStartPoint.y,
                    endX: left,
                    endY: top,
                    shift: shiftPressed,
                    color: currentColor,
                    lineSize: lineSize
                });
            } else if (currentTool == 'eraser') {
                canvasObjects.push({shape: 'eraser', data: eraserPoints});
                streamCanvasDrawing([{shape: 'eraser', data: eraserPoints}], publicModeEnabled,false,lineEndPoint);
            }
        }
        if(currentTool=='drag'){
            socket.emit('redraw-foreign',{data:canvasObjects,receiver:receiver});
        }
        fakeCanvas.clearRect(0, 0, fakeC.height, fakeC.width);
        mouseDown = false;
        pencilPoints = [];
        lineEndPoint.x = left;
        lineEndPoint.y = top;

    });

    textHolder.on('blur', function () {

        if (textEnabled || symbolEnabled)
            return;
        writeTextDivToCanvas(textLeftCord, textTopCord, function () {
            $enableTextTool.removeClass('border');
            textHolder.hide();
            textHolder.css('color', currentColor).html('');
            textwritten = true;
        });

    });

    function midPointBtw(p1, p2) {
        return {
            x: p1.x + (p2.x - p1.x) / 2,
            y: p1.y + (p2.y - p1.y) / 2
        };
    }


    function findMinMaxXYPoints(points) {
        var pointX = [];
        var pointY = [];
        for (var i in points) {
            var point = points[i];
            if (point.x && point.y) {
                pointX.push(point.x);
                pointY.push(point.y);
            }
        }
        return {
            minX: Math.min(...pointX),
            minY: Math.min(...pointY),
            maxX: Math.max(...pointX),
            maxY: Math.max(...pointY)
        }
    }

    /**
     * function to erase drawing
     * @param l
     * @param t
     * @param es
     */
    function eraseActualDrawing(l, t, es) {
        drawingCanvas.beginPath();
        drawingCanvas.globalCompositeOperation = "destination-out";
        drawingCanvas.arc(l, t, es, 0, Math.PI * 2, false);
        drawingCanvas.fill();
        drawingCanvas.globalCompositeOperation = 'source-over';
        eraserPoints.push({left: l, top: t, eraserSize: es});

    }

    /**
     * ======================================================
     * *************** draw with pencil
     * =====================================================
     */

    function drawPencil(ctx, color, size) {
        var p1 = pencilPoints[0];
        var p2 = pencilPoints[1];

        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(p1.x, p1.y);
        for (var i = 1, len = pencilPoints.length; i < len; i++) {
            var midPoint = midPointBtw(p1, p2);
            ctx.quadraticCurveTo(p1.x, p1.y, midPoint.x, midPoint.y);
            p1 = pencilPoints[i];
            p2 = pencilPoints[i + 1];
        }

        // ctx.lineTo(p1.x, p1.y);
        ctx.lineJoin = ctx.lineCap = 'round';
        // ctx.shadowBlur = 0.001;
        // ctx.shadowColor = currentColor;
        ctx.strokeStyle = color;
        ctx.lineWidth = size;
        ctx.stroke();
        ctx.closePath();

    }

    /**
     * ======================================================
     * *************** function for line animation
     * =====================================================
     */
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param type
     * @param color
     * @param size
     */
    function drawLineAnimation(x1, y1, x2, y2, color, size, noAnimation, type) {
        var noAnimation = noAnimation ? noAnimation : false;
        var type = type ? type : 'simple';
        var lineEndPointX = x2,
            lineEndPointY = y2;
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        //if shift is pressed detect to which way we have to draw line
        if (shiftPressed) {
            var dx = Math.abs(x2 - x1);
            var dy = Math.abs(y2 - y1);
            if (dx > dy) {
                lineEndPointY = y1;
            } else {
                lineEndPointX = x1;
            }
        }
        if (type == 'double' || type == 'single') {
            drawArrow(ctx, x1, y1, lineEndPointX, lineEndPointY, color, size);
        }
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(x1, y1);
        ctx.lineTo(lineEndPointX, lineEndPointY);
        ctx.closePath();
        ctx.strokeStyle = color;
        ctx.lineWidth = size;
        ctx.fill();
        ctx.stroke();
        ctx.closePath();
        if (type == 'double') {
            drawArrow(ctx, lineEndPointX, lineEndPointY, x1, y1, color, size);

        }
    }

    /**
     * ======================================================
     * *************** function to draw line
     * =====================================================
     */
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     */
    function drawLine(x1, y1, x2, y2) {
        drawLineAnimation(x1, y1, x2, y2, currentColor, lineSize, true);
    }


    /**
     * ======================================================
     * *************** function for cube animation
     * =====================================================
     */
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param insertPoint
     */
    function drawCubeAnimation(x1, y1, x2, y2, color, size, noAnimation) {
        var noAnimation = noAnimation ? noAnimation : false;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        var sizeX = Math.abs(x2 - x1),
            sizeY = Math.abs(y2 - y1),
            z = sizeX / 2,
            h = sizeY,
            p0 = {x: x2, y: y2},
            p1 = {x: x2 - sizeX, y: y2 - sizeX * 0.5},
            p2 = {x: x2 - sizeX, y: y2 - h - sizeX * 0.5},
            p3 = {x: x2, y: y2 - h},
            p4 = {x: x2 + sizeY, y: y2 - sizeY * 0.5},
            p5 = {x: x2 + sizeY, y: y2 - h - sizeY * 0.5},
            p6 = {x: x2 - sizeX + sizeY, y: y2 - h - (sizeX * 0.5 + sizeY * 0.5)},
            p7 = {x: p6.x, y: p2.y + ((p1.y - p2.y) / 2)};
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(p0.x, p0.y);
        ctx.lineTo(p1.x, p1.y);
        ctx.lineTo(p2.x, p2.y);
        ctx.lineTo(p3.x, p3.y);
        ctx.closePath();
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();

        // right face
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(p0.x, p0.y);
        ctx.lineTo(p4.x, p4.y);
        ctx.lineTo(p5.x, p5.y);
        ctx.lineTo(p3.x, p3.y);
        ctx.closePath();
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();


        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(p3.x, p3.y);
        ctx.lineTo(p2.x, p2.y);
        ctx.lineTo(p6.x, p6.y);
        ctx.lineTo(p5.x, p5.y);
        ctx.closePath();
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();

        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(p6.x, p6.y);
        ctx.lineTo(p7.x, p7.y);
        ctx.lineTo(p1.x, p1.y);
        ctx.lineTo(p0.x, p0.y);
        ctx.lineTo(p4.x, p4.y);
        ctx.lineTo(p7.x, p7.y);
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();
        ctx.closePath();

    }

    /**
     * ======================================================
     * *************** function to draw cube
     * =====================================================
     */
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     */
    function drawCube(x1, y1, x2, y2) {
        drawCubeAnimation(x1, y1, x2, y2, currentColor, lineSize, true);
    }

    /**
     * ======================================================
     * *************** function for cube animation
     * =====================================================
     */
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param filled
     * @param color
     * @param size
     */
    function drawRectangleAnimation(x1, y1, x2, y2, color, size, noAnimation, filled) {
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        var noAnimation = noAnimation ? noAnimation : false;
        var filled = filled ? filled : false;
        width = x2 - x1;
        height = y2 - y1;
        if (shiftPressed) {
            height = ((x2 - x1) / Math.abs(x2 - x1)) * width;
        }
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.rect(x1, y1, width, height);
        ctx.stroke();
        if (filled) {
            ctx.fillStyle = color;
            ctx.fill();
        }
        ctx.closePath();

    }

    /**
     * ======================================================
     * *************** function to draw cube
     * =====================================================
     */
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     */
    function drawRectangle(x1, y1, x2, y2) {
        drawRectangleAnimation(x1, y1, x2, y2, currentColor, lineSize, true);
    }


    /**
     * ======================================================
     * *************** function to draw eraser animation
     * =====================================================
     */
    /**
     *
     * @param left
     * @param top
     * @param eraserSize
     */
    function showEraserAnimation(left, top, eraserSize) {
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        fakeCanvas.beginPath();
        fakeCanvas.arc(left, top, eraserSize, 0, Math.PI * 2, false);
        fakeCanvas.strokeStyle = '#000';
        fakeCanvas.fillStyle = '#fff';
        fakeCanvas.stroke();
        fakeCanvas.fill();
        fakeCanvas.closePath();
    }

    /**
     * ======================================================
     * *************** function for circle animation
     * =====================================================
     */
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param fill
     * @param color
     * @param size
     */
    function drawCircleAnimation(x1, y1, x2, y2, color, size, noAnimation, fill) {
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        var noAnimation = noAnimation ? noAnimation : false;
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        var fill = fill ? fill : false;
        var width = x2 - x1;
        var height = y2 - y1;
        var centerX = x1 + (width / 2);
        var centerY = y1 + (height / 2);
        var radius = width > height ? width : height;
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        if (shiftPressed) {
            ctx.arc(lineStartPoint.x, lineStartPoint.y, radius, 0, Math.PI * 2)
        } else {
            ctx.moveTo(centerX, centerY - height / 2); // A1
            ctx.bezierCurveTo(
                centerX + width / 2, centerY - height / 2, // C1
                centerX + width / 2, centerY + height / 2, // C2
                centerX, centerY + height / 2); // A2

            ctx.bezierCurveTo(
                centerX - width / 2, centerY + height / 2, // C3
                centerX - width / 2, centerY - height / 2, // C4
                centerX, centerY - height / 2); // A1
        }

        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();
        if (fill) {
            ctx.fillStyle = color;
            ctx.fill();
        }
        ctx.closePath();

    }

    /**
     * ======================================================
     * *************** function for cylinder  animation
     * =====================================================
     */
    /**
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param color
     * @param
     */
    function drawCylinderAnimation(x1, y1, x2, y2, color, size, noAnimation) {
        var noAnimation = noAnimation ? noAnimation : false;
        var i, xPos, yPos, pi = Math.PI, twoPi = 2 * pi;
        var w = x2 - x1;
        var h = y2 - y1;
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        var x = x1;
        var y = y1;
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        for (i = 0; i < twoPi; i += 0.001) {
            xPos = (x + w / 2) - (w / 2 * Math.cos(i));
            yPos = (y + h / 8) + (h / 8 * Math.sin(i));

            if (i === 0) {
                ctx.moveTo(xPos, yPos);
            } else {
                ctx.lineTo(xPos, yPos);
            }
        }
        ctx.moveTo(x, y + h / 8);
        ctx.lineTo(x, y + h - h / 8);

        for (i = 0; i < twoPi; i += 0.001) {
            xPos = (x + w / 2) - (w / 2 * Math.cos(i));
            yPos = (y + h - h / 8) + (h / 8 * Math.sin(i));

            if (i === 0) {
                ctx.moveTo(xPos, yPos);
            } else {
                ctx.lineTo(xPos, yPos);
            }
        }
        ctx.moveTo(x + w, y + h / 8);
        ctx.lineTo(x + w, y + h - h / 8);
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();
        ctx.closePath();
    }

    /**
     * ======================================================
     * *************** function for cone animation
     * =====================================================
     */

    /**
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param color,
     * @param size
     */
    function drawConeAnimation(x1, y1, x2, y2, color, size, noAnimation) {
        var noAnimation = noAnimation ? noAnimation : false;
        var i, xPos, yPos, pi = Math.PI, twoPi = 2 * pi;
        var w = x2 - x1;
        var h = y2 - y1;
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        var x = x1;
        var y = y1;
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";

        ctx.moveTo(x, y);
        ctx.lineTo(x - w, y + h - h / 8);

        for (i = 0; i < twoPi; i += 0.001) {
            xPos = (x2 - w) - (w * Math.cos(i));
            yPos = (y + h - h / 8) + (h / 8 * Math.sin(i));
            if (i === 0) {
                ctx.moveTo(xPos, yPos);
            } else {
                ctx.lineTo(xPos, yPos);
            }
        }
        ctx.moveTo(x, y);
        ctx.lineTo(x + w, y + h - h / 8);
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();
        ctx.closePath();
    }

    /**
     * ======================================================
     * *************** function for pyramid animation
     * =====================================================
     */
    /**
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param color
     * @param size
     */
    function drawPyramidAnimation(x1, y1, x2, y2, color, size, noAnimation) {
        var noAnimation = noAnimation ? noAnimation : false;
        var w = x2 - x1;
        var h = y2 - y1;
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        var r = h / 4;
        var p1 = {x: x2 - (3 * w / 2) - (r * Math.cos(Math.PI / 4)), y: y2 + (r * Math.sin(Math.PI / 4))},
            p2 = {x: x2 - (3 * w / 2) + (r * Math.cos(Math.PI / 4)), y: y2 + (r * Math.sin(-Math.PI / 4))},
            p3 = {x: x2 + (3 * w / 4) + (r * Math.cos(Math.PI / 4)), y: y2 + (r * Math.sin(-Math.PI / 4))},
            p4 = {x: x2 + (3 * w / 4) - (r * Math.cos(Math.PI / 4)), y: y2 + (r * Math.sin(Math.PI / 4))};
        var points = [p1, p2, p3, p4];
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(p1.x, p1.y);
        ctx.lineTo(p2.x, p2.y);
        ctx.lineTo(p3.x, p3.y);
        ctx.lineTo(p4.x, p4.y);
        ctx.lineTo(p1.x, p1.y);
        for (var i in points) {
            var point = points[i];
            ctx.moveTo(x1, y1);
            ctx.lineTo(point.x, point.y);
        }
        ctx.lineWidth = size;
        ctx.strokeStyle = color;
        ctx.stroke();
        ctx.closePath();
    }

    /**
     * ======================================================
     * *************** function for x-graph animation
     * =====================================================
     */
    /**
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param color
     * @param size
     */
    function drawXGraphAnimation(x1, y1, x2, y2, color, size, noAnimation) {
        var noAnimation = noAnimation ? noAnimation : false;
        var w = Math.ceil(Math.abs(x2 - x1));
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        if (noAnimation) {
            var pointsArray = {};
            var i = x2 > x1 ? x1 : x2;
            var endP = ( x2 > x1 ? x2 : x1) + 2;
            var factor = w / 10;
            var counter = 0;
            while (i <= endP) {
                pointsArray[counter]={x: Math.floor(i), y: y1};
                i += factor;
               if(counter>10)
                    break;
                counter++;
            }

            for (var j in pointsArray) {

                var point = pointsArray[j];
                ctx.beginPath();
                ctx.globalCompositeOperation = "source-over";
                ctx.moveTo(point.x, point.y - 5);
                ctx.lineTo(point.x, point.y + 5);
                ctx.strokeStyle = color;
                ctx.lineWidth = 3;
                ctx.stroke();
                ctx.closePath();
                ctx.font = 'normal 14px ' + font;
                ctx.fillStyle = color;
                ctx.fillText(j * 10, point.x - 3, point.y + 14);
                ctx.closePath();
            }
        }
        var startX = x1 + (x2 > x1 ? -10 : 10),
            endX = x2 + (x2 > x1 ? 10 : -10);

        drawArrow(ctx, startX, y1, endX, y1, color, size);

        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(startX, y1);
        ctx.lineTo(endX, y1);
        ctx.strokeStyle = color;
        ctx.lineWidth = size;
        ctx.stroke();
        ctx.closePath();
        drawArrow(ctx, endX, y1, startX, y1, color, size);
        pointsArray = {};
    }

    /**
     * ======================================================
     *
     * *************** function for xy-graph animation
     * =====================================================
     */
    /**
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     * @param noAnimation
     * @param color
     * @param size
     */
    function drawXYGraphAnimation(x1, y1, x2, y2, color, size, noAnimation) {
        var noAnimation = noAnimation ? noAnimation : false;
        var w = Math.ceil(Math.abs(x2 - x1));
        var h = Math.ceil(Math.abs(y2 - y1));
        var ctx = noAnimation ? drawingCanvas : fakeCanvas;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);


        var startX = x1,
            endX = x2;
        var startY = y1, endY = y2;

        var dx = Math.abs(startX - endX),
            dy = Math.abs(startY - endY);

        //draw rectainge
        drawRectangleAnimation(x1, y1, x2, y2, color, size, noAnimation);


        //find midpoints

        var mx = x2 > x1 ? (x1 + (dx / 2)) : x2 + (dx / 2),
            my = y2 > y1 ? (y1 + (dy / 2)) : y2 + (dy / 2);


        //draw horizontal line
        drawArrow(ctx, startX, my, ctx, endX, my);
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(startX, my);
        ctx.lineTo(endX, my);
        ctx.strokeStyle = color;
        ctx.lineWidth = size;
        ctx.stroke();
        ctx.closePath();
        drawArrow(ctx, endX, my, startX, my);

        //draw vertical line

        //draw horizontal line
        drawArrow(ctx, mx, startY, mx, endY);
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.moveTo(mx, startY);
        ctx.lineTo(mx, endY);
        ctx.strokeStyle = color;
        ctx.lineWidth = size;
        ctx.stroke();
        ctx.closePath();
        drawArrow(ctx, mx, endY, mx, startY);

        if (noAnimation) {
            //draw vertical lines
            var pointsArrayX = {};

            var counterx = 0;
            var i = (x2 > x1 ? x1 : x2) + 10,
                endPX = ( x2 > x1 ? x2 : x1);
            while (i <= endPX) {
                var factor = (w / 20) - 1;
                pointsArrayX[counterx]={x: Math.floor(i), y: my};
                i += factor;
                if(counterx>20)
                    break;
                counterx++;
            }

            for (var j in pointsArrayX) {
                var point = pointsArrayX[j];
                ctx.beginPath();
                ctx.globalCompositeOperation = "source-over";
                ctx.moveTo(point.x, point.y - dy / 2);
                ctx.lineTo(point.x, point.y + dy / 2);
                ctx.lineWidth = 1;
                ctx.strokeStyle = graphColor;
                ctx.stroke();
                ctx.closePath();


                ctx.beginPath();
                ctx.globalCompositeOperation = "source-over";
                if (j % 2 == 0) {

                    ctx.moveTo(point.x, point.y - 5);
                    ctx.lineTo(point.x, point.y + 5);
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 3;
                    ctx.stroke();
                    ctx.closePath();
                    ctx.font = 'normal 12px ' + font;
                    ctx.fillStyle = color;
                    ctx.fillText(j - 10, point.x - 3, point.y + 20);
                    ctx.closePath();
                } else {
                    ctx.moveTo(point.x, point.y - 2);
                    ctx.lineTo(point.x, point.y + 2);
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 3;
                    ctx.stroke();
                    ctx.closePath();
                }


            }
            pointsArrayX ={};

            //draw horizontal lines
            var pointsArrayY = {};
            var countery = 0;
            var k = (y2 > y1 ? y1 : y2) + 10,
                endPY = ( y2 > y1 ? y2 : y1);
            while (k <= endPY) {
                var factor = (h / 20) - 1;
                pointsArrayY[countery]={x: mx, y: Math.floor(k)};
                k += factor;
                if(countery>20)
                    break;
                countery++;
            }

            for (var l in pointsArrayY) {
                var point = pointsArrayY[l];
                ctx.beginPath();
                ctx.globalCompositeOperation = "source-over";
                ctx.moveTo(point.x + w / 2, point.y);
                ctx.lineTo(point.x - w / 2, point.y);
                ctx.lineWidth = 1;
                ctx.strokeStyle = graphColor;
                ctx.stroke();
                ctx.closePath();


                ctx.beginPath();
                ctx.globalCompositeOperation = "source-over";
                if (l % 2 == 0) {

                    ctx.moveTo(point.x - 5, point.y);
                    ctx.lineTo(point.x + 5, point.y);
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 3;
                    ctx.stroke();
                    ctx.closePath();
                    ctx.font = 'normal 12px ' + font;
                    ctx.fillStyle = color;
                    ctx.fillText(10 - l, point.x - 20, point.y + 3);
                    ctx.closePath();
                } else {
                    ctx.moveTo(point.x - 2, point.y);
                    ctx.lineTo(point.x + 2, point.y);
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 3;
                    ctx.stroke();
                    ctx.closePath();
                }


            }

        }

    }


    /**
     * method to draw arrow
     * @param context
     * @param fromx
     * @param fromy
     * @param tox
     * @param toy
     */
    function drawArrow(context, fromx, fromy, tox, toy, color, size) {
        var x_center = tox;
        var y_center = toy;

        var angle;
        var x;
        var y;
        var r = lineSize > 1 ? (4 * lineSize) : 8;

        context.beginPath();
        context.globalCompositeOperation = "source-over";
        angle = Math.atan2(toy - fromy, tox - fromx)
        context.moveTo(x_center, y_center);

        angle += (1 / 6) * (4 * Math.PI);
        x = r * Math.cos(angle) + x_center;
        y = r * Math.sin(angle) + y_center;

        context.lineTo(x, y);
        context.strokeStyle = color;
        context.lineWidth = size;
        context.stroke();
        context.closePath();

        context.beginPath();
        context.globalCompositeOperation = "source-over";
        context.moveTo(x_center, y_center);
        angle += (1 / 6) * (4 * Math.PI);
        x = r * Math.cos(angle) + x_center;
        y = r * Math.sin(angle) + y_center;
        context.lineTo(x, y);
        context.strokeStyle = color;
        context.lineWidth = size;
        context.stroke();
        context.closePath();


    }


    //print canvas
    $('#print-data').click(function (e) {
        e.preventDefault();
        $('#print-modal').modal('hide');
        var printValue = $('input[name="print_option"]:checked').val();
        var printMode = $('input[name="print_mode"]:checked').val();
        var printContent = '';
        let style  = '';
        if(printMode=='landscape'){
            style = '<style type="text/css" >\n' +
                '  @media print{@page {size: landscape}}\n' +
                '</style>'
        }

        if(printValue=='current-slide' || printValue=='all-slides'){
            var dataUrl = drawingC.toDataURL();
             printContent = '<!Doctype html>' +
                '<html>' +
                '<head><title></title>' +
                 style+
                 '</head>' +
                '<body>' +
                '<img src="' + dataUrl + '">' +
                '</body>' +
                '</html>';
        }else{
            let chatContent = $('.chat-room').html();
            printContent = '<!Doctype html>' +
                '<html>' +
                '<head><title></title>' +
                style+
                '</head>' +
                '<body>' +
                chatContent
                '</body>' +
                '</html>';
        }

        var printWindow = window.open('', '', width = $('#drawing-board').width(), height = $('#drawing-board').height());
        printWindow.document.write(printContent);
        printWindow.document.addEventListener('load', function () {
            setTimeout(function(){
                printWindow.focus();
                printWindow.print();
                printWindow.document.close();
                printWindow.close();
            },500);

        }, true)

    });

    $('#color-spectrum').mouseover(function () {
        symbolEnabled = true;
    }).spectrum({
        showPalette: true,
        color: '#000',
        preferredFormat: "hex3",
        showInput: true,
        palette: [
            ["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
            ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
            ["#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d0e0e3", "#cfe2f3", "#d9d2e9", "#ead1dc"],
            ["#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
            ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"],
            ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
            ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
            ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"]
        ],
        move: function (color) {
            var c = color.toHexString();

            $('#color-indicator-sp').css('background', c);
        },
        change: function (color) {
            $('.sp-container').css('display', 'none');
            currentColor = color.toHexString();
            $('#color-indicator').css('background', currentColor);
            if (currentTool == 'text') {
                if (textHolder.text().trim().length < 1) {
                    textHolder.css('color', currentColor);
                    textHolder[0].focus();
                } else {
                    pasteHtmlAtCaret("<span style='color:" + currentColor + ";'>&#8203;</span>", textHolder[0]);
                }
            }
        }
    });


    $('#paste-tool').click(function () {
        currentTool = 'paste';
        $('.js-tools').removeClass('active')
        $(this).addClass('active');
    });

    /**
     *
     * @param dom
     * @param callback
     */
    function insertSymbols(callback) {
        var latex = mathEditor.getLatex();
        //$.get(',function(response){
        domtoimage.toSvg($('#equation-editor-wrapper .mq-root-block')[0])
            .then(function (dataUrl) {
                var img = new Image();
                img.onload = function () {
                    img.crossOrigin = "Anonymous";
                    //var actualWidth = img.width;
                    // var actualHeight = img.height;
                    // var factor = fontSize/35;
                    var height = img.height;
                    var width = img.width + 20;
                    var canvasHeight = drawingC.height;
                    var canvasWidth = drawingC.width;
                    var left = lineStartPoint.x + width;
                    var top = lineStartPoint.y + height;
                    if (left > canvasWidth) {
                        parentDiv.scrollLeft(left);
                        rC.width = canvasWidth;
                        rC.height = canvasHeight;
                        resizeCanvas.drawImage(drawingC, 0, 0);
                        dc.attr('width', left + 20);
                        fa.attr('width', left + 20);
                        drawingCanvas.drawImage(rC, 0, 0);
                    }

                    if (top > canvasHeight) {
                        parentDiv.scrollLeft(top);
                        rC.width = canvasWidth;
                        rC.height = canvasHeight;
                        resizeCanvas.drawImage(drawingC, 0, 0);
                        dc.attr('height', top);
                        fa.attr('height', top);
                        drawingCanvas.drawImage(rC, 0, 0);
                    }
                    var pointX = lineStartPoint.x + (textDivWidth - 30);
                    var pointY = lineStartPoint.y - height / 3 + 10;

                    drawingCanvas.drawImage(img, pointX, pointY, width, height);
                    saveCanvasObjects('image', {
                        startX: pointX,
                        startY: pointY,
                        endX: pointX + width + 2,
                        endY: pointY - 2 + height,
                        image: dataUrl
                    });
                    return callback();
                };
                //img.src = 'http://www.wiris.net/demo/editor/render?format=svg&latex='+latex;
                img.src = dataUrl;
            })
            .catch(function (error) {
                //console.error('oops, something went wrong!', error);
            });
        //});

    }

    /**
     * @param x
     * @param y
     * @param callback
     */

    function writeTextDivToCanvas(x, y, callback) {

        var styles = textHolder.attr('style');
        var htmlString = textHolder.html();
        if (!htmlString)
            return callback();
        if (htmlString.trim().length < 1)
            return callback();
        setTimeout(function(){
        htmlString = htmlString.replace(/&#8203;/g, ' ');

        var height = parseInt(textHolder.height()) + 20;
        var width = textHolder.width() + 30;
        var svgData = '<svg xmlns="http://www.w3.org/2000/svg" width="' + width + '" height="' + height + '">' +
            '<foreignObject width="100%" height="100%">' +
            '<div xmlns="http://www.w3.org/1999/xhtml" style="' + styles + '">' +
            htmlString +
            '</div>' +
            '</foreignObject>' +
            '</svg>';
        var data = encodeURIComponent(svgData);
        var img = new Image();
        img.onload = function () {
            var width = img.width;
            textDivWidth = img.width;
            var height = img.height;
            var canvasHeight = drawingC.height;
            var canvasWidth = drawingC.width;
            var left = x + width;
            var top = y + height;
            if (left > canvasWidth) {
                parentDiv.scrollLeft(left);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('width', left);
                fa.attr('width', left);
                drawingCanvas.drawImage(rC, 0, 0);
            }
            if (top > canvasHeight) {
                parentDiv.scrollLeft(top);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('height', top);
                fa.attr('height', top);
                drawingCanvas.drawImage(rC, 0, 0);
            }
            drawingCanvas.drawImage(img, x, y + 2);
            var cssObj = {
                'color': textHolder.css('color'),
                'font-size': textHolder.css('font-size'),
                'font-family': textHolder.css('font-family'),
                'font-weight': textHolder.css('font-weight'),
                'font-style': textHolder.css('font-style')
            };
            saveCanvasObjects('image-text', {
                textLeftCord: x,
                textTopCord: y + 2,
                startX: x - 10,
                startY: y - 10,
                endX: x + img.width + 2,
                endY: y - 40 + img.height,
                image: "data:image/svg+xml," + data,
                html: textHolder.html(),
                cssObj: cssObj
            });

            return callback();
        };
        img.src = "data:image/svg+xml," + data
        },100);
    }


    //undo the canvas state

    $('#undo-tool').click(function (e) {
        textHolder.blur();
        //textInput.val('');
        $enableTextTool.removeClass('js-tools border');
        canvasObjects.splice(canvasObjects.length - 1);
        redrawCanvas();
        if(user.userType=='tutor'){
            socket.emit('redraw-foreign',{data:canvasObjects,receiver:receiver});
        }
        $enableTextTool.click();
    });

    $('#new-board').click(function () {
        textEnabled = false;
        textHolder.blur();
        drawingCanvas.clearRect(0, 0, drawingC.width, drawingC.height);
        drag = [];
        currentColor = '#000';
        lineSize = 2;
        font = 'serif';
        fontSize = 18;
        fontStyle = 'normal';
        textHolder.css({'font-size': fontSize, 'color': currentColor, 'font-style': 'normal', 'font-weight': 'normal'});
        dc.attr({'height': parentHeight - 8, 'width': parentWidth - 5});
        fa.attr({'height': parentHeight - 8, 'width': parentWidth - 5});
        pencilPoints = [];

        $enableTextTool.click();
        $('#color-indicator').css('background', '#000');
        if(user.userType=='tutor'){
            if(canvasObjects.length>1){
                canvasStates.push(canvasObjects);
                currentStateIndex = canvasStates.length;
            }

        }

        canvasObjects = [];
        foreignCanvasData = [];
        redrawCanvas();
        if(user.userType=='tutor' && $onlineUsers.find('li.active').length>0){
            var rec = $onlineUsers.find('li.active').find('.js-online-users').data().user;
            socket.emit('redraw-canvas', {receiver: rec,type:'new-board'});
        }

    });
    if(user.userType=='tutor'){
        $('#canvas-next-state').click(function(e){
            e.preventDefault();

            currentStateIndex++;
            if(currentStateIndex<canvasStates.length){

                if(canvasObjects && canvasObjects.length>0 && isNewDrawing){
                    isNewDrawing = false;
                    canvasStates.push(canvasObjects);
                };
                canvasObjects = canvasStates[currentStateIndex];
               redrawCanvas();
                streamCanvasDrawing(canvasObjects, false,'redraw-foreign',lineEndPoint);
            }else{
                currentStateIndex = canvasStates.length-1;
            }
        });

        $('#canvas-back-state').click(function(e){
            e.preventDefault();
            currentStateIndex--;

            if(currentStateIndex>=0){

                if(canvasObjects && canvasObjects.length>0 && isNewDrawing){
                    isNewDrawing = false;
                    canvasStates.push(canvasObjects);
                }
                canvasObjects = canvasStates[currentStateIndex];
                streamCanvasDrawing(canvasObjects, false,'redraw-foreign',lineEndPoint);
                redrawCanvas();
            }else{
                currentStateIndex =0;
            }
        });
    }else{
        $('#canvas-next-state,#canvas-back-state').hide();
    }

    /**
     * method to save canvas states
     * @param shape
     * @param data
     */
    function saveCanvasObjects(shape, data) {
        if(!canvasObjects)
            canvasObjects = [];
        canvasObjects.push({shape: shape, data: data});
        isNewDrawing = true;
        streamCanvasDrawing([{shape: shape, data: data}], publicModeEnabled,false,lineEndPoint);
    }

    /**
     * @param x
     * @param y
     * @returns {boolean}
     */
    function initializeDrag(x, y) {
        fa.show();
        dragDiv.show();
        for (var i = 0; i < canvasObjects.length; i++) {

            var canvasShape = canvasObjects[i];
            if (!canvasShape)
                continue;
            if (!canvasShape.data)
                continue;

            var shapeData = canvasShape.data;

            var x1, y1, x2, y2;
            if (canvasShape.shape === 'pencil') {
                var pPoints = shapeData.minMaxPoints;
                x1 = pPoints.minX;
                x2 = pPoints.maxX + 5;
                y1 = pPoints.minY;
                y2 = pPoints.maxY + 5;
            }
            else {
                x1 = Math.min(shapeData.startX, shapeData.endX);
                x2 = Math.max(shapeData.startX, shapeData.endX);
                y1 = Math.min(shapeData.startY, shapeData.endY);
                y2 = Math.max(shapeData.startY, shapeData.endY);
                dx = x2 - x1;
                dy = y2 - y1;
                if (dy < 10) {
                    x1 = x1 - 15;
                    x2 = x2 + 15;
                }
                if (dx < 10) {
                    y1 = y1 - 15;
                    y2 = y2 + 15;
                }
                if (canvasShape.shape === 'cube') {
                    x1 = x1 - dx / 2;
                    y1 = y1 - dy;
                    x2 = x2 + (2 * dx);

                } else if (canvasShape.shape === 'cone') {
                    x1 = x1 - dx;
                } else if (canvasShape.shape === 'pyramid') {
                    x1 = x1 - dx;
                    x2 = x2 + (2.5 * dx / 2);
                    y2 = y2 + dy / 4;
                } else if (canvasShape.shape === 'xgraph') {
                    y1 = y1 - 30;
                    y2 = y2 + 30;
                }
            }


            if (x >= x1 - 15 && x <= x2 + 15 && y >= y1 - 15 && y <= y2 + 15) {
                draggingShape = canvasShape;
                draggingShape.rectArea = {x1: x1 - 5, y1: y1 - 5, x2: x2 + 5, y2: y2 + 5};
                canvasObjects.splice(i, 1);//remove that objects from canvas;
                drawMultipleShapes(canvasShape, false, true);
                var dx = x2 - x1, dy = y2 - y1;
                if (canvasShape.shape !== 'image' && canvasShape.shape !== 'image-text' && canvasShape.shape !== 'line') {
                    draggingShape.img = fakeCanvas.getImageData(draggingShape.rectArea.x1, draggingShape.rectArea.y1, dx, dy + 20);
                }

                return true;
            }
        }
        return false;
    }

    //{startX:lineStartPoint.x,startY:lineStartPoint.y,endX:left,endY:top,shift:shiftPressed,color:currentColor,lineSize:lineSize});

    /**
     * function to redraw canvas
     */
    function redrawCanvas() {
        drawingCanvas.clearRect(0, 0, drawingC.width, drawingC.height);
        if(canvasObjects) {

            for (var i = 0; i < canvasObjects.length; i++) {

                var canvasShape = canvasObjects[i];
                if (!canvasShape)
                    continue;
                if (!canvasShape.data)
                    continue;
                drawMultipleShapes(canvasShape, true);

            }
        }
        if(foreignCanvasData) {
            for (var i = 0; i < foreignCanvasData.length; i++) {

                var canvasShape = foreignCanvasData[i];
                if (!canvasShape)
                    continue;
                if (!canvasShape.data)
                    continue;
                drawMultipleShapes(canvasShape, true);
            }
        }

    }

    /**
     * function to show the drag effect
     */
    function showDragUIAnimation(x, y) {

        if (!draggingShape || !draggingShape.rectArea)
            return false;
        var dragX = x - lineStartPoint.x;
        var dragY = y - lineStartPoint.y;
        var dx = draggingShape.rectArea.x2 - draggingShape.rectArea.x1;
        var dy = draggingShape.rectArea.y2 - draggingShape.rectArea.y1;

        //draw outer layer
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);

        if (draggingShape.shape == 'image' || draggingShape.shape == 'image-text') {
            var l = draggingShape.data.textLeftCord;
            var t = draggingShape.data.textTopCord;
            if (draggingShape.shape == 'image') {
                l = draggingShape.data.startX;
                t = draggingShape.data.startY;
            }
            var img = new Image();
            img.onload = function () {
                fakeCanvas.drawImage(img, l + dragX, t + dragY);
            };
            img.src = draggingShape.data.image;
        } else if (draggingShape.shape == 'line') {
            drawLineAnimation(draggingShape.data.startX + dragX, draggingShape.data.startY + dragY, draggingShape.data.endX + dragX, draggingShape.data.endY + dragY, draggingShape.data.color, draggingShape.lineSize)
        } else {
            fakeCanvas.putImageData(draggingShape.img, draggingShape.rectArea.x1 + dragX, draggingShape.rectArea.y1 + dragY)
        }
        dragDiv.css({
            left: draggingShape.rectArea.x1 + dragX - 10,
            top: draggingShape.rectArea.y1 + dragY - 10,
            width: dx + 30,
            height: dy + 30
        });

    }

    /**
     *
     * @param canvasShape
     * @param noAnimation
     * @param isFirst
     */
    function drawMultipleShapes(canvasShape, noAnimation, isFirst) {
        var shapeData = canvasShape.data;
        currentColor = shapeData.color;
        lineSize = shapeData.lineSize;
        var shape = canvasShape.shape;
        var isFirst = isFirst ? isFirst : false;
        if (shape == 'pencil') {
            pencilPoints = shapeData.pencilPoints;
            drawPencil(noAnimation ? drawingCanvas : fakeCanvas, shapeData.color, shapeData.lineSize);
        } else if (shape == 'line') {
            shiftPressed = shapeData.shift;
            drawLineAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'cube') {
            drawCubeAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'rectangle') {
            shiftPressed = shapeData.shift;
            drawRectangleAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'oval') {
            shiftPressed = shapeData.shift;
            drawCircleAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'cylinder') {
            drawCylinderAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'cone') {
            drawConeAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'pyramid') {
            drawPyramidAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'xgraph') {
            drawXGraphAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'xygraph') {
            drawXYGraphAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation);
        } else if (shape == 'rectangle-filled') {
            drawRectangleAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation, true);
        } else if (shape == 'oval-filled') {
            drawCircleAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation, true);
        } else if (shape == 'line-sarrow') {
            shiftPressed = shapeData.shift;
            drawLineAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation, 'single');
        } else if (shape == 'line-darrow') {
            shiftPressed = shapeData.shift;
            drawLineAnimation(shapeData.startX, shapeData.startY, shapeData.endX, shapeData.endY, shapeData.color, shapeData.lineSize, noAnimation, 'double');
        } else if (!isFirst && (shape == 'image' || shape == 'image-text')) {
            var ctx = noAnimation ? drawingCanvas : fakeCanvas;
            var l = shapeData.textLeftCord;
            var t = shapeData.textTopCord;
            if (shape == 'image') {
                l = shapeData.startX;
                t = shapeData.startY;
            }
            var img = new Image();
            img.onload = function () {

                ctx.drawImage(img, l, t);
            };
            img.src = shapeData.image;
        } else if (shape == 'eraser') {
            shapeData.forEach(function (d) {
                drawingCanvas.beginPath();
                drawingCanvas.globalCompositeOperation = "destination-out";
                drawingCanvas.arc(d.left, d.top, d.eraserSize, 0, Math.PI * 2, false);
                drawingCanvas.fill();
                drawingCanvas.globalCompositeOperation = 'source-over';
            });
        }
    }

    function drawShapeAgain() {
        if (!draggingShape)
            return false;
        if (!draggingShape.data)
            return false;

        var dx = lineEndPoint.x - lineStartPoint.x;
        var dy = lineEndPoint.y - lineStartPoint.y;
        fakeCanvas.clearRect(0, 0, fakeCanvasMaxLenght, fakeCanvasMaxLenght);
        var shapeData = draggingShape.data;
        currentColor = shapeData.color;
        lineSize = shapeData.lineSize;
        var shape = draggingShape.shape;
        if (shape === 'pencil') {

            var newMXY = [];
            for (var i in shapeData.pencilPoints) {
                var sdx = shapeData.pencilPoints[i].x + dx;
                var sdy = shapeData.pencilPoints[i].y + dy;
                if (sdx && sdy)
                    newMXY.push({x: sdx, y: sdy});
            }
            var minMaxPoints = draggingShape.data.minMaxPoints;
            minMaxPoints.minX = minMaxPoints.minX + dx;
            minMaxPoints.maxX = minMaxPoints.maxX + dx;
            minMaxPoints.minY = minMaxPoints.minY + dy;
            minMaxPoints.maxY = minMaxPoints.maxY + dy;
            draggingShape.data.pencilPoints = newMXY;
            draggingShape.data.minMaxPoints = minMaxPoints;
            drawMultipleShapes(draggingShape, true);
        } else {
            if (shape === 'image-text') {
                draggingShape.data.textLeftCord = draggingShape.data.textLeftCord + dx;
                draggingShape.data.textTopCord = draggingShape.data.textTopCord + dy;
            }
            draggingShape.data.startX = draggingShape.data.startX + dx;
            draggingShape.data.startY = draggingShape.data.startY + dy;
            draggingShape.data.endX = draggingShape.data.endX + dx;
            draggingShape.data.endY = draggingShape.data.endY + dy;
            drawMultipleShapes(draggingShape, true);
        }
        canvasObjects.push(draggingShape);
        draggingShape = {};
    }

    //check for text edit option
    function checkTextEdit(x, y) {
        if(canvasObjects) {
            for (var i = 0; i < canvasObjects.length; i++) {
                var canvasShape = canvasObjects[i];
                if (canvasShape.shape !== 'image-text')
                    continue;
                var shapeData = canvasShape.data;
                x1 = Math.min(shapeData.startX, shapeData.endX);
                x2 = Math.max(shapeData.startX, shapeData.endX);
                y1 = Math.min(shapeData.startY, shapeData.endY);
                y2 = Math.min(shapeData.startY, shapeData.endY);

                dx = x2 - x1;
                dy = y2 - y1;
                if (x >= x1 - 20 && x <= x2 + 20 && y >= y1 - 20 && y <= y2 + 20) {
                    canvasObjects.splice(i, 1);
                    redrawCanvas();
                    return {
                        left: shapeData.textLeftCord,
                        top: shapeData.textTopCord,
                        html: shapeData.html,
                        cssObj: shapeData.cssObj
                    };
                }
            }
        }
        return false;
    }

    //enable public mode
    $('.js-public-mode').click(function () {
        var $this = $(this);
        if ($this.hasClass('active')) {
            $this.removeClass('active')
            publicModeEnabled = false;
            foreignCanvasData = [];
            canvasObjects =[];
            redrawCanvas();
            if(user.userType=='tutor'){
                $.ajax({
                    type : 'post',
                    url : herokoUrl+'unset-public-drawing',
                    data : user,
                    success : function (data){

                    }
                });

            }
        } else {
            if(user.userType=='student'){

                checkPublicMethodEnabled(function(data){
                    if(data.status){
                        $this.addClass('active');
                        publicModeEnabled = true;
                        foreignCanvasData = [];
                        canvasObjects =[];
                        redrawCanvas();
                    }else{
                        alert('Sorry currently no public option avilable');
                    }
                });
            }else{
                $.ajax({
                    type : 'post',
                    url : herokoUrl+'set-public-drawing',
                    data : user,
                    global:false,
                    crossDomain : true,
                    success : function (data){
                        if(data.status){
                            $this.addClass('active');
                            publicModeEnabled = true;
                            foreignCanvasData = [];
                            canvasObjects =[];
                            redrawCanvas();
                        }else{
                            alert('Sorry You are currently Offline. Please refresh the page');
                        }

                    }
                })

            }

        }
    });

    socket.on('get-public-drawing', function (data) {
        if (!publicModeEnabled){
            return false;
        }

        if (data.user.ObjectID!=user.ObjectID && data.hasOwnProperty('canvasData')) {

            lineEndPoint = data.xy;
            var left = lineEndPoint.x;
            var top = lineEndPoint.y;
            var canvasHeight = drawingC.height;
            var canvasWidth = drawingC.width;

            if (left > canvasWidth) {
                parentDiv.scrollLeft(left);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('width', left);
                fa.attr('width', left);
                drawingCanvas.drawImage(rC, 0, 0);
            }
            if (top > canvasHeight) {
                parentDiv.scrollLeft(top);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('height', top);
                fa.attr('height', top);
                drawingCanvas.drawImage(rC, 0, 0);
            }
            for (var i in data.canvasData) {
                foreignCanvasData.push(data.canvasData[i]);
                drawMultipleShapes(data.canvasData[i],true);
            }

        }
    });

    socket.on('get-private-drawing', function (data) {
        //console.log(data);
        if (publicModeEnabled){
            return false;
        }
        if(user.userType=='tutor' && data.user.ObjectID !=currentStudentID){
            return false;
        }

        if (data.user.ObjectID!=user.ObjectID && data.hasOwnProperty('canvasData')) {
            lineEndPoint = data.xy;
            var left = lineEndPoint.x;
            var top = lineEndPoint.y;
            var canvasHeight = drawingC.height;
            var canvasWidth = drawingC.width;

            if (left > canvasWidth) {
                parentDiv.scrollLeft(left);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('width', left);
                fa.attr('width', left);
                drawingCanvas.drawImage(rC, 0, 0);
            }
            if (top > canvasHeight) {
                parentDiv.scrollLeft(top);
                rC.width = canvasWidth;
                rC.height = canvasHeight;
                resizeCanvas.drawImage(drawingC, 0, 0);
                dc.attr('height', top);
                fa.attr('height', top);
                drawingCanvas.drawImage(rC, 0, 0);
            }

            if(data.redrawForeign=='redraw-foreign'){

                foreignCanvasData = data.canvasData;
                redrawCanvas();
            }else{
                for (var i in data.canvasData) {
                    foreignCanvasData.push(data.canvasData[i]);
                    drawMultipleShapes(data.canvasData[i],true);
                }
            }


        }
    });

    socket.on('req-for-drawing-update', function(){
       streamCanvasDrawing(canvasObjects,publicModeEnabled,false,lineEndPoint);
    });

    socket.on('force-redraw', function(data){
        if(user.userType=='student'){
            if(data.type == 'new-board'){
                foreignCanvasData =[];
            }
            canvasObjects =[];
            redrawCanvas();
        }
    });
    socket.on('undo-foreign',function(data){
        foreignCanvasData = data;
        redrawCanvas();
    });
    $(document).on('click', '.js-online-users', function () {
        if (!$(this).hasClass('already-selected')){
            $('.js-online-users').removeClass('already-selected');
            $(this).addClass('already-selected');
            canvasObjects = [];
            foreignCanvasData = [];
            redrawCanvas();
            var rec = $(this).data().user;
            socket.emit('req-student-drawing',{receiver:rec});
        }

    });


    $(document).on('click','.js-clear-std-board',function(e){
        e.preventDefault();

        if(user.userType=='tutor'){
            var rec = $(this).parent().find('.js-online-users').data().user;
            socket.emit('redraw-canvas', {receiver: rec,type:'std-board'});
            foreignCanvasData = [];
            redrawCanvas();
        }


    });
}


function getCoords(elem) {
    var box = elem.getBoundingClientRect();
    var scrollLeft = $(elem).parent().scrollLeft();
    var scrollTop = $(elem).parent().scrollTop();
    var body = document.body;
    var docEl = document.documentElement;

    var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
    var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

    var clientTop = docEl.clientTop || body.clientTop || 0;
    var clientLeft = docEl.clientLeft || body.clientLeft || 0;
    var top = box.top + scrollTop - clientTop;
    var left = box.left + scrollLeft - clientLeft;
    return {top: Math.round(top), left: Math.round(left)};

}


function pasteHtmlAtCaret(html, el) {

    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.setStart(lastNode, 1)
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }


}








