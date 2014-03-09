var isEval = 1;

function Clear() {
    var currentArea = document.getElementById("screen");
    currentArea.value = "0";
    isEval = 0;
}

function AddElement(t) {
    var currentArea = document.getElementById("screen");
    if (currentArea.value === "0" || isEval === 1) {
        currentArea.value = t;
        isEval = 0;
    } else {
        currentArea.value += t;
    }
}

function Eval() {
    var currentArea = document.getElementById("screen");
    if (currentArea.value.match("//") != null) {
        currentArea.value = "ERROR";
        isEval = 1;
        console.log("ERROR OCCUR: find '//'")
        return;
    }
    try {
        currentArea.value = eval(currentArea.value);
    } catch (err) {
        currentArea.value = "ERROR";
        console.log("ERROR OCCUR: " + err);
    }
    isEval = 1;
}

function Sqrt() {
	var currentArea = document.getElementById("screen");
	if (isEval != 1) {
		Eval();
		if (currentArea.value === "ERROR") return;
	}
    try {
        currentArea.value = Math.sqrt(currentArea.value);
        isEval = 1;
    } catch (err) {
        currentArea.value = "ERROR";
        console.log("ERROR OCCUR: " + err);
    }
}

function Delete () {
	var currentArea = document.getElementById("screen");
	if (currentArea.value.length == 1) {
		currentArea.value = "0";
	}
	else {
		currentArea.value = currentArea.value.substr(0, currentArea.value.length-1);
	}
}
