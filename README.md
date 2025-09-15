# OpenConext Saml Value Object

A set of value objects to be used within the SAML2 domain.

## Installation

```sh
composer require openconext/saml-value-object
```

## Local development (Docker)
A simple Compose file (compose.yaml) is provided with a single php (PHP 8.2 CLI) service.

### Start container
```sh
docker compose up -d
```

### Open a shell
```sh
docker compose exec php bash
```

### Install dependencies
```sh
composer install
```

### Run full quality check suite
Equivalent to CI:
```sh
composer check
```

## Releases

- **3.0** - Improved PHP 8.2 support and replaced abandoned dev tooling
- **2.0** - Basic php 8.2 support
- **1.0** - Initial release

## License

See the LICENSE file
