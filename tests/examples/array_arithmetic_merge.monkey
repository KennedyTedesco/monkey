--TEST--
Testing array merge with the plus operator
--FILE--
let foo = [1, 2, 3] + [4, "foo"];
puts(foo);
--EXPECT--
[1, 2, 3, 4, "foo"]
