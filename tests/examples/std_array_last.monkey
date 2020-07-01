--TEST--
Testing the first() function
--FILE--
let years = [2016, 2017, 2018, 2019, 2020, 2021, 2021, 2023, 2024, 2025, 2026];
puts(last(years));
--EXPECT--
2026
