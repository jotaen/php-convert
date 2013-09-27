<?php

class base {
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

function convert($input,$from,$to) {
    // basic setup
    $default = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_./+*#,;:!?"\'@$%&()=[]{}<>|';
    if (is_int($from)) {
        if ($from<0) trigger_error('convert() Second parameter must be positive.',E_USER_ERROR);
        if ($from>strlen($default)) trigger_error('convert() Second parameter cannot be bigger than 90, which is the length of default base.',E_USER_ERROR);
        $from = new base(substr($default,0,$from));
    }
    if (is_int($to)) {
        if ($to<0) trigger_error('convert() Third parameter must be positive.',E_USER_ERROR);
        if ($to>strlen($default)) trigger_error('convert() Third parameter cannot be bigger than 90, which is the length of default base.',E_USER_ERROR);
        $to = new base(substr($default,0,$to));
    }
    // to base 10:
    $l = strlen($input);
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