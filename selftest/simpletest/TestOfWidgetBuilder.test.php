<?php
require_once('../../autoload.php');
    
class TestOfWidgetBuilder extends UnitTestCase {
    function setUp(){}
    function tearDown(){}
    
    function testRunPass(){
        // modify the request so we have a fake one
        $wb = new WidgetBuilder('helpers/widget_testcase_001');
        $filename = $wb->getFilename();
        
        $z = new ZipArchive();
        $z->open($filename);
        
        
        $fp = $z->getStream('widget.txt');
        
        $this->assertTrue( $fp );

        while (!feof($fp)) {
            $contents .= fread($fp, 2);
        }

        fclose($fp);
        
        $this->assertEqual($contents, 'pass');
    }
  
}

?>