try{
    xhr({uri:'http://www.google.com/',loaded:first,error:whops});
    function first(x){
        testcase.debug('Loaded '+this.uri);
        xhr({
            uri:'http://www.yahoo.com/',
            data:'status=google%20is%20'+x.status==200?'online':'offline',
            loaded:second,
            error:whops
        });
    }
    function second(x){
        testcase.debug('Loaded '+this.uri);
        testcase.result(true);
    }
    function whops(x){
        testcase.result(false,'Error when contacting '+this.uri);
    }
} 
catch(error){
    testcase.result(false,'Caught unexpected error: '+error);
}