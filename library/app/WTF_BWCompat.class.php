<?php

/**
* Class providing backwards compatible functions used in the original WTF
*/
class WTF_BWCompat {
    /** 
    * Parse a string containing ini settings and outputs an array of settings
    * Code modified to use string input instead of files
    * author: goulven.ch@gmail.com from 29-Oct-2007 03:33 at http://no2.php.net/manual/en/function.parse-ini-file.php
    */
    public static function parse_ini( $content ) {
        //$ini = file( $filepath );
        //$ini = explode("\r\n",$content);
        $ini = split("\r|\n",$content);
        if ( count( $ini ) == 0 ) { return array(); }
        $sections = array();
        $values = array();
        $globals = array();
        $i = 0;
        foreach( $ini as $line ){
            $line = trim( $line );
            // Comments
            if ( $line == '' || $line{0} == ';' ) { continue; }
            // Sections
            if ( $line{0} == '[' ) {
                $sections[] = substr( $line, 1, -1 );
                $i++;
                continue;
            }
            // Key-value pair
            list( $key, $value ) = explode( '=', $line, 2 );
            $key = trim( $key );
            $value = trim( $value );
            if ( $i == 0 ) {
                // Array values
                if ( substr( $line, -1, 2 ) == '[]' ) {
                    $globals[ $key ][] = $value;
                } else {
                    $globals[ $key ] = $value;
                }
            } else {
                // Array values
                if ( substr( $line, -1, 2 ) == '[]' ) {
                    $values[ $i - 1 ][ $key ][] = $value;
                } else {
                    $values[ $i - 1 ][ $key ] = $value;
                }
            }
        }
        $result = array();
        for( $j=0; $j<$i; $j++ ) {
            $result[ $sections[ $j ] ] = $values[ $j ];
        }
        return $result + $globals;
    }
}
?>