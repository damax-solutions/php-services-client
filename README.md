# Damax Services Client

[![Build Status](https://travis-ci.org/damax-solutions/php-services-client.svg?branch=master)](https://travis-ci.org/damax-solutions/php-services-client) [![Coverage Status](https://coveralls.io/repos/damax-solutions/php-services-client/badge.svg?branch=master&service=github)](https://coveralls.io/github/damax-solutions/php-services-client?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/damax-solutions/php-services-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/damax-solutions/php-services-client/?branch=master)

PHP client for Damax Services. Provides integration with [Symfony Framework](https://github.com/symfony/symfony).

## Description

This is the _HTTP_ client for the services provided by _Damax Solutions_.

#### Invalid passports

Check if passport number is listed in invalid passports database of [Ministry of Internal Affairs](https://xn--b1aew.xn--p1ai) or Russian Federation.

Examples:

- [check passport](examples/check_passport.php),
- [check multiple passports](examples/check_multiple_passports.php),
- [download passport check](examples/download_passport_check.php) as _PDF_ document.

#### Federal financial monitoring service

Check if person is listed as participant of extremistic activity or terrorism by [Rosfinmonitoring](http://www.fedsfm.ru/en).

See [example](examples/check_rosfin.php).

## Description in Russian

- Проверка паспорта по [списку недействительных российских паспортов](http://xn--b1afk4ade4e.xn--b1ab2a0a.xn--b1aew.xn--p1ai/info-service.htm?sid=2000) МВД.
- Поиск по перечню организаций и физических лиц [Росфинмониторинга](http://www.fedsfm.ru/documents/terr-list).

## Access

Contact _Damax Solutions_ to get an API key.

## Contribute

Install dependencies and run tests:

```bash
$ make
```
