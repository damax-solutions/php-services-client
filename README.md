## Development

Build image:

```bash
$ docker build -t damax-services-client-php .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-services-client-php composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-services-client-php ./vendor/bin/php-cs-fixer fix
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-services-client-php ./vendor/bin/simple-phpunit
$ docker run --rm -v $(pwd):/app -w /app damax-services-client-php ./bin/phpunit-coverage
```
