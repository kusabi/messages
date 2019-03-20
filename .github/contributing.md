# Coding Standards

This library follows [PSR-1](https://www.php-fig.org/psr/psr-1/) & [PSR-2](https://www.php-fig.org/psr/psr-2/) standards.

Before pushing changes ensure you run the following commands (and they return successfully).

```bash
vendor/bin/php-cs-fixer fix
vendor/bin/codecept run
vendor/bin/phan
```

Code coverage should be checked whenever making changes.

```bash
php -d xdebug.profiler_enable=on vendor/bin/codecept run --coverage --coverage-xml --coverage-html
```
