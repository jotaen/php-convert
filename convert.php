<?php

class Base {
    private $base;
    private $length;
    private $arr;
    public function __construct($s) {
        $this->base   = $s;
        $this->length = strlen($s);
        $this->arr    = str_split($s);
    }
    public function __toString() {return $this->base;}
    public function length() {return $this->length;}
    public function arr() {return $this->arr;}
}

function base ($base) {
    return new Base($base);
}

function convert($input,$from,$to) {
    // stringify, then split input:
    $input = str_split((string)$input);
    $l = count($input);
    // basic setup
    $default = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_./+*#,;:!?"\'@$%&()=[]{}<>|';
    if (is_int($from)) {
        if ($from<=1) throw new Exception('convert() Second parameter must be greather than 1.');
        if ($from>strlen($default)) throw new Exception('convert() Second parameter cannot be bigger than '.strlen($default).', which is the length of default base.');
        $from = new Base(substr($default,0,$from));
    }
    if (is_int($to)) {
        if ($to<=1) throw new Exception('convert() Third parameter must be greater than 1.');
        if ($to>strlen($default)) throw new Exception('convert() Third parameter cannot be bigger than '.strlen($default).', which is the length of default base.');
        $to = new Base(substr($default,0,$to));
    }
    if ($from->length()<=1||$to->length()<=1) {throw new Exception('convert() Don’t accept trivial bases. Base must contain two or more chars.');}
    // ensure, $input matches $from-base:
    if (count(array_intersect($input,$from->arr())) != $l) {throw new Exception('convert() Input must be subset of first base!');};
    // to base 10:
    $dec = strpos($from,$input[0]);
    for($i=1 ; $i<$l ; $i++) {
        $dec = $from->length() * $dec + strpos($from,$input[$i]);
    }
    if ($to=='0123456789') {return $dec;}
    // to desired base:
    $mod = $dec % $to->length();
    $res = $to->arr()[$mod];
    $lef = floor($dec / $to->length());
    while ($lef) {
        $mod = $lef % $to->length();
        $lef = floor($lef / $to->length());
        $res = $to->arr()[$mod].$res;
    }
    return $res;
}

?>