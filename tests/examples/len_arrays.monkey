--TEST--
Testing a builtin len function with an array
--FILE--
let years = [2015, 2016, 2017, 2018, 2019];
print len(years);
--EXPECT--
5