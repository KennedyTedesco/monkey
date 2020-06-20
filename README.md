## Monkey Programming Language

<p align="center">
    <img src="resources/monkey-php.png?raw=true" alt="Monkey Programming Language">
</p>

A working in progress. More docs soon.

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
