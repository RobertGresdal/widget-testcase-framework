if( opera && opera.io && opera.io.filesystem ){
    testcase.result(true);
} else {
    testcase.result(false,'FileIO not enabled.');
}