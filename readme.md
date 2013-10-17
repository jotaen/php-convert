php-convert
===========

This is a tool for converting a number/string from and to an arbitrary base. It’s able to do the basic stuff like converting dual into hex or decimal into octal and so on.

But you can rather make crazy things like converting this string `d7+2*f#aks` into a base, which just contains the characters `.` `,` `-` `;` `:` `_`.

Just convert, what you want!


Examples
--------
* Let’s do some warm-up with a basic job: Convert `123` from base 10 (decimal) to base 16 (hexadecimal):
    
        echo convert(123, 10, 16);
        // output: 7b

* That was easy. As well as this is: Convert `'1011100'` from base 2 (dual) to base 36 (numbers and lowercase letters):
        
        echo convert('1011100', 2, 36);
        // output: 2k

* Now, let’s define some custom bases:
        
        $from = base('abcdefghijklmnopqrstuvwxyz');
        $to   = base('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        echo convert('yippie', $from, $to);
        // output: YIPPIE

* And finally, do the crazy stuff:
        
        $from = base('ab#cd23f7*#89slk+');
        $to   = base('.,-;:_');
        echo convert('d7+2*f#aks', $from, $to);
        // output: ,._.;:;::_;_;.;_


Documentation
-------------

To get ready, just include the file [convert.php](convert.php):

    <?php
        require('convert.php');
    ?>

The function expects three parameters:

    convert($input, $from, $to);

* `$input` A string, which you want to convert. (You can also input a decimal number, so writing `33` or `'33'` or `"33"` would be the same here.)
* `$from` The base, in which the input string is delivered.
  * This can be either an integer (then the built-in default-base is used).
  * Or you pass a custom base with your own character-set. (Read section „Bases“ below for details.)
* `$to` Specify the base, to which the input should be converted. (Same as `$from`).

**Important!** The task of input-validation is up to you! Make sure, the input-string is subset of the first base and the bases itself are valid. If not, exceptions will be thrown. Read section „Input validation“ below for further explanation!


Bases
-----
You can either use the built-in default base, or you create a custom base.

### Default base ###
The default base contains the following 90 chars (in this order):

`0` `1` `2` `3` `4` `5` `6` `7` `8` `9` `a` `b` `c` `d` `e` `f` `g` `h` `i` `j` `k` `l` `m` `n` `o` `p` `q` `r` `s` `t` `u` `v` `w` `x` `y` `z` `A` `B` `C` `D` `E` `F` `G` `H` `I` `J` `K` `L` `M` `N` `O` `P` `Q` `R` `S` `T` `U` `V` `W` `X` `Y` `Z` `-` `_` `.` `/` `+` `*` `#` `,` `;` `:` `!` `?` `"` `'` `@` `$` `%` `&` `(` `)` `=` `[` `]` `{` `}` `<` `>` `|`

The convert-function will automatically use this default base, if you don’t specify your own base:

    convert(123, 10, 16);     // result: 7b
    convert('1az7r', 32, 62); // result: 9cbR
    convert(10001011, 2, 8);  // result: 213

You can also create the default bases as distinct objects:

    $from = default_base(10);
    $to   = default_base(16);
    convert(123, $from, $to);
    // result: 7b

The value-range for default bases is from 1 to 90. (If you break this range, an exception will be thrown!)


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

The convert-function is able to throw PHP-exceptions. The reason is, that base-conversions aren’t trivial and there are a few things which can go wrong. Think of that:

    convert(1378, 8, 10);
    convert('Hrg17', 0, 16);
    convert('Hrg17', 62, base(''));

What’s wrong here? Well, in the first example you specify `1378` to be an octal value, but the value itself isn’t subset of the octal-base (which consists of chars `01234567`). So the digit `8` is not defined in the octal-system.

In the second and third example, one of the bases are empty. And an empty base, which doesn’t contain any characters, is obviously invalid.

In those cases, the function will throw an exception. (If you are unfamiliar with the concept of exceptions, read [this](http://php.net/manual/en/language.exceptions.php). In brief: Unhandled exceptions can cause your script to be terminated!)
Especially when dealing with externally defined variables, you *have to* validate all of the input before processing it! There are two ways to deal with that:

### 1. Check input manually ###
When you create a base, you can call the `check_subset` method to check, whether all chars of some input-string are contained in this base.

    $input = 'Hello';
    $from  = base('abcdef');
    if ($from->check_subset($input)) {
        echo convert($input, $from, 10);
    }
    else {
        echo 'Error!';
    }

By the way: A base-object of the default base is created like so:

    $from = default_base(10);

### 2. Use try/catch ###
You could also wrap the function call in a try/catch-statement. If any exception is thrown in the try-block, the catch-block will be invoked, where you can handle the exception.

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

For running the testsuite ([tests.php](tests.php)) you need to provide my [php-testsuite](https://github.com/jotaen/php-testsuite). Anyway, you can also view the output [here](http://code.jotaen.net/exec/php-convert/tests.php).


License
-------

Copyright 2013 Jan Heuermann

Apache 2.0, see [License-file](LICENSE)
