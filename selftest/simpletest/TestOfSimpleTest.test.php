<?php
require_once('bootstrap.php');

class TestOfSimpleTest extends UnitTestCase {

    function testTrue(){
        $this->assertTrue(true);
    }
    
    function testFalse(){
        $this->assertFalse(false);
    }
    
    function testNull(){
        $this->assertNull(null);
    }
    
    function testNotNull(){
        $this->assertNotNull(true);
        $this->assertNotNull(false);
        $this->assertNotNull(0);
        $this->assertNotNull('');
        $c = new stdClass();
        $this->assertNotNull($c);
        $this->assertNotNull(array());
    }
    
    function testIsA(){
        $c = new stdClass();
        $this->assertIsA($c,'stdClass');
    }
    
    function testEqual(){
        $a = 'foo';
        $b = 'foo';
        $this->assertEqual($a,$b);
        
        $a = new stdClass();
        $b = new stdClass();
        // tests using '=='
        $this->assertEqual($a,$b); 
    }
    
    function testNotEqual(){
        $a = new stdClass();
        $a->foo = 'bar';
        $b = new stdClass();
        $this->assertNotEqual($a,$b); 
    }
    
    function testIdentical(){
        $a = new stdClass();
        $b = new stdClass();
        $this->assertIdentical($a,$b);
        
        $a = 0;
        $b = 0;
        $this->assertIdentical($a,$b);
    }
    
    function testNotIdentical(){
        $a = 0;
        $b = '';
        $this->assertNotIdentical($a,$b); // tests using '==='
        
        $a = false;
        $b = 0;
        $this->assertNotIdentical($a,$b); // tests using '==='
    }
    
    function testReference(){
        $a = new stdClass();
        $b = $a;
        $this->assertReference($a,$b);
        /*
        * Note, this would fail: 
        $a = new stdClass();
        $b = new stdClass();
        $this->assertReference($a,$b);
        /**/
    }
    
    function testCopy(){
        $a = new stdClass();
        $b = new stdClass();
        $this->assertCopy($a,$b);
        
        /*
        * Note, this would fail:
        $a = new stdClass();
        $b = $a;
        $this->assertCopy($a,$b); 
        /**/
    }
    
    /**
    * @todo testWantedPattern
    * @todo testNoUnwantedPattern
    * @todo testNoErrors
    * @todo testErrors
    */
}
?>