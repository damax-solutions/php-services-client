## Development

Build image:

```bash
$ docker build -t damax-client .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-client composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-client ./vendor/bin/php-cs-fixer fix
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-client ./vendor/bin/simple-phpunit
$ docker run --rm -v $(pwd):/app -w /app damax-client ./bin/phpunit-coverage
```
