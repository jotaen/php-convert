<?php
/**
 *  php-convert
 *  by Jan Heuermann    
 *
 *  This tool is able to convert numbers/strings with arbitrary
 *  bases. Either, you use the built-in default base (which
 *  should cover most of the use-cases), or you can specify
 *  the bases of both input-/output-value yourself.
 */

require_once('convert.php');

/**
 *  The default base is to call the convert-function with
 *  three arguments
 *  First parameter:    Number/String to be converted
 *  Second parameter:   Base of input number
 *  Third parameter:    Desired base to convert to
 */
echo convert(12,10,16); // Converts decimal '12' to hex. Returns 'c'
echo '<br>';
echo convert('f',16,2); // Converts hex 'f' to dual. Returns '1111'
echo '<br>';
echo convert('1111',2,16);
echo '<br>';
echo convert('8172468712',10,90); // Base 90 is the max-length of the default base
echo '<br>';

/**
 *  You can also specify both or one of the bases yourself
 */ 
echo convert('hello world!', base('abcdefghijklmnopqrstuvwxyz !'), 2);
echo '<br>';
echo convert('bbb', base('abcdef') , 10);
echo '<br>';
echo convert('*=.2;3+2++' , base('2345Ah;.j=*+') , base('/8a9fN§Bj%1a9'));
?>