--TEST--
Testing the push() function
--FILE--
let years = [2018, 2019, 2020];
years = push(years, 2021);
puts(last(years));
--EXPECT--
2021
