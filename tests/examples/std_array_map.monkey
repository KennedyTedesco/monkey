--TEST--
Testing the array map function
--FILE--
let x = 10;
let foo = map([2, 4, 6, 8], fn(x) { x * 2 });

puts(foo, " ", x);
--EXPECT--
[4, 8, 12, 16] 10
