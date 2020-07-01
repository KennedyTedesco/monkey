--TEST--
Testing the slice() function with a string
--FILE--
let foo = "abcdef";
let name = "Kennedy";
puts(slice(name, 0, 3), " ", slice(foo, 0, -1), " ", slice(foo, 2, -1), " ", slice(foo, -3, -1));
--EXPECT--
Ken abcde cde de
