<?php

require_once('convert.php');
require_once('../testsuite/function.php');

$f = new testsuite('convert');

/*****************************************************
 * TEST CASES
 ****************************************************/

// Trivial tests:
$f('0',1,1)         ->eq = '0';
$f('746',10,10)     ->eq = '746';
$f('g',36,36)       ->eq = 'g';
$f('f',16,20)       ->eq = 'f';
$f('9',10,16)       ->eq = '9';
$f('Z',62,10)       ->eq = '61';

// Test against php core functions:
$f('13',10,16)      ->eq = dechex('13');
$f('f4',16,10)      ->eq = hexdec('f4');
$f('1000101',2,10)  ->eq = bindec('1000101');
$f('471',10,2)      ->eq = decbin('471');
$f('abc',36,10)     ->eq = base_convert('abc',36,10);
$f('100',36,10)     ->eq = base_convert('100',36,10);
$f('c42',14,4)      ->eq = base_convert('c42',14,4);
$f('ggp',30,17)     ->eq = base_convert('ggp',30,17);

// Long values:

?>