--TEST--
Testing the slice() function with an array
--FILE--
let foo = ["a", "b", "c", "d", "e"];
print slice(foo, 0, 1);
print slice(foo, 1, 3);
print slice(foo, -1, 1);
--EXPECT--
[a][b, c, d][e]
