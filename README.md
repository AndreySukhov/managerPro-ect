# README

A simple Symfony project

## Install

```
$ composer install
```

```
$ bin/console doctrine:database:create
$ bin/console doctrine:migrations:migrate
$ bin/console doctrine:fixtures:load
```

Run server

```
$ bin/console server:start
```

Login and password (in parameters.yml) - By default root / pa$$word

## Tests

It's easy

```
$ phpunit
```
