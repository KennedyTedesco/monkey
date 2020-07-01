--TEST--
Testing the slice() function with an array
--FILE--
let foo = ["a", "b", "c", "d", "e"];
puts(slice(foo, 0, 1), slice(foo, 1, 3), slice(foo, -1, 1));
--EXPECT--
["a"]["b", "c", "d"]["e"]
