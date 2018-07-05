# Development

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
$ docker run --rm -v $(pwd):/app -w /app damax-services-client-php composer cs
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-services-client-php composer test
$ docker run --rm -v $(pwd):/app -w /app damax-services-client-php composer test-cc
```
