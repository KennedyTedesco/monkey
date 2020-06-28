--TEST--
Testing a while expression
--FILE--
let foo = fn() {
    let x = 0;
    while (x < 100) {
        x = x + 1;
    }
    return x;
};

print foo();
--EXPECT--
100
