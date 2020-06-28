let newAdder = fn(a, b) {
    return fn(c) {
        return a + b + c
    };
};

let adder = newAdder(1, 2);

print adder(8);