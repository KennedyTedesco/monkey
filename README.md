<p align="center">
    <img src="resources/monkey-php.png?raw=true" alt="Monkey Programming Language">
</p>

## Monkey Programming Language

The Monkey Programming Language & Interpreter in **PHP**. From the awesome [Write An Interpreter In Go](https://interpreterbook.com/).

**Features:**

- A REPL;
- Integers, booleans, strings, arrays (TODO), hash maps (TODO);
- Arithmetic expressions;
- Conditional expressions;
- Let statements;
- First-class and higher-order functions;
- Built-in functions;
- Recursion;
- Closures;

Future scope:

- Loops;
- More built-in functions;
- Etc.

A working in progress. More docs soon.

### Why?

This is just a C-like language and its interpreter that I built to learn and understand how things work, how lexers and parsers work. I hope that could be useful to you, at least, inspire you to create your interpreter.

### How it works?

This interpreter uses an approach called Tree-Walking, it parses the source code, builds an abstract syntax tree (AST), and then evaluates this tree.

The steps are:

1. **Lexical analysis:** from source code (free text) to Tokens/Lexemes;
2. **Parsing:** uses the generated tokens to create an Abstract Syntax Tree;
3. **Abstract Syntax Tree (AST):** a structural representation of the source code with its precedence level, associativity, etc;
4. **Evaluator:** runs through the AST evaluating all expressions.

<p align="center">
    <img src="resources/interpreter-steps.png?raw=true" alt="How it works">
</p>

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
