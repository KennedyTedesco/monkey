--TEST--
Testing a while expression
--FILE--
let foo = fn() {
    let x = 0;
    while (x < 100) {
        x++;
    }
    return x;
};

print foo();
--EXPECT--
100
