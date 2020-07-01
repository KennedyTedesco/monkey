--TEST--
Testing a fibonacci sequence using loop
--FILE--
let fibonacci = fn(num) {
    let a = 0;
    let b = 1;
    let temp = 0;

    while (num > 0) {
        temp = b;
        b = b + a;
        a = temp;

        num--;
    }

    return a;
};

puts(fibonacci(32));
--EXPECT--
2178309
