let fibonacci = fn(num) {
    let a = 0;
    let b = 1;
    let temp = 0;

    while (num > 0) {
        temp = b;
        b = b + a;
        a = temp;

        num = num - 1;
    }

    return a;
};

print fibonacci(32);
