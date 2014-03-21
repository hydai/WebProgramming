var B = "-";
var HB = "";
var KB = "";
var TKB = "";
var HKB = "";
var MB = "";
for (i = 0; i < 1024; i++) {
    KB += '-';
}
for (i = 0; i < 1024; i++) {
    MB += KB;
}
for (i = 0; i < 100; i++) {
    HKB += KB;
}
for (i = 0; i < 10; i++) {
    TKB += KB;
}
for (i = 0; i < 100; i++) {
    HB += B;
}
var autotest = false;

function Clean() {
    autotest = false;
    window.sessionStorage.clear();
    var screen = document.getElementById('screen');
    console.log(screen.value);
    screen.value = "0";
}

function AutoTry() {
    Clean();
    for (i = 0; i < 4; i++) {
        Add1MB();
    }

    for (i = 0; i < 10; i++) {
        Add100KB();
    }
    for (i = 0; i < 2; i++) {
        Add10KB();
    }
    for (i = 0; i < 3; i++) {
        Add1KB();
    }
    for (i = 0; i < 9; i++) {
        Add100B();
    }
    var flag = true;
    while (flag) {
        try {
            var tmp = window.sessionStorage.getItem('a') + B;
            window.sessionStorage.setItem('a', tmp);
            id++;
            var screen = document.getElementById('screen');
            screen.value = parseInt(screen.value) + 1;
        } catch (err) {
            alert("Storage is full!");
            var screen = document.getElementById('screen');
            screen.value = "Auto testing result: " + parseInt(screen.value) + 1;
            flag = false;

            autotest = true;
        }
    }
}

function Add1B() {
    if (autotest) {
        Clean();
    }
    try {
        var tmp = window.sessionStorage.getItem('a') + B;
        window.sessionStorage.setItem('a', tmp);
    } catch (err) {
        alert("Storage is full!");
    }

    var screen = document.getElementById('screen');
    screen.value = parseInt(screen.value) + 1;
}

function Add100B() {
    try {
        var tmp = window.sessionStorage.getItem('a') + HB;
        window.sessionStorage.setItem('a', tmp);
    } catch (err) {
        alert("Storage is full!");
    }

    var screen = document.getElementById('screen');
    screen.value = parseInt(screen.value) + 100;
}

function Add1KB() {
    if (autotest) {
        Clean();
    }
    try {
        var tmp = window.sessionStorage.getItem('a') + KB;
        window.sessionStorage.setItem('a', tmp);
    } catch (err) {
        alert("Storage is full!");
    }

    var screen = document.getElementById('screen');
    screen.value = parseInt(screen.value) + 1024;
}

function Add10KB() {
    try {
        var tmp = window.sessionStorage.getItem('a') + TKB;
        window.sessionStorage.setItem('a', tmp);
    } catch (err) {
        alert("Storage is full!");
    }

    var screen = document.getElementById('screen');
    screen.value = parseInt(screen.value) + 10240;
}

function Add100KB() {
    try {
        var tmp = window.sessionStorage.getItem('a') + HKB;
        window.sessionStorage.setItem('a', tmp);
    } catch (err) {
        alert("Storage is full!");
    }

    var screen = document.getElementById('screen');
    screen.value = parseInt(screen.value) + 102400;
}

function Add1MB() {
    if (autotest) {
        Clean();
    }
    try {
        var tmp = window.sessionStorage.getItem('a') + MB;
        window.sessionStorage.setItem('a', tmp);
    } catch (err) {
        alert("Storage is full!");
    }

    var screen = document.getElementById('screen');
    screen.value = parseInt(screen.value) + 1024 * 1024;
}