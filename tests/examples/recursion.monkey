--TEST--
Testing a fibonacci function
--FILE--
let fibonacci = fn(x) {
    if (x == 0 || x == 1) {
        return x;
    }

    return fibonacci(x - 1) + fibonacci(x - 2);
};

puts(fibonacci(10));
--EXPECT--
55
