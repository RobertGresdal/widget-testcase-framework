The smallest testcase will contain two files: [widget.txt](Tutorial#Identifier_file.md) and [test.js](Tutorial#Testfile.md). The text file should contain a brief description of what the test should do. The description is required by convention, but not by code, however the widget.txt file MUST exist. More about that under [Tutorial#Identifier\_file](Tutorial#Identifier_file.md).

The framework will have included a small javascript library for you which have already created an object called `testcase`. This object contains some methods to call, the most important one being `result()`.

Add the following to the `test.js` file, you should now have a simple test complete:

```
testcase.result( true );
```

This should color the output green and the box should say "PASS". If you have configured the testcases to report to a server, it will call the URL with an xmlhttprequest with the result in the GET string.

If your code detects that the test failed, there is a second parameter which should contain the reason it failed:

```
testcase.result( false, "Test failed because the moon is not full." );
```

The result method also accepts an exception as its second parameter. The widget will then output the stacktrace in the window.

```
try{
    // something that throws an exception
}catch(Exception e){
    testcase.result( false, e );
}
```

### Possible results ###
If the test passes successfully, the widget will display a green box with the text "PASS".

Conversely a widget that fails will show a red box with "FAIL".

A widget that has not completed running, will display white and the test "Running...". If this happens, the test might be incomplete. Consider if you can change the test to a definite result. A timeout that calls failure might also be better than none.

If the javascript is disabled or there has been no code running at all, it will display a white box and the text "NOT TESTED".

### Identifier file ###

The identifier file is called widget.txt (upcoming change will make it widget.ini). It flags to the PHP script that the directory contains a testcase, so it knows it can package the current folder.

### Testfile ###

The test file MUST always be called `test.js`.

## Widget configuration settings ##

You may add specific optional features for widgets by adding some settings in the widget.txt file. These features are browser specific, I'll get back to these in a later revision. Poke me by email or an issue if you need to know something.

## Library ##

As mentioned above, the framework adds a library. This contains some methods which makes it easier to create testcases.

### setInteractive() ###

You may call `testcase.setInteractive()`, which will make the testcase add two buttons, "Pass" and "Fail" to the UI.

```
// Try this, it should work
testcase.addControls('<p>Click "Pass".</p>');
testcase.setInteractive();
```

### printf ###

~~printf is included and works as it does in C, PHP and other programming languages, but modifiers and argument types "p" and "n" are not supported due to language restrictions.~~ Some bugs have been mentioned about this method, it might be removed or replaced. Poke me if you need this or have an alternative.

### Testcase object ###

Once your test.js script is running, a `testcase` object has already been created for you. You **must not** create a new testcase object.

#### testcase.result(bool result [, string|exception reason]) ####

The first argument is a boolean value where pass==true and fail==false. The second parameter is the reason for the result. A reason SHOULD be specified if the result was false, but is not requried if the test pass. The second parameter may also be an exception.

#### testcase.debug(string msg) ####

Prints a message to the error console and adds a text box in the widget to output results. A finished testcase should not call debug, there should be no reason for a tester to see the internal workings of the testcase. If you do, have fun running your testcases manually.

#### testcase.addControls(string html) ####

Add controls to the widget. The parameter accepts html code, so you can call `testcase.addControls('<label>Type something here: <input type="string" id="foo" name="foo" /></label>');`

You can attach events right after calling addControls. `document.getElementById('foo').addEventListener('change',functionFoo,false);`

### XMLHttpRequest helper ###

The xhr() function will help you send XMLHttpRequests. Pass a single configurational object and the rest will be handled automatically.

> uri : String containing which uri we send the request to. This is the only parameter which is not optional. If the current widget is an alien, the uri can be relative.

  * **headers** : A double-array containing extra headers to send. Example: `[['Accept-Language','nb']]` or `[['User-Agent', 'Mozilla/5.0 (Linux; X11; UTF-8)',true],['Accept-Language','nb',true]]`. The second example will overwrite the User-Agent and Accept-Language headers specified previously (if the browser accepts, though). ([List of HTTP headers](http://en.wikipedia.org/wiki/List_of_HTTP_headers)).

  * **method** : String declaring wether the method is GET or POST.

  * **open**, **sent**, **receiving**, **loaded** : Function to run when readyState is 1, 2, 3 and 4 respectively. Each parameter is optional.

  * **data** : Pass a string to be sendt if the current method is POST. Encode the string as a query string is encoded with 'foo=bar&amp;bar=baz'.

  * **error** : Function to run if there was an error thrown within `xhr()`. The first parameter for this function is the error object. This can happen in some version of opera where you cannot contact a server within an intranet and an external adress at the same time. You should consider adding this if you want to contact more than one server at any one time.

#### Simple example ####

```
xhr({
    uri:'http://www.opera.com/',
    loaded:function(xhr){
        alert('Opera webpage is ' + (xhr.status==200?'online.':'offline!') );
    }
}); 
```

#### Security error (in some versions) ####

```
// Attempt to contact what is presumably the router
xhr({uri:'http://192.168.1.1/'});

// Contact some external server. This will be prevented in Opera 9.0X to 9.2x
xhr({
    uri:'http://www.opera.com.com/',
    loaded:function(xhr){
        alert('No connection attempts were stopped.');
    },
    error:function(e){
        alert('Opera stopped the request. Error: '+e);
    }
}); 
```

#### Post data ####
```
xhr({
    uri:'http://www.foo.com/bar.php',
    method:'post',
    data:'foo=bar&bar=baz'
}); 
```

#### Fetch data from any html page ####
```
function nsr(){return 'http://www.w3.org/1999/xhtml';}

// Asks for the current posting key on opera, assuming 
// *the widget* has a current session cookie
xhr({
    uri:'http://my.opera.com/community/forums/topic.dml?id=199899',
    loaded:function(xhr){ 
        var xpath = "//h:input[@name='key']/@value";
        var key = xhr.responseXML.evaluate(xpath,
            xhr.responseXML,nsr,2,null).stringValue;
        opera.postError(key); 
    }
});
```

#### Chaining requests (short pseudocode) ####
```
xhr({uri:'foo',loaded:next});
function next(){
    xhr({uri:'baz'}); 
}
// remember to add error:somefunc to cover all exits
```

#### More about chaining requests (working example) ####
```
// Try changing the urls to an invalid domain, such as 
// www.googlealsdhfklajsdhf.com to see how failing is handled.
// Note that functions inherit the correct uri value (and all
// other parameters, so the loading function know about the
// error function).
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
```