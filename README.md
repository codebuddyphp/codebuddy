# CodeBuddy

## All-in-One Code Quality Tool for Laravel

> **Note:** This package is currently compatible only with the Laravel framework.

## About
CodeBuddy is a wrapper around essential development tools that help maintain code quality in your Laravel projects. It integrates:

- **Rector** (automated code refactoring)
- **Pint** (code styling)
- **PHPStan** (static analysis)
- **PestPHP** (testing framework)

## Features
- One command setup for essential tools.
- CI/CD optimized validation.
- Automated fixes for coding standards.
- Code health reporting with email support.

## Commands

### Configure Code Quality Tools
```sh
php artisan codebuddy:configure
```
This command sets up **Rector, PestPHP, Pint, and PHPStan** with standard configurations.

### Run CI Checks
```sh
php artisan codebuddy:ci [--fix]
```
Runs tests, performs static analysis, and checks code style in a **dry-run mode** (does not modify files). Optimized for CI/CD pipelines.

- `--fix`: Automatically applies fixes for Rector and Pint where possible.

### Generate Code Quality Report
```sh
php artisan codebuddy:report [--show|--send-to=<email>]
```
- `--show`: Displays the overall code health report in the console.
- `--send-to=<email>`: Sends the report to the specified email address.

---

This package simplifies code quality enforcement, making it easier to maintain a high standard across your Laravel projects.
