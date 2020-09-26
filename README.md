<p align="center">
    <img src="resources/monkey-php.png?raw=true" alt="Monkey Programming Language">
</p>

## Monkey Programming Language

The Monkey Programming Language & Interpreter written in **PHP**.

**Features:**

- A REPL;
- Integers (`10`), floats (`10.5`), booleans (`false`, `true`), strings (`"foo"`), arrays (`[2016, "foo"]`), hash maps (TODO);
- Arithmetic expressions (`+`, `-`, `*`, `%`, `/`, `**`);
- Postfix operators (`i++`, `i--`)
- Comparison operators (`>`, `<`, `>=`, `<=`, `==`, `!=`)
- Conditional expressions (`if else`);
- Logical operators (`&&`, `||`)
- Loop (`while`);
- Let statements (`let a = 1`);
- First-class and higher-order functions (`let foo = fn(x) { x + 1 }`);
- Built-in functions (`puts()`, etc);
- Recursion;
- Closures;

**Future scope:**

- Enums;
- Classes and Objects;
- Match Expression;
- Regex support;

**Basic syntax:**

A Fibonacci sequence using recursion:

```javascript
let fibonacci = fn(x) {
    if (x == 0 || x == 1) {
        return x;
    }

    return fibonacci(x - 1) + fibonacci(x - 2);
};

puts(fibonacci(10));
```

A Fibonacci sequence using loop (much faster):

```javascript
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
```

A raw implementation of mapping an array:

```javascript
let rawMap = fn(arr, callback) {
    let iter = fn(arr, accumulated) {
        if (len(arr) == 0) {
            return accumulated;
        }

        return iter(slice(arr, 1), push(accumulated, callback(first(arr))));
    };

    return iter(arr, []);
};

let foo = rawMap([1, 2, 3, 4], fn(x) { x * 2 });

puts(foo); // [2, 4, 6, 8]
```

Or, you can just use the builtin function `map()`:

```javascript
let foo = map([1, 2, 3, 4], fn(x) { x * 2 });

puts(foo); // [2, 4, 6, 8]
```

See more examples [here](tests/examples).

(A working in progress. More features and docs soon.)

### Why?

This is just a C-like language and its interpreter that I built to learn and understand how lexers and parsers work. I hope that could be useful to you, at least, inspire you to create your interpreter to learn those things.

### Is it fast?

It's not so fast because this is a pure tree-walk interpreter (which is notable slow) written in a PHP (a high-level programming language), and PHP compiles to an intermediate code that runs on top of a Virtual Machine written in C. Also, speed isn't the goal. The goal here is to learn, play, and have fun with the foundation of interpreters.

### How it works?

This interpreter uses an approach called Tree-Walking, it parses the source code, builds an abstract syntax tree (AST), and then evaluates this tree.

The steps are:

1. **Lexical analysis:** from source code (free text) to Tokens/Lexemes;
2. **Parsing:** uses the generated tokens to create an Abstract Syntax Tree;
3. **Abstract Syntax Tree (AST):** a structural representation of the source code with its precedence level, associativity, etc;
4. **Evaluator:** runs through the AST evaluating all expressions.

<p align="center">
    <img src="resources/how-it-works.png?raw=true" alt="How it works">
</p>

### Running with Docker (PHP 8 only)

Pull the docker image:

```bash
docker pull keinos/php8-jit
```

Running the tests:

```bash
docker run --rm -v $(pwd):/monkey -w /monkey keinos/php8-jit ./vendor/bin/pest
```

If you're using a fish-like shell, omit the `$`:

```bash
docker run --rm -v (pwd):/monkey -w /monkey keinos/php8-jit ./vendor/bin/pest
```

Running from a file contents of the examples folder:

```bash
docker run --rm -v (pwd):/monkey -w /monkey keinos/php8-jit ./monkey run examples/fibo_while.monkey
```

### Using the REPL

Clone this repository, execute `composer install`, then:

```bash
docker run --rm -v (pwd):/monkey -w /monkey keinos/php8-jit ./monkey repl
```

Example:

```text
            __,__
   .--.  .-"     "-.  .--.
  / .. \/  .-. .-.  \/ .. \
 | |  '|  /   Y   \  |'  | |
 | \   \  \ 0 | 0 /  /   / |
  \ '- ,\.-"`` ``"-./, -' /
   `'-' /_   ^ ^   _\ '-'`
       |  \._   _./  |
       \   \ `~` /   /
        '._ '-=-' _.'
           '~---~'
-------------------------------
| Monkey Programming Language |
-------------------------------

 > let a = 20 + fn(x){ return x + 10; }(2);
32
```

Or, if you want to execute a file:

```bash
docker run --rm -v (pwd):/monkey -w /monkey keinos/php8-jit ./monkey run examples/closure.monkey
```

### Contributing

I'll be pleased to have you contributing to any aspect of this project. You can fix a bug, implement new functionality, or add more [tests](tests/examples).

### Credits

This language is a version of the incredible [Monkey Lang](https://monkeylang.org/) with some extra batteries included.
