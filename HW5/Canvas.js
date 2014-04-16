/************************************************************
 * Onload part
 ************************************************************/
// for panel
var canvas = document.getElementById("panel");
var context = canvas.getContext("2d");
// for preview screen
var preview = document.getElementById("previewScreen");
var previewContext = preview.getContext("2d");

// for mouse event
var drawMode, onDraw, mouseX, mouseY;
var startX, startY;

// for color panel
var currentColor = '#88a8ff';
var currentSize = 10;
var eraserColor = 'rgba(0,0,0,1)';
var currentRenderSize = 4;


// initial panel
window.onload = reset();

function reset() {
    canvasReset();
    document.getElementById('colorbar').value = '#88a8ff';
    previewScreenReset();
    changeDrawMode("pencil");
    initCache();
}

/************************************************************
 * Undo and redo part
 ************************************************************/
// for Undo event
var undoStack;
var undoSize = 30;

function initCache() {
    var tmpCanvas = document.createElement("canvas");
    document.body.appendChild(tmpCanvas);
    tmpCanvas.width = $(document).width() * 0.8;
    tmpCanvas.height = $(document).height();
    var tmpContext = tmpCanvas.getContext("2d");
    tmpContext.fillstyle = 'rgba(0, 0, 0, 0)';
    undoStack = new Array(tmpCanvas.toDataURL());
    document.body.removeChild(tmpCanvas);
}

function addCache() {
    undoStack.push(canvas.toDataURL());
    if (undoStack.length > undoSize) {
        undoStack.shift();
    }
}

function undo() {
    undoStack.pop();
    reDraw();
}

function reDraw() {
    canvasReset();
    var tmpImg = new Image();
    tmpImg.src = undoStack[undoStack.length - 1];
    context.drawImage(tmpImg, 0, 0);
}

document.onkeydown = function(event) {
    if (event.ctrlKey && event.keyCode == 90) {
        undo();
    }
};

/************************************************************
 * Button part
 ************************************************************/
// Pencil icon focus
var pencilIconAnimation = document.getElementById('toolbuttonPencil');
pencilIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
pencilIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
pencilIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};

// Brush icon focus
var brushIconAnimation = document.getElementById('toolbuttonBrush');
brushIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
brushIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
brushIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};

// Eraser icon focus
var eraserIconAnimation = document.getElementById('toolbuttonEraser');
eraserIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
eraserIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
eraserIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};

// PT icon focus
var PTIconAnimation = document.getElementById('toolbuttonPT');
PTIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
PTIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
PTIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};

// Save icon focus
var SaveIconAnimation = document.getElementById('toolbuttonSave');
SaveIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
SaveIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
SaveIconAnimation.onmouseout = function() {
    this.style.backgroundColor = 'gray';
};

// Push icon focus
var PushIconAnimation = document.getElementById('toolbuttonPush');
PushIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
PushIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
PushIconAnimation.onmouseout = function() {
    this.style.backgroundColor = 'gray';
};

// PTline icon focus
var PTlineIconAnimation = document.getElementById('PTline');
PTlineIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
PTlineIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
PTlineIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};

// PTcircle icon focus
var PTcircleIconAnimation = document.getElementById('PTcircle');
PTcircleIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
PTcircleIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
PTcircleIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};

// PTrect icon focus
var PTrectIconAnimation = document.getElementById('PTrect');
PTrectIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
PTrectIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
PTrectIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};
/*
// PaintBucket icon focus
var PaintBucketIconAnimation = document.getElementById('toolbuttonPaintBucket');
PaintBucketIconAnimation.onmouseover = function() {
    this.style.backgroundColor = 'white';
};
PaintBucketIconAnimation.onmousedown = function() {
    this.style.backgroundColor = 'gray';
};
PaintBucketIconAnimation.onmouseout = function() {
    changeDrawMode(drawMode);
};
*/

function changeDrawMode(mode) {
    drawMode = mode;

    document.getElementById('RenderSetting').style.display = 'none';
    document.getElementById('PTSetting').style.display = 'none';

    if (mode == 'pencil') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = '#ACD6FF';
        document.getElementById('toolbuttonBrush').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonEraser').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonPT').style.backgroundColor = 'gray';
        previewScreenReset();
    } else if (mode == 'brush') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonBrush').style.backgroundColor = '#ACD6FF';
        document.getElementById('toolbuttonEraser').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonPT').style.backgroundColor = 'gray';
        document.getElementById('RenderSetting').style.display = 'block';
        previewScreenReset();
    } else if (mode == 'eraser') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonBrush').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonEraser').style.backgroundColor = '#ACD6FF';
        document.getElementById('toolbuttonPT').style.backgroundColor = 'gray';
    } else if (mode == 'line' || mode == 'circle' || mode == 'rect') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonBrush').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonEraser').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonPT').style.backgroundColor = '#ACD6FF';
        document.getElementById('PTSetting').style.display = 'block';
        document.getElementById('PTline').style.backgroundColor = 'gray';
        document.getElementById('PTcircle').style.backgroundColor = 'gray';
        document.getElementById('PTrect').style.backgroundColor = 'gray';
        if (mode == 'line') {
            document.getElementById('PTline').style.backgroundColor = '#ACD6FF';
        } else if (mode == 'circle') {
            document.getElementById('PTcircle').style.backgroundColor = '#ACD6FF';
        } else if (mode == 'rect') {
            document.getElementById('PTrect').style.backgroundColor = '#ACD6FF';
        }
    }
    /*
    else if (mode == 'paintbucket') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonBrush').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonEraser').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonPaintBucket').style.backgroundColor = '#ACD6FF';
    }
    */
}
/************************************************************
 * Canvas part
 ************************************************************/

canvas.onmousedown = function(event) {
    context.beginPath();
    context.strokeStyle = currentColor;
    context.lineWidth = currentSize;
    context.lineCap = "round";
    context.lineJoin = "round";

    if (drawMode == "pencil" || drawMode == "line" || drawMode == "circle" || drawMode == "rect") {
        context.shadowBlur = 0;
        context.shadowColor = currentColor;
    } else if (drawMode == "brush") {
        context.shadowBlur = currentRenderSize;
        context.shadowColor = currentColor;
    } else if (drawMode == "eraser") {
        context.shadowBlur = 1;
        context.shadowColor = currentColor;
    }

    var position = $("canvas:last").position();
    mouseX = event.clientX - position.left;
    mouseY = event.clientY - position.top;
    startX = mouseX;
    startY = mouseY;
    context.moveTo(mouseX, mouseY);
    onDraw = true;
};

canvas.onmousemove = function(event) {
    if (onDraw) {
        var tempCO = context.globalCompositeOperation;
        var position = $("canvas:last").position();
        mouseX = event.clientX - position.left;
        mouseY = event.clientY - position.top;
        if (drawMode == "pencil") {
            context.lineTo(mouseX, mouseY);
            context.stroke();
        } else if (drawMode == "eraser") {
            context.globalCompositeOperation = "destination-out";
            context.strokeStyle = eraserColor;
            context.lineTo(mouseX, mouseY);
            context.stroke();
        } else if (drawMode == "brush") {
            context.lineTo(mouseX, mouseY);
            context.stroke();
        } else if (drawMode == 'line') {
            reDraw();
            drawLine(mouseX, mouseY);
        } else if (drawMode == 'circle') {
            reDraw();
            drawCircle(mouseX, mouseY);
        } else if (drawMode == 'rect') {
            reDraw();
            drawRect(mouseX, mouseY);
        }
        context.globalCompositeOperation = tempCO;
    }
};

canvas.onmouseup = function(event) {
    onDraw = false;
    var position = $("canvas:last").position();
    mouseX = event.clientX - position.left;
    mouseY = event.clientY - position.top;
    if (drawMode == "line") {
        drawLine(mouseX, mouseY);
    } else if (drawMode == "circle") {
        drawCircle(mouseX, mouseY);
    } else if (drawMode == "rect") {
        drawRect(mouseX, mouseY);
    }
    addCache();
};

function drawLine(mouseX, mouseY) {
    context.beginPath();
    context.strokeStyle = currentColor;
    context.lineWidth = currentSize;
    context.lineCap = "round";
    context.moveTo(startX, startY);
    context.lineTo(mouseX, mouseY);
    context.stroke();
}

function drawCircle(mouseX, mouseY) {
    context.beginPath();
    context.strokeStyle = currentColor;
    context.lineWidth = currentSize;
    context.lineCap = "round";
    context.arc(startX, startY, Math.sqrt(Math.pow((mouseX - startX), 2) + Math.pow((mouseY - startY), 2)), 0, 2 * Math.PI, false);
    context.stroke();
}

function drawRect(mouseX, mouseY) {
    context.beginPath();
    context.strokeStyle = currentColor;
    context.lineWidth = currentSize;
    context.lineCap = "round";
    context.moveTo(startX, startY);
    context.lineTo(mouseX, startY);
    context.lineTo(mouseX, mouseY);
    context.lineTo(startX, mouseY);
    context.lineTo(startX, startY);
    context.stroke();
}

function canvasReset() {
    canvas.width = $(document).width() * 0.8;
    canvas.height = $(document).height();
    context.fillstyle = 'rgba(0, 0, 0, 0)';
}

function drawCanvasCircle(mouseX, mouseY, color) {
    context.beginPath();
    context.arc(startX, startY, Math.sqrt(Math.pow((mouseX - startX), 2) + Math.pow((mouseY - startY), 2)), 0, 2 * Math.PI, false);
    context.fillStyle = color;
    context.fill();
}

var count = 0,
    runtime;

function animationRuntime() {
    count++;
    rs = $("canvas:last").width() * 0.25;
    if (count == 1) {
        startX = $("canvas:last").width() * 0.25;
        startY = $("canvas:last").height() * 0.25;
        mouseX = startX + rs;
        mouseY = startY + rs;
        drawCanvasCircle(mouseX, mouseY, "#6bb2fb");
    } else if (count == 2) {
        startX = $("canvas:last").width() * 0.75;
        startY = $("canvas:last").height() * 0.25;
        mouseX = startX + rs;
        mouseY = startY + rs;
        drawCanvasCircle(mouseX, mouseY, "#63adf8");
    } else if (count == 3) {
        startX = $("canvas:last").width() * 0.75;
        startY = $("canvas:last").height() * 0.9;
        mouseX = startX + rs;
        mouseY = startY + rs;
        drawCanvasCircle(mouseX, mouseY, "#4499ef");
    } else if (count == 4) {
        startX = $("canvas:last").width() * 0.25;
        startY = $("canvas:last").height() * 0.9;
        mouseX = startX + rs;
        mouseY = startY + rs;
        drawCanvasCircle(mouseX, mouseY, "#78bbfe");
    } else if (count == 5) {
        var tmpCanvasLogo = new Image();
        tmpCanvasLogo.src = "res/CanvasPure.png";
        startX = $("canvas:last").width() * 0.5;
        startY = $("canvas:last").height() * 0.5;
        tmpCanvasLogo.onload = function() {
            context.drawImage(tmpCanvasLogo, startX - tmpCanvasLogo.width / 4, startY - tmpCanvasLogo.height / 4, tmpCanvasLogo.width / 2, tmpCanvasLogo.height / 2);
        };

    } else if (count == 6) {
        clearInterval(runtime);
        canvasReset();
        initCache();
    }
}

function canvasAnimation() {
    var tmpCanvas = document.createElement("canvas");
    document.body.appendChild(tmpCanvas);
    tmpCanvas.width = $(document).width() * 0.8;
    tmpCanvas.height = $(document).height();
    var tmpContext = tmpCanvas.getContext("2d");
    tmpContext.fillstyle = 'rgba(0, 0, 0, 0)';
    undoStack = new Array(tmpCanvas.toDataURL());
    document.body.removeChild(tmpCanvas);
    count = 0;
    runtime = setInterval("animationRuntime();", 500);
}
/************************************************************
 * Toolbar part
 ************************************************************/
document.getElementById('toolbuttonCanvas').onclick = function() {
    canvasAnimation();
};
document.getElementById('toolbuttonPencil').onclick = function() {
    changeDrawMode("pencil");
};
document.getElementById('toolbuttonBrush').onclick = function() {
    changeDrawMode("brush");
};
document.getElementById('toolbuttonEraser').onclick = function() {
    changeDrawMode("eraser");
};
document.getElementById('toolbuttonPT').onclick = function() {
    changeDrawMode("line");
};
document.getElementById('PTline').onclick = function() {
    changeDrawMode("line");
};
document.getElementById('PTcircle').onclick = function() {
    changeDrawMode("circle");
};
document.getElementById('PTrect').onclick = function() {
    changeDrawMode("rect");
};
/*
document.getElementById('toolbuttonPaintBucket').onclick = function() {
    changeDrawMode("paintbucket");
};
*/
document.getElementById('toolbuttonSave').onclick = function() {
    canvas2PNG();
};

// A easy way to save png file!!

function canvas2PNG() {
    var data = canvas.toDataURL("image/png");
    data = data.replace("image/png", "image/octet-stream");

    var downloadLink = document.createElement("a");
    downloadLink.href = data;
    downloadLink.download = "Canvas.png";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
/************************************************************
 * Detail part
 ************************************************************/

// Button of changing color
document.getElementById('colorPanel').onclick = function() {
    document.getElementById('color').click();
};

// Get the changing color when the color is change
document.getElementById('color').onchange = function() {
    currentColor = this.value;
    document.getElementById('colorbar').value = this.value;
    previewScreenReset();
};

// Get the changing color from color input
document.getElementById('colorbar').onchange = function() {
    currentColor = this.value;
    previewScreenReset();
};

// Get the changing size from size bar
document.getElementById('sizebar').onchange = function() {
    currentSize = this.value;
    document.getElementById('size').innerHTML = "Size: " + currentSize;
    previewScreenReset();
};

// Get Render size from Render bar
document.getElementById('Rendersizebar').onchange = function() {
    currentRenderSize = this.value;
    document.getElementById('Rendersize').innerHTML = "Render: " + (currentRenderSize - 4);
    previewScreenReset();
};

function previewScreenReset() {
    preview.width = $(document).width() * 0.15 * 0.9;
    preview.height = $(document).height() * 0.2;
    previewContext.fillstyle = 'rgba(0, 0, 0, 0)';
    previewContext.beginPath();
    previewContext.lineCap = "round";

    if (drawMode == "pencil") {
        previewContext.strokeStyle = currentColor;
        previewContext.lineWidth = currentSize;
        previewContext.shadowBlur = 0;
        previewContext.shadowColor = currentColor;
    } else if (drawMode == "brush") {
        previewContext.strokeStyle = currentColor;
        previewContext.lineWidth = currentSize;
        previewContext.shadowBlur = currentRenderSize;
        previewContext.shadowColor = currentColor;
    } else {
        previewContext.strokeStyle = currentColor;
        previewContext.lineWidth = currentSize;
    }
    var position = $("canvas:first").position();
    previewContext.moveTo(position.left + 20, position.top + 40);
    var x1 = position.left + 170;
    var y1 = position.top + 20;
    var cx = position.left - 20;
    var cy = position.top + 160;
    previewContext.quadraticCurveTo(cx, cy, x1, y1);
    previewContext.stroke();
}
/*******************************************************************************
 * Push canvas to FB part, from https://github.com/lukasz-madon/heroesgenerator
 ******************************************************************************/
(function($) {
    $(document).bind('FBSDKLoaded', function() {
        if (XMLHttpRequest.prototype.sendAsBinary === undefined) {
            XMLHttpRequest.prototype.sendAsBinary = function(string) {
                var bytes = Array.prototype.map.call(string, function(c) {
                    return c.charCodeAt(0) & 0xff;
                });
                this.send(new Uint8Array(bytes).buffer);
            };
        }

        function postImageToFacebook(authToken, filename, mimeType, imageData, message) {
            // this is the multipart/form-data boundary we'll use
            var boundary = '----ThisIsTheBoundary1234567890';
            // let's encode our image file, which is contained in the var
            var formData = '--' + boundary + '\r\n';
            formData += 'Content-Disposition: form-data; name="source"; filename="' + filename + '"\r\n';
            formData += 'Content-Type: ' + mimeType + '\r\n\r\n';
            for (var i = 0; i < imageData.length; ++i) {
                formData += String.fromCharCode(imageData[i] & 0xff);
            }
            formData += '\r\n';
            formData += '--' + boundary + '\r\n';
            formData += 'Content-Disposition: form-data; name="message"\r\n\r\n';
            formData += message + '\r\n';
            formData += '--' + boundary + '--\r\n';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'https://graph.facebook.com/me/photos?access_token=' + authToken, true);
            xhr.onload = xhr.onerror = function() {
                console.log(xhr.responseText);
            };
            xhr.setRequestHeader("Content-Type", "multipart/form-data; boundary=" + boundary);
            xhr.sendAsBinary(formData);
        }
        var authToken;

        document.getElementById("toolbuttonPush").onclick = function postCanvasToFacebook() {
            var data = canvas.toDataURL("image/png");
            var encodedPng = data.substring(data.indexOf(',') + 1, data.length);
            var decodedPng = Base64Binary.decode(encodedPng);
            FB.getLoginStatus(function(response) {
                if (response.status === "connected") {
                    postImageToFacebook(response.authResponse.accessToken, "Canvas", "image/png", decodedPng, "m101.nthu.edu.tw/~s101062124/HW5/Canvas.html");
                } else if (response.status === "not_authorized") {
                    FB.login(function(response) {
                        postImageToFacebook(response.authResponse.accessToken, "Canvas", "image/png", decodedPng, "m101.nthu.edu.tw/~s101062124/HW5/Canvas.html");
                    }, {
                        scope: "publish_stream"
                    });
                } else {
                    FB.login(function(response) {
                        postImageToFacebook(response.authResponse.accessToken, "Canvas", "image/png", decodedPng, "m101.nthu.edu.tw/~s101062124/HW5/Canvas.html");
                    }, {
                        scope: "publish_stream"
                    });
                }
            });

        };
    });

})(jQuery);