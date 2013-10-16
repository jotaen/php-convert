php-convert
===========

This is a tool for converting a number/string from and to an arbitrary base. You can not only convert dual to hex or decimal to octal or base36 to decimal, but you can rather make crazy things like converting this string `d7+2*f#aks` into base 7.


Examples
--------
* Convert `123` from base `10` (decimal) to base `16` (hexadecimal):
    
        echo convert(123, 10, 16);
        // output: 7b

* Convert `'1011100'` from base `2` (dual) to base `36` (numbers and small letters):
        
        echo convert('1011100', 2, 36);
        // output: 2k

* Define custom bases:
        
        $from = base('abcdefghijklmnopqrstuvwxyz');
        $to   = base('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        echo convert('yippie', $from, $to);
        // output: YIPPIE

* Do the crazy stuff:
        
        $from = base('ab#cd23f7*#89slk+');
        echo convert('d7+2*f#aks', $from, 42);
        // output: 2dxyvyx5


Documentation
-------------

The function expects three parameters:

    convert($input, $from, $to);

* `$input` The first is the input, which you want to convert. That could be either a decimal number or a string. So these are all fine: `33` `"100"` `'ff0011'` `'_*2i.1'`
* `$from` Specify the base, in which the input string is delivered. This can be either an integer (then the built-in default-base is used). Or you pass a custom base with your own character-set. Read section „Bases“ below for details.
* `$to` Specify the base, to which the input should be converted. (Same rules as `$from`).

**Important!** The task of input-validation is up to you! If you pass invalid parameters, exceptions will be thrown. Read section „Input validation“ below!


Bases
-----
You can either use the built-in default base, or you create a custom base.

### Default base ###
The default contains the following 90 chars (in this order):

`0` `1` `2` `3` `4` `5` `6` `7` `8` `9` `a` `b` `c` `d` `e` `f` `g` `h` `i` `j` `k` `l` `m` `n` `o` `p` `q` `r` `s` `t` `u` `v` `w` `x` `y` `z` `A` `B` `C` `D` `E` `F` `G` `H` `I` `J` `K` `L` `M` `N` `O` `P` `Q` `R` `S` `T` `U` `V` `W` `X` `Y` `Z` `-` `_` `.` `/` `+` `*` `#` `,` `;` `:` `!` `?` `"` `\` `'` `@` `$` `%` `&` `(` `)` `=` `[` `]` `{` `}` `<` `>` `|`

The convert-function will automatically use this default base, if you don’t specify your own base:

    convert(123, 10, 16);     // result: 7b
    convert('1az7r', 32, 62); // result: 9cbR
    convert(10001011, 2, 8);  // result: 213

You can also create the default bases as objects:

    $from = default_base(10);
    $to   = default_base(16);
    convert(123, $from, $to);
    // result: 7b

The value-range for default bases is from 1 to 90. (If you break this range, an exception will be thrown.)


### Custom bases ###

If you want to use a different base for input and/or output, you can create your own bases with custom character-sets:

    $from = base('abcdefghijklmno');
    $to   = base('8|&ag2');
    convert('hello', $from, $to);
    // result: ||2a2&&2

You can write this, of course, also in a single line:

    convert('hello', base('abcdefghijklmno'), base('8|&ag2'));


Input validation
----------------

The convert-function is able to throw exceptions. The reason is, that base-conversions aren’t trivial and there are a few things which can go wrong. Think of that:

    convert(998, 8, 10);
    convert('Hrg17', 62, 0);
    convert('Hrg17', 62, base(''));

What’s wrong here? Well, in the first example you specify `998` to be an octal value, but none of its digits are defined in the octal-system (which consists of chars `01234567`). In the second and third example, the destination-bases doesn’t contain any characters, which is invalid.
In those cases, the function will throw an exception. (If you are unfamiliar with the concept of exceptions, read [this](http://php.net/manual/en/language.exceptions.php). In brief: Unhandled exceptions can cause your script to be terminated!)
Especially when dealing with externally defined variables, you *have to* validate all of the input before processing it!

### Manually check input ###
When you create a base, you can call the `check_subset` method to check, whether all chars of some input-string are contained in this base.

    $input = 'Hello';
    $from  = base('abcdef');
    if ($from->check_subset($input)) {
        echo convert($input, $from, 10);
    }
    else {
        echo 'Error!';
    }

By the way: If you want to use the default base, you can create them this way:

    $from = default_base(10);

### Use try/catch ###
You could also wrap the function call in a try/catch-statement. If an exception is thrown in the try-block, the catch-block will be invoked, where you can handle the exception.

    try {
        $input = 'Hello';
        $from  = base('abcdef');
        echo convert($input, $from, 10);
    }
    catch(Exception $e) {
        echo $e;
    }


Unit-Tests
----------

For running the testsuite ([tests.php](tests.php)) you need to provide my [php-testsuite](https://github.com/jotaen/php-testsuite). You can, however, view the output also [here](http://code.jotaen.net/exec/php-convert_base/tests.php).


License
-------

Copyright 2013 Jan Heuermann

Apache 2.0, see [License-file](LICENSE)