--TEST--
Testing array merge with the plus operator
--FILE--
print [1, 2, 3] + [4, "foo"];
--EXPECT--
[1, 2, 3, 4, "foo"]
