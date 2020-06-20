<p align="center">
    <img src="resources/monkey-php.png?raw=true" alt="Monkey Programming Language">
</p>

## Monkey Programming Language

The Monkey Programming Language & Interpreter (Tree-Walk Interpreter) in PHP. From [Write An Interpreter In Go](https://interpreterbook.com/).

**Features:**

- A REPL
- Integers, booleans, strings, arrays (TODO), hash maps (TODO)
- Arithmetic expressions
- Let statements
- First-class and higher-order functions
- Built-in functions
- Recursion
- Closures

A working in progress. More docs soon.

### How it works

This interpreter uses an approach called Tree-Walking, it parses the source code, builds an abstract syntax tree (AST), and then evaluates this tree.

The steps are:

1. **Lexical analysis:** from source code to Tokens/Lexemes;
2. **Parsing:** uses the generated tokens to create an Abstract Syntax Tree;
3. **Abstract Syntax Tree (AST):** a structural representation of the source code with its precedence level, associativity, etc;
4. **Evaluator:** runs through the AST evaluating all operations.

<p align="center">
    <img src="resources/interpreter-steps.png?raw=true" alt="How it works">
</p>

## Running on PHP 8 with Docker

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
