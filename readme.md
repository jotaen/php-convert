php-convert
===========


This is a tool for converting a number/string from and to an arbitrary basis. You can not only convert dual->hex or decimal->octal or base32->decimal, but you can rather make crazy things like converting this string `d7+2*f#aks` into base 7.

Please take the time to read and understand this readme complete in order to avoid suffering surprises.


# Example #



# Function signature #

The functions expects three parameters:

    convert($input, $from_base, $to_base);

* `$input` The first is the input, which should be converted. That could be either a decimal number or a string. So values like `33`, `"33"`, `'33'`, `'fU.*3'` are all fine.

* `$from_base` Specify the base, in which the input string is delivered. This can be either an integer (then the function uses the built-in default-base). Or you pass a `Base`-object, in which you specify a custom base (see below). For most cases, the default-base will be suitable – the range of allowed values is 1 to 90.

* `$to_base` Specify the base, to which the input should be converted. (Same rules as `$from_base`).

By the way: the task of input-validation is up to you! If you pass invalid parameters, the function will throw an exception, which can lead your script to terminate if you aren’t prepared!


# Default base #

The default base is:

    0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_./+*#,;:!?"\'@$%&()=[]{}<>|

It contains 91 chars, so which is thus the maximum value for the function call:

    convert(123, 10, 90);


# Custom bases #

If you want to use a different base for input and/or output, you have to specify the bases yourself.

    $from_base = base('abcdefghijklmno');
    $to_base   = base('12345');
    echo convert('hello', $from_base, $to_base);

You can write this, of course, also in a single line:

    echo convert('hello', base('abcdefghijklmno'), base('12345'));


# Three things you NEED to know! #

1. This function is able to throw exceptions. The reason is, that base-convertions aren’t trivial and there are a few things which can go wrong. Think of that:

    echo convert(978, 16, 10);
    echo convert('Hrg17', 62, 0);

You specify '978' to be a hex-value, but none of the chars are defined in the hex-system. In the second example, the destination-base is zero, which is impossible, because an empty base contains no characters.
In those cases, the function will throw an exception. And if you don’t handle them, your script will be terminated. To avoid this, you should wrap your function call in a try/catch-statement:

    try {
        $value = $_GET['user_input'];
        echo convert($value, 16, 10);
    }
    catch(Exception $e) {
        // this catch-block only is inoked, if something goes wrong
        echo $e;
    }

In particular, this is important, when you call the convert function with variables.
If you make function-calls with hardcoded numbers/strings, e.g.

    convert(123, 10, 2);

you don’t necessarily need to do try/catch, because you would be able to test it before.


2. I developed this tool on a private basis just for fun. I wrote a few testcases, to ensure it works as I expect it to work. Nevertheless, I grant/provide any warranties, not in the least! Use this on your own risk.