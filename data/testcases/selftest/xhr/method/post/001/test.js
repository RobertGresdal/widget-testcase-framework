function pass(x){
    testcase.result(true);
}
function fail(x){
    testcase.result(false,x);
}

window.onload = function(){
    xhr({
        uri:'http://t/resources/scripts/wantspassed.php',
        method:'post',
        data:'passed=passed',
        loaded:pass,
        error:fail
    }); 
}