# CodeBuddy

## All-in-one code quality tool for your codebase

> **Note:** This package is currently compatible only with the Laravel framework 11 & 12.


## Installation

Install the package as dev dependency:
```
composer require --dev codebuddyphp/codebuddy
```

Publish the assets:
```
php artisan vendor:publish --provider="Codebuddyphp\Codebuddy\CodebuddyServiceProvider" --tag="codebuddy-config" --force
```


## Usage

Configure essential tools (like Rector, Pint, Phpstan, Pest, etc):
```
php artisan cb:configure
```

Find or fix codebase issues:
```
php artisan cb:review [--fix]
```

Get quick codebase insights:
```
php artisan cb:insights
```
