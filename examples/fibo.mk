let fibonacci = fn(x) {
    if (x == 0 || x == 1) {
        return x;
    }

    return fibonacci(x - 1) + fibonacci(x - 2);
};

fibonacci(10);
