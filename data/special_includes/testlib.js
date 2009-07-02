window.addEventListener('load',function(){window.isLoaded=true;},false);



/**
* This will tell our testset server whether a testcase passed or failed. 
* Testcases only has to include testcase.result(boolean) and the rest 
* will be handled automatically.
*
* Any test code should be prepared that the widget could close after the 
* result() funcion has been called!
*
* You should not create a new Testcase object. Only use the already 
* created global 'testcase'.
*/
function Testcase(){
    /*
    /**
    * The url for the test server that will record results.
    * @access readonly
    * /
    this.testServer = typeof(TEST_SERVER)=='undefined'?null:TEST_SERVER;
    */
    /**
    * The url with printf format to request on testserver for 
    * reporting a result. 
    * 
    * For printf formatting:
    * The first parameter is a success string: 'pass' or 'fail'
    * The second parameter is the reason for failing, if any.
    * @access readonly
    */
    this.reportURI = typeof(REPORT_URI)=='undefined'?'':REPORT_URI;
    /**
    * ID for this test so the test server can tell which test
    * is reporting back with its result. Default value is an 
    * empty string since the ID will be inserted into here
    * by a php file that modifies this script and adds it as 
    * a global TEST_ID var.
    * @access readonly
    */
    this.id = typeof(TEST_ID)=='undefined'?'':TEST_ID; 
    /**
    * Default value is true so all debug messages will be printed out.
    * If there should be no overhead of printing out debug messages,
    * this must be added as PRINT_DEBUG=false as a global variable at
    * the top of this script. That will be handled by a php script, the
    * same that adds the TEST_ID one.
    * @access readonly
    */
    this.printDebug = typeof(PRINT_DEBUG)=='undefined'?true:PRINT_DEBUG; 
    
    
    /**
    * Writes the text to the output window so a user can see it
    * @access public
    */
    this.output = function(text){
        var out = function(){
            if(document.getElementById('result')){
                document.getElementById('result').firstChild.data = text;
            }
        }
        if(window.isLoaded)out();
        else window.addEventListener('load',out,false);
    }
    
    
    
    /**
    * Testcase will call this function
    * @param pass boolean - Wether or not the test passed
    * @return boolean - True if the result was sent, false if there was an error. Please note
    *   that this does not mean whether the server recieved something. You need a callback for that.
    * @access public
    */
    this.result = function(pass, r){
        var reason = (r==null) ? '' : r;
        
        // Debug message if pass is false and there is a reason
        if(!pass && reason)this.debug(reason);
        
        // Add a "pass" or "fail" class to the content element so we can style by result
        var setClass = function(){
            if(document.getElementById('content')){
                document.getElementById('content').setAttribute('class',pass?'pass':'fail');
            }
        }
        if(window.isLoaded)setClass();
        else window.addEventListener('load',setClass,false);
        
        // print debug message
        if(opera && opera.postError){
            var msg = 'Result(ID'+this.id+')="'+pass+'"';
            if(reason.length>0)msg+=', Reason="'+reason+'"';
            opera.postError(msg);
        }
        
        // Add text output if a user watches the test
        this.output(pass?'PASS':'FAIL');
        
        // Contact test server and tell it the result 
        // TODO: let contacting the server be configurable.
        // Let the framework add a js variable that lets us know the URI
        // to call with %s for result and reason. 
        // spartan is "http://localhost/?status=(pass|fail)\treason"
        try{
            this.postResult(this.id, pass, reason, function(ok){
                // close the widget if the test server responded with 200 (all ok)
                if(ok)window.close();
            });
        }catch(e){
            if(opera && opera.postError){
                opera.postError('Cannot store result on test server: '+e);
            }
            return false;
        }
        
        return true;
    }
    
    
    /**
    * The debug function will print a message out to the
    * error console. Later on, this function may also contact
    * the server and store debugging results there.
    * @access public
    */
    this.debug = function(msg){
        // Print first to error console
        if(opera && opera.postError){
            opera.postError('DEBUG: '+msg);
        }
        
        if(this.printDebug){
            // Also, print to output so a user viewing it can see it
            var out = function(){
                if(document.getElementById('debug')){
                    debugList = document.getElementById('debug');
                    // TODO: make a function that makes messages safe to
                    // insert into html messages (since we're using innerHTML here)
                    if(msg instanceof Error){
                        str = '<li>Error:<ul>'+
                            '<li>Name: '+msg.name+'</li>'+
                            '<li>Message: '+msg.message+'</li>'+
                        '</ul></li>';
                        debugList.innerHTML += str;
                    } else {
                        debugList.innerHTML += '<li>'+msg+'</li>';
                    }
                }
            }
            if(window.isLoaded)out();
            else window.addEventListener('load',out,false);
        }
    }
    
    
    
    /**
    * Call this function using a string containing html that 
    * can be converted into a document fragment. It will be 
    * added to the index page.
    * 
    * @example addControls('<div>foo</div>bar') will add a div element and a text node after it.
    * @param {string} html fragment 
    * @param [{string} element id]
    * @access public
    */
    this.addControls = function(str,id){
        id = (id==null?'controls':id);
        var out = function(){
            if(document.getElementById('controls')){
                document.getElementById('controls').innerHTML += str;
            }
        }
        if(window.isLoaded)out();
        else window.addEventListener('load',out,false);
    }
    
    /**
    * Same as addControls but will replace whatever is inside.
    * The default element id is "controls".
    * 
    * @example addControls('<div>foo</div>bar') will add a div element and a text node after it.
    * @param {string} html fragment 
    * @param [{string} element id]
    * @access public
    */
    this.replaceControls = function(str,id){
        id = (id==null?'controls':id);
        var out = function(){
            if(document.getElementById('controls')){
                document.getElementById('controls').innerHTML = str;
            }
        }
        if(window.isLoaded)out();
        else window.addEventListener('load',out,false);
    }
    
    /**
    * Will mark the testcase as interactive and by default will add
    * a pass and fail button to the UI.
    * 
    * @param options Set options for the interactive modus;  
    *   However, there are no options available at this time.
    */
    this.setInteractive = function(options){
        tc = this;
        window.addEventListener('load',function(){
            function passCall(){testcase.result(true);}
            function failCall(){testcase.result(false,'User failed the interactive testcase.');}
            tc.addControls('<input id="passButton" type="button" value="Pass" />');
            tc.addControls('<input id="failButton" type="button" value="Fail" />');
            document.getElementById('passButton').addEventListener('click',passCall,false);
            document.getElementById('failButton').addEventListener('click',failCall,false);
        },false);
    }
    
    /**
    * Sends result to listening test server
    * 
    * @param id the id of this test
    * @param pass boolean - Whether or not this test passed
    * @param reason string - Why the result was such
    * @param [callback] function(responseBool) - Optional callback function 
    *   with what the server responded
    * @access private
    */
    this.postResult = function(id,pass,reason,callback){
        if(!this.reportURI)return false;

        // initialise variables
        // var uri = printf(this.reportURI,(pass?'pass':'fail'),encodeURI(reason));
        var uri = this.reportURI+"status="+(pass?'pass':'fail')+"\t"+encodeURI(reason);
        var xhr = new XMLHttpRequest();
        
        xhr.open('GET',uri,true);
        
        // Set the return function
        xhr.onreadystatechange = function(){
            if(xhr.readyState==4){
                // Only call back if we had a callback function
                if(typeof(callback)=='function'){
                    callback(xhr.status==200);
                }
            }
        }
        
        xhr.send(null);
    }
    
    
    // Write to the output that the test has started 
    this.output('Running...');
}




/**
* You can register results here and a callback function when 
* all results have had their results set. The first parameter
* of the callback function will contain the boolean and of
* all results and the second will contain an array of all
* results so you can check them yourself.
*
* @param array tests - a list of test names
* @param boolean and - true means all results will be returned with AND operator between, false returns with OR. so [false,false,true] is false with AND and true with OR.
* @param function callback - a function to call with the result
*/
function Resultset(tests, callback, and){
    // set tests and initialise as null
    this.tests = [];
    for(t in tests){
        this.tests[tests[t]] = null;
    }
    this.size = tests.length; // how many results to wait for
    this.setted = 0; // how many tests have been set so far
    this.callback = callback; // callback function with result
    this.and = and; // whether to compare using AND (true) or OR (false)
    
    this.set = function(test,result,debugMsg){
        var msg = (debugMsg && debugMsg.length>0) ? debugMsg : 'false';
        
        // Print out debug message using a testcase object if the result is false
        if(!result){
            if(testcase) testcase.debug('Resultset('+test+'): '+msg);
            //opera.postError('Resultset('+test+'): '+msg);
        }
    
        // only increase the setter if the current result was null - prevent overwriting
        this.tests[test]==null ? this.setted++ : null;
        this.tests[test] = (result==true); // we only want to store boolean results
        
        if(this.setted >= this.size){
            this.callback(this.and ? this.compareAnd() : this.compareOr());
        }
    }
    this.compareAnd = function(){
        var res = true;
        for(t in this.tests){
            res &= (this.tests[t]==true);
        }
        return (res==1);
    }
    this.compareOr = function(){
        var res = false;
        for(t in this.tests){
            res |= (this.tests[t]==true);
        }
        return (res==1);
    }
}




/**
* xhr sends data from a configuration object
*
* the object looks as follows:
* ============================
* obj.method string ['GET' | 'POST'] 
* obj.headers array - [['foo','bar'],['foo','car',true]] will send 'foo: car' header since true overwrites
* obj.uri string - uri to call. It can be relative, in which case it is relative to this server
* obj.open = function to run when readyState is 1
* obj.sent = function to run when readyState is 2
* obj.receiving = function to run when readyState is 3
* obj.loaded = function to run on readyState 4 (gets xhr object as first param)
* obj.data = data to send on post request
* obj.error = Error catching callback function. First parameter is the error object.
*
*/
function xhr(obj){
    var xhrobj = new XMLHttpRequest();
    var uri = '';
    
    // Make sure we get a method, use GET as default
    if(obj.method==null)obj.method='GET';
    
    // Determine wether we want a relative url for our service name
    if( obj.uri.indexOf('http')==0 || 
        obj.uri.indexOf('ftp')==0 || 
        obj.uri.indexOf('widget')==0 )
    {
        uri = obj.uri;
    } else {
        if(opera && opera.postError){
            opera.postError('Absolute uri required.');
        }
        return null;
    }
    
    // Send headers
    if(obj.headers != null){
        for(h in obj.headers){
            xhrobj.setRequestHeader(obj.headers[h][0], obj.headers[h][1], (obj.headers[h][2]==true));
        }
    }
    
    // Set return functions
    xhrobj.onreadystatechange = function(){
        if(xhrobj.readyState==1){if(obj.open)obj.open(xhrobj);}
        else if(xhrobj.readyState==2){if(obj.sent)obj.sent(xhrobj);}
        else if(xhrobj.readyState==3){if(obj.receiving)obj.receiving(xhrobj);}
        else if(xhrobj.readyState==4){if(obj.loaded)obj.loaded(xhrobj);}
    }
    
    
    // Send data
    try {
        // Open the uri
        xhrobj.open(obj.method, uri, true);
        
        // consider post request content type
        if(obj.method.toLowerCase() == 'post')
            xhrobj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
            
        // Send data
        xhrobj.send(obj.data);
    } catch(e){
        if(obj.error)obj.error(e);
    }
}





/* Function printf(format_string,arguments...)
 * Javascript emulation of the C printf function (modifiers and argument types 
 *    "p" and "n" are not supported due to language restrictions)
 *
 * Copyright 2003 K&L Productions. All rights reserved
 * http://www.klproductions.com 
 *
 * Terms of use: This function can be used free of charge IF this header is not
 *               modified and remains with the function code.
 * 
 * Legal: Use this code at your own risk. K&L Productions assumes NO resposibility
 *        for anything.
 ********************************************************************************/
function printf(fstring)
  { var pad = function(str,ch,len)
      { var ps='';
        for(var i=0; i<Math.abs(len); i++) ps+=ch;
        return len>0?str+ps:ps+str;
      }
    var processFlags = function(flags,width,rs,arg)
      { var pn = function(flags,arg,rs)
          { if(arg>=0)
              { if(flags.indexOf(' ')>=0) rs = ' ' + rs;
                else if(flags.indexOf('+')>=0) rs = '+' + rs;
              }
            else
                rs = '-' + rs;
            return rs;
          }
        var iWidth = parseInt(width,10);
        if(width.charAt(0) == '0')
          { var ec=0;
            if(flags.indexOf(' ')>=0 || flags.indexOf('+')>=0) ec++;
            if(rs.length<(iWidth-ec)) rs = pad(rs,'0',rs.length-(iWidth-ec));
            return pn(flags,arg,rs);
          }
        rs = pn(flags,arg,rs);
        if(rs.length<iWidth)
          { if(flags.indexOf('-')<0) rs = pad(rs,' ',rs.length-iWidth);
            else rs = pad(rs,' ',iWidth - rs.length);
          }    
        return rs;
      }
    var converters = new Array();
    converters['c'] = function(flags,width,precision,arg)
      { if(typeof(arg) == 'number') return String.fromCharCode(arg);
        if(typeof(arg) == 'string') return arg.charAt(0);
        return '';
      }
    converters['d'] = function(flags,width,precision,arg)
      { return converters['i'](flags,width,precision,arg); 
      }
    converters['u'] = function(flags,width,precision,arg)
      { return converters['i'](flags,width,precision,Math.abs(arg)); 
      }
    converters['i'] =  function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        var rs = ((Math.abs(arg)).toString().split('.'))[0];
        if(rs.length<iPrecision) rs=pad(rs,' ',iPrecision - rs.length);
        return processFlags(flags,width,rs,arg); 
      }
    converters['E'] = function(flags,width,precision,arg) 
      { return (converters['e'](flags,width,precision,arg)).toUpperCase();
      }
    converters['e'] =  function(flags,width,precision,arg)
      { iPrecision = parseInt(precision);
        if(isNaN(iPrecision)) iPrecision = 6;
        rs = (Math.abs(arg)).toExponential(iPrecision);
        if(rs.indexOf('.')<0 && flags.indexOf('#')>=0) rs = rs.replace(/^(.*)(e.*)$/,'$1.$2');
        return processFlags(flags,width,rs,arg);        
      }
    converters['f'] = function(flags,width,precision,arg)
      { iPrecision = parseInt(precision);
        if(isNaN(iPrecision)) iPrecision = 6;
        rs = (Math.abs(arg)).toFixed(iPrecision);
        if(rs.indexOf('.')<0 && flags.indexOf('#')>=0) rs = rs + '.';
        return processFlags(flags,width,rs,arg);
      }
    converters['G'] = function(flags,width,precision,arg)
      { return (converters['g'](flags,width,precision,arg)).toUpperCase();
      }
    converters['g'] = function(flags,width,precision,arg)
      { iPrecision = parseInt(precision);
        absArg = Math.abs(arg);
        rse = absArg.toExponential();
        rsf = absArg.toFixed(6);
        if(!isNaN(iPrecision))
          { rsep = absArg.toExponential(iPrecision);
            rse = rsep.length < rse.length ? rsep : rse;
            rsfp = absArg.toFixed(iPrecision);
            rsf = rsfp.length < rsf.length ? rsfp : rsf;
          }
        if(rse.indexOf('.')<0 && flags.indexOf('#')>=0) rse = rse.replace(/^(.*)(e.*)$/,'$1.$2');
        if(rsf.indexOf('.')<0 && flags.indexOf('#')>=0) rsf = rsf + '.';
        rs = rse.length<rsf.length ? rse : rsf;
        return processFlags(flags,width,rs,arg);        
      }  
    converters['o'] = function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        var rs = Math.round(Math.abs(arg)).toString(8);
        if(rs.length<iPrecision) rs=pad(rs,' ',iPrecision - rs.length);
        if(flags.indexOf('#')>=0) rs='0'+rs;
        return processFlags(flags,width,rs,arg); 
      }
    converters['X'] = function(flags,width,precision,arg)
      { return (converters['x'](flags,width,precision,arg)).toUpperCase();
      }
    converters['x'] = function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        arg = Math.abs(arg);
        var rs = Math.round(arg).toString(16);
        if(rs.length<iPrecision) rs=pad(rs,' ',iPrecision - rs.length);
        if(flags.indexOf('#')>=0) rs='0x'+rs;
        return processFlags(flags,width,rs,arg); 
      }
    converters['s'] = function(flags,width,precision,arg)
      { var iPrecision=parseInt(precision);
        var rs = arg;
        if(rs.length > iPrecision) rs = rs.substring(0,iPrecision);
        return processFlags(flags,width,rs,0);
      }
    farr = fstring.split('%');
    retstr = farr[0];
    fpRE = /^([-+ #]*)(\d*)\.?(\d*)([cdieEfFgGosuxX])(.*)$/;
    for(var i=1; i<farr.length; i++)
      { fps=fpRE.exec(farr[i]);
        if(!fps) continue;
        if(arguments[i]!=null) retstr+=converters[fps[4]](fps[1],fps[2],fps[3],arguments[i]);
        retstr += fps[5];
      }
    return retstr;
  }
/* Function printf() END */




var testcase = new Testcase();
