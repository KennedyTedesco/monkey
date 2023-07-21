// In this implementation, we use a recursive function to calculate the nth Fibonacci number, but this is the slowest way to do it (in this parser).
// The fibo_while.monkey implementation is much faster.

let fibonacci = fn(x) {
    if (x == 0 || x == 1) {
        return x;
    }

    return fibonacci(x - 1) + fibonacci(x - 2);
};

puts(fibonacci(25));
