<?php
/**
 *  php-convert
 *
 *  Copyright 2013 Jan Heuermann
 *  http://jotaen.net  •  <jan@jotaen.net>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */


/**
 *  Class for representing a base
 */
class Base {
    private $base;
    public function __construct($input) {
        if ( ! is_array($input)) {
            $input = str_split((string)$input);
        }
        $this->base = $input;
    }
    /** (string) return base as string */
    public function __toString() {
        return implode('',$this->base);
    }
    /** (string) returns the char at particular index in base */
    public function char($index) {
        if ($index<0 || $index>=$this->length()) return null;
        return $this->base[$index];
    }
    /** (int) returns length of base */
    public function length() {
        return count($this->base);
    }
    /** (bool) checks, whether (string)/(array) $input is subset of base */
    public function check_subset($input) {
        if ( ! is_array($input)) $input = str_split((string)$input);
        $unique = array_unique($input);
        return ( count(array_intersect($input,$this->base)) == count($input) );
    }
}

/**
 *  Helper functions for creating bases
 */
function base($chars) {
    return new Base($chars);
}
function default_base($length) {
    $default = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_./+*#,;:!?"\'@$%&()=[]{}<>|';
    if ($length<1) {throw new Exception('default_base() : Length must be greather than 0, because empty bases are void.');}
    if ($length>strlen($default)) {throw new Exception('default_base() : Length cannot be bigger than '.strlen($default).' (which is the length of default base).');}
    $chars = substr($default,0,$length);
    return new Base($chars);
}

/**
 *  This function can convert numbers/strings from/to
 *  arbitrary bases
 *  \param      (string)        The string you want to convert. It’s
 *                              recommended to always „stringify“ your input.
 *  \param      (obj)/(int)     The base of the input string. If (int) is given,
 *                              default_base() is invoked to create the base.
 *  \param      (obj)/(int)     The destination base to convert to. If (int) is given,
 *                              default_base() is invoked to create the base.
 *  \exception                  Throws exceptions, if
 *                              * input-string isn’t subset of input-base
 *                              * one of the bases is empty
 *                              * invalid input is provided for base-parameters
 */
function convert($input,$from,$to) {
    // validate bases
    if (is_int($from)) $from = default_base($from);
    if (is_int($to)) $to = default_base($to);
    if ( ! ($from instanceof Base) || ! ($to instanceof Base)) {throw new Exception('convert() : One or both bases are not of class Base.');}
    // stringify, split and validate input:
    if ($input=='') return '';
    $input = str_split((string)$input);
    $l = count($input);
    if ( ! $from->check_subset($input)) {throw new Exception('convert() : Input must be subset of first base!');}
    // skip trivial conversions:
    if ($from==$to) return implode('',$input);
    // first, convert to base 10:
    $dec = strpos($from,$input[0]);
    for($i=1 ; $i<$l ; $i++) {
        $dec = $from->length() * $dec + strpos($from,$input[$i]);
    }
    if ($from->length()==1) { $dec=$l-1; }
    if ($to=='0123456789') {return $dec;}
    // skip trivial conversion:
    if ($to->length()==1) { return str_repeat($to->char(0),$dec+1); }
    // second, convert to desired base:
    $mod = $dec % $to->length();
    $res = $to->char($mod);
    $lef = floor($dec / $to->length());
    while ($lef) {
        $mod = $lef % $to->length();
        $lef = floor($lef / $to->length());
        $res = $to->char($mod).$res;
    }
    return $res;
}

?>