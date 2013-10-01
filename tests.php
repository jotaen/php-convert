<?php

require_once('convert.php');


require_once(dirname(__FILE__).'/../php-testsuite/function.php');
$f = new testsuite('convert');

/*****************************************************
 * TEST CASES
 ****************************************************/

//
// Trivial tests
//
$f('0',2,2)         ['=='] = '0';
$f(1,10,10)         ['=='] = 1;
$f(1,10,10)         ['=='] = 1;
$f(746,10,10)       ['=='] = '746';
$f('g',36,36)       ['=='] = 'g';
$f('f',16,20)       ['=='] = 'f';
$f('9',10,16)       ['=='] = '9';
$f('Z',62,10)       ['=='] = '61';
$f('01',10,12)      ['=='] = '1';
$f('000009',10,12)  ['=='] = 9;
$f('|',90,10)  ['=='] = 89;

//
// Test against php core functions
//
$f('13',10,16)      ['=='] = dechex('13');
$f('f4',16,10)      ['=='] = hexdec('f4');
$f('374',10,8)      ['=='] = decoct('374');
$f('7374',8,10)     ['=='] = octdec('7374');
$f('1000101',2,10)  ['=='] = bindec('1000101');
$f('471',10,2)      ['=='] = decbin('471');
$f('abc',36,10)     ['=='] = base_convert('abc',36,10);
$f('100',36,10)     ['=='] = base_convert('100',36,10);
$f('c42',14,4)      ['=='] = base_convert('c42',14,4);
$f('ggp',30,17)     ['=='] = base_convert('ggp',30,17);

//
// Custom bases
//
$f(10,base('0123456789'),base('0123456789')) ['=='] = 10;
$f(10,base('01'),base('0123456789')) ['=='] = 2;
$f(10,base('10'),base('9876543210')) ['=='] = 8;
$f(10,base('0123456789'),base('9876543210')) ['=='] = 89;

//
// These must throw
//
// default-base <= 1 not allowed
$f('55',1,10)       ['throw'];
$f('55',10,1)       ['throw'];
$f('55',10,-8)      ['throw'];
$f('55',0,10)       ['throw'];
// default-base > 90 not allowed 
$f('123',91,10)     ['throw'];
$f('123',617,10)    ['throw'];
$f('123',10,91)     ['throw'];
$f('123',10,8122)   ['throw'];
// don’t accept trivial bases
$f('81728',base(''),7)   ['throw'];
$f('alkjh82',base('p'),7)   ['throw'];
// input value must be subset of first base
$f('abc',10,15)     ['throw'];
$f('abc',base('hui89ab'),90)   ['throw'];
$f('11182999930hj',base('18930hj'),9)   ['throw'];

?>