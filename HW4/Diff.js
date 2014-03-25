var leftValue = "//Paste your code here.\n#include <cstdio>\nint main() {\n\treturn 0;\n}\n";
var leftEditor = CodeMirror(document.getElementById("leftCodePanel"), {
    value: leftValue,
    lineNumbers: true,
    tabSize: 4,
    indentUnit: 4,
    indentWithTabs: true,
    lineWrapping: true,
    styleActiveLine: true,
    mode: "text/x-csrc",
    keyMap: "sublime",
    autoCloseBrackets: true,
    matchBrackets: true,
    showCursorWhenSelecting: true,
    theme: "solarized light"
});

var rightValue = "//Paste your code here.\n#include <cstdio>\nint main() {\n\treturn 0;\n}\n";
var rightEditor = CodeMirror(document.getElementById("rightCodePanel"), {
    value: rightValue,
    lineNumbers: true,
    tabSize: 4,
    indentUnit: 4,
    indentWithTabs: true,
    lineWrapping: true,
    styleActiveLine: true,
    mode: "text/x-csrc",
    keyMap: "sublime",
    autoCloseBrackets: true,
    matchBrackets: true,
    showCursorWhenSelecting: true,
    theme: "solarized light"
});

var isDiff = false;
$(document).ready(function() {
    $("#myButton").click(function() {
        changeButtonMode();
    });
    $("#myButton").hover(function() {
        showButtonInfo();
    });
    $("#myButton").mouseout(function() {
        checkDiff();
    });
});

var leftText;
var rightText;

function diff() {
    if (removeSpaceWord(leftEditor.getValue()).length === 0 && removeSpaceWord(rightEditor.getValue()).length === 0) {
        return "請放入程式碼";
    }
    leftText = parseToPureText(leftEditor.getValue());
    rightText = parseToPureText(rightEditor.getValue());
    lcs = LCS(leftText, rightText);
    return ("相似度：" + Math.round(lcs / Math.max(rightText.length, leftText.length) * 100) + " %");
}

function parseToPureText(file) {
    result = removeComments(file);
    result = removeSpaceWord(result);
    return result;
}

function removeComments (file) {
    reg = new RegExp('/\\*([^*]|[\r\n]|(\\*+([^*/]|[\r\n])))*\\*+/', "g");
    result = file.replace(reg, "");
    reg = new RegExp("//[^\r\n]*", "g");
    result = result.replace(reg, "");
    console.log(result);
    return result;
}

function removeSpaceWord (file) {
    reg = new RegExp('[^!-z{}]*', "g");
    result = file.replace(reg, "");
    return result;
}

var lcsStr = "";

function LCS(s1, s2) {
    prev = new Array(s1.length + 1);
    for (i = 0; i <= s1.length; i++) {
        prev[i] = new Array(s2.length + 1);
    }
    for (i = 0; i <= s1.length; i++) {
        for (j = 0; j <= s2.length; j++) {
            prev[i][j] = "0";
        }
    }
    twoDArr = new Array(s1.length + 1);
    for (i = 0; i <= s1.length; i++) {
        twoDArr[i] = new Array(s2.length + 1);
    }
    for (i = 0; i <= s1.length; i++) {
        twoDArr[i][0] = 0;
    }
    for (i = 0; i <= s2.length; i++) {
        twoDArr[0][i] = 0;
    }

    for (i = 1; i <= s1.length; i++) {
        for (j = 1; j <= s2.length; j++) {
            if (s1[i - 1] == s2[j - 1]) {
                twoDArr[i][j] = twoDArr[i - 1][j - 1] + 1;
                prev[i][j] = "leftUp";
            } else {
                if (twoDArr[i - 1][j] < twoDArr[i][j - 1]) {
                    twoDArr[i][j] = twoDArr[i][j - 1];
                    prev[i][j] = "left";
                } else {
                    twoDArr[i][j] = twoDArr[i - 1][j];
                    prev[i][j] = "up";
                }
            }
        }
    }
    lcsStr = "";
    genLcsStr(prev, s1.length, s2.length);
    return twoDArr[s1.length][s2.length];
}
var count = 0;

function genLcsStr(prev, i, j) {
    count++;
    if (i == "0" || j == "0") return;

    if (prev[i][j] == "leftUp") {
        genLcsStr(prev, i - 1, j - 1);
        lcsStr += leftText[i - 1];
    } else if (prev[i][j] == "up") {
        genLcsStr(prev, i - 1, j);
    } else if (prev[i][j] == "left") {
        genLcsStr(prev, i, j - 1);
    }
}

var diffResult = "";

function changeButtonMode() {
    if (!isDiff) {
        diffResult = diff();
        console.log(isDiff + " " + diffResult);
        $("#myButton").text(diffResult);
    } else {
        console.log(isDiff + " Diff Now!");
        $("#myButton").text("Diff Now!");
    }
    isDiff = !isDiff;
}

function showButtonInfo() {
    if (!isDiff) {
        $("#myButton").text("Diff Now!");
    } else {
        $("#myButton").text("Clear Result");
    }
}

function checkDiff() {
    if (isDiff) {
        $("#myButton").text(diffResult);
    } else {
        $("#myButton").text("Diff Now!");
    }
}