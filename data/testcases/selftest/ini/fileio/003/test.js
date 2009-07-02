if( opera && opera.io && opera.io.filesystem ){
    testcase.result(false,'FileIO should not be enabled when invalid value is passed to widget configutation.');
} else {
    testcase.result(true);
}