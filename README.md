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

# Frontend

## OAuth

Create client (all grant types)

```
$ bin/console app:oauth-server:client:create --redirect-uri="http://localhost:8000" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh-token" --grant-type="token" --grant-type="client_credentials"
```

output:

```
<id>_<client_id>, secret <client_secret>
```

In production use only implicit grant. (authorization_code)
