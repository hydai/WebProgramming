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

// for color panel
var currentColor = '#000';
var currentSize = 10;
var eraserColor = 'rgba(0,0,0,1)';

// initial panel
window.onload = reset();

function reset() {
    canvasReset();
    document.getElementById('colorbar').value = '#000000';
    previewScreenReset();
    changeDrawMode("pencil");
}

function changeDrawMode(mode) {
    drawMode = mode;
    if (mode == 'pencil') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = '#ACD6FF';
        document.getElementById('toolbuttonBrush').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonEraser').style.backgroundColor = 'gray';
    } else if (mode == 'brush') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonBrush').style.backgroundColor = '#ACD6FF';
        document.getElementById('toolbuttonEraser').style.backgroundColor = 'gray';
    } else if (mode == 'eraser') {
        document.getElementById('toolbuttonPencil').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonBrush').style.backgroundColor = 'gray';
        document.getElementById('toolbuttonEraser').style.backgroundColor = '#ACD6FF';
    }
}
/************************************************************
 * Canvas part
 ************************************************************/

canvas.onmousedown = function(event) {
    context.beginPath();
    context.strokeStyle = currentColor;
    context.lineWidth = currentSize;
    context.lineCap = "round";
    var position = $("canvas:last").position();
    mouseX = event.clientX - position.left;
    mouseY = event.clientY - position.top;
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
        }
        context.globalCompositeOperation = tempCO;
    }
};

canvas.onmouseup = function(event) {
    onDraw = false;
};

document.getElementById("toolbuttonSave").onclick = function() {
    window.open(context.canvas.toDataURL('img/png'));
};

function canvasReset() {
    canvas.width = $(document).width() * 0.8;
    canvas.height = $(document).height();
    context.fillstyle = 'rgba(0, 0, 0, 0)';
}

/************************************************************
 * Toolbar part
 ************************************************************/
document.getElementById('toolbuttonCanvas').onclick = function() {
    canvasReset();
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
document.getElementById('toolbuttonSave').onclick = function() {
    canvas2PNG();
};

function canvas2PNG() {
    var bRes = false;
    bRes = Canvas2Image.saveAsPNG(canvas);
    if (!bRes) alert("Sorry, this browser is not capable of saving files!");
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

// Get the changing color from color input
document.getElementById('sizebar').onchange = function() {
    currentSize = this.value;
    document.getElementById('size').innerHTML = "Size:" + currentSize;
    previewScreenReset();
};

function previewScreenReset() {
    preview.width = $(document).width() * 0.15 * 0.9;
    preview.height = $(document).height() * 0.2;
    previewContext.fillstyle = 'rgba(0, 0, 0, 0)';
    previewContext.beginPath();
    previewContext.lineCap = "round";
    previewContext.strokeStyle = currentColor;
    previewContext.lineWidth = currentSize;
    var position = $("canvas:first").position();
    previewContext.moveTo(position.left + 20, position.top + 40);
    var x1 = position.left + 180;
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