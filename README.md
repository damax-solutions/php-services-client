# Damax Services Client

[![Build Status](https://travis-ci.org/lakiboy/damax-services-client-php.svg?branch=master)](https://travis-ci.org/lakiboy/damax-services-client-php) [![Coverage Status](https://coveralls.io/repos/lakiboy/damax-services-client-php/badge.svg?branch=master&service=github)](https://coveralls.io/github/lakiboy/damax-services-client-php?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lakiboy/damax-services-client-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lakiboy/damax-services-client-php/?branch=master)

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

## Access

Contact _Damax Solutions_ to get an API key.

## Contribute

If you wish to contribute take a look how to [run the code locally](doc/development.md) in Docker.
