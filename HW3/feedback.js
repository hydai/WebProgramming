function setCookie(name, value) {
    document.cookie = name + "=" + value;
}
/*
function cleanCookie() {
    now = new Date();
    now.setTime(now.getTime() - 1);
    document.cookie = "expires=" + now.toGMTString();
    alert(document.cookie);
}
*/

function loadCookie() {
    cookieArr = document.cookie.split('; ');
    for (i = 0; i < cookieArr.length; i++) {
        thisCookie = cookieArr[i].split('=');
        thisName = thisCookie[0];
        thisVal = thisCookie[1];
        console.log("####" + thisName + ' ' + thisVal);

        if (thisName == "usernameInput" || thisName == "useremail" || thisName == "usertype") {
            document.getElementById(thisName).value = thisVal;
        } else if (thisName == "cf") {
            document.getElementById("cf" + thisVal).checked = true;
        } else if (thisName == "usertype") {
            var list = document.getElementById("usertype");
            list.value = list[0].value;
            for (var i = 0; i < list.length; i++) {
                if (list[i].value == thisVal) {
                    list.value = list[i].value;
                    break;
                }
            }
        } else if (thisName == "pntc1" || thisName == "pntc2" || thisName == "pntc3") {
            console.log("check" + thisName + " " + thisVal);
            if (thisVal == "true") {
                document.getElementById(thisName).checked = true;
            } else {
                document.getElementById(thisName).checked = false;
            }
        }

    }
}