<p align="center">
    <img src="resources/monkey-php.png?raw=true" alt="Monkey Programming Language">
</p>

## Monkey Programming Language

The Monkey Programming Language & Interpreter written in **PHP**.

**Features:**

- A REPL;
- Integers, floats, booleans, strings, arrays, hash maps (TODO);
- Arithmetic expressions;
- Conditional expressions;
- While expression;
- Let statements;
- First-class and higher-order functions;
- Built-in functions;
- Recursion;
- Closures;

**Future scope:**

- More Logical Operators;
- More built-in functions;
- Enums;
- Classes and Objects;
- Match Expression;
- Etc.

**Basic syntax:**

```javascript
let fibonacci = fn(x) {
    if (x == 0 || x == 1) {
        return x;
    }

    return fibonacci(x - 1) + fibonacci(x - 2);
};

print fibonacci(10);
```

See more on [examples](examples) folder.

A working in progress. More features and docs soon.

### Why?

This is just a C-like language and its interpreter that I built to learn and understand how lexers and parsers work. I hope that could be useful to you, at least, inspire you to create your interpreter to learn those things.

### Is it fast?

Not. It's slow because this is a pure tree-walk interpreter (which is notable slow) written in a PHP (a high-level programming language), and PHP compiles to an intermediate code that runs on top of a Virtual Machine written in C. Also, speed isn't the goal. The goal here is to learn, play, and have fun with the foundation of interpreters.

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

### Using the REPL

Clone this repository, execute `composer install`, then:

```bash
$ ./monkey repl
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

### Running on PHP 8 with Docker

Pull the docker image:

```bash
docker pull keinos/php8-jit:latest
```

Running the tests:

```bash
docker run --rm -v $(pwd):/monkey -w /monkey keinos/php8-jit:latest ./vendor/bin/pest
```

If you're using a fish-like shell:

```bash
docker run --rm -v (pwd):/monkey -w /monkey keinos/php8-jit:latest ./vendor/bin/pest
```

Running from a file contents of the examples folder:

```bash
docker run --rm -v (pwd):/monkey -w /monkey keinos/php8-jit:latest ./monkey run examples/fibo.mk
```

### Contributing

I'll be pleased to have you contributing to any aspect of this project.

### Credits

This language is a version of the incredible [Monkey Lang](https://monkeylang.org/) with some extra batteries included.
