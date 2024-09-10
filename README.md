# Laravel Exception Notifications

An easy-to-use package for sending email notifications with stack traces whenever an exception occurs in your Laravel application.

![exceptionemail example image](exceptionemail.png?raw=true "ExceptionEmail")

## Installation Guide

### 1. Install via Composer

To install the package, run the following Composer command:

```bash
composer require darshan/exceptionemail
```

### 2. Configure Laravel

#### Breaking Changes for Laravel 11: Exception Handling in a Custom Service Provider

In Laravel 11, exception handling logic should be placed in a custom service provider. Follow these steps to set it up:

1. **Create a Custom Service Provider:**

Create a new service provider that will handle exceptions in your application. Add the following code in `app/Providers/ExceptionServiceProvider.php`:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Throwable;

class ExceptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Nothing here for now.
    }

    public function boot()
    {
        app()->error(function (Throwable $e) {
            app('exceptionemail')->captureException($e);
        });
    }
}
```

2. **Register the Service Provider:**

In `bootstrap/providers.php`, register your custom `ExceptionServiceProvider` by adding it to the `providers` array:

```php
return [
    // Other service providers...
    App\Providers\ExceptionServiceProvider::class,
],
```

#### Add ExceptionEmail's Exception Capturing (for version of Laravel below 11 like 8,9,10)

In order to capture exceptions and send emails (for Laravel versions prior to 11 or if you prefer using `Handler.php`), modify the `report` method in your `app/Exceptions/Handler.php` file:

```php
use Throwable;

public function report(Throwable $exception)
{
    app('exceptionemail')->captureException($exception);

    parent::report($exception);
}
```

### 3. Publish the Configuration File

Publish the ExceptionEmail configuration file by running the following Artisan command:

```bash
php artisan vendor:publish --provider="Webmonks\ExceptionEmail\ExceptionEmailServiceProvider"
```

This will create a configuration file at `config/exceptionemail.php`.

## Configuration

### Silent Mode

By default, the package is configured with `'silent' => true` to prevent sending exception emails in development environments, especially when `'debug' => true` is enabled in Laravel.

You can control this behavior with the following configuration option:

```php
'silent' => env('IS_EXCEPTION_EMAIL_SILENT', true),
```

To enable email notifications when exceptions occur, set `IS_EXCEPTION_EMAIL_SILENT=false` in your `.env` file.

### Capture Exceptions

You can specify which types of exceptions should trigger email notifications. By default, the package includes `Symfony\Component\Debug\Exception\FatalErrorException::class`.

```php
'capture' => [
    Symfony\Component\Debug\Exception\FatalErrorException::class,
],
```

To capture all exceptions, you can use the wildcard `'*'`:

```php
'capture' => [
    '*'
],
```

### Ignored Exceptions

You may define exceptions that should not trigger email notifications. This is done by adding them to the `ignored_exception` array.

```php
'ignored_exception' => [
    // Webmonks\ExceptionEmail\Exceptions\DummyException::class,
],
```

For example, to ignore `FatalErrorException`, use the following:

```php
'ignored_exception' => [
    Symfony\Component\Debug\Exception\FatalErrorException::class,
],
```

#### Usage in `Handler.php`

Update the `report` method in `app/Exceptions/Handler.php` to incorporate ignored exceptions:

```php
public function report(Exception $exception)
{
    if ($this->shouldReport($exception)) {
        app('exceptionemail')->captureException($exception);
    }

    parent::report($exception);
}
```

### Recipients

Specify the email addresses that should receive the exception notifications by updating the `to` array:

```php
'to' => [
    'hello@example.com',
],
```

### Ignored Bots

You can configure the package to ignore errors triggered by bots, like search engine crawlers. The default configuration includes common bots such as:

```php
'ignored_bots' => [
    'googlebot',
    'bingbot',
    'slurp', 
    'ia_archiver',
],
```

## Customizing Emails

To customize the subject and body of the error notification emails, publish the email templates by running:

```bash
php artisan vendor:publish --provider="Webmonks\ExceptionEmail\ExceptionEmailServiceProvider"
```

> **Note:** Only run this command once to avoid overwriting custom changes.

The email views will be published to `resources/views/vendor/exceptionemail`. You can modify the templates as needed, and you have access to the `$exception` object in the views.

## Testing the Integration

To verify that ExceptionEmail is correctly set up and working, use the following Artisan command:

```bash
php artisan exceptionemail:test
```

This command will throw a `Webmonks\ExceptionEmail\Exceptions\DummyException`, and the package will capture and send it as an email. If everything is set up correctly, you should receive the test email.

## Security

If you discover any security issues, please contact us directly via email at damku999@gmail.com, rather than opening an issue on GitHub.

## Credits

- [Darshan Baraiya](https://github.com/damku999)
- [Squareboat](https://github.com/squareboat/sneaker)
- [All Contributors](../../contributors)

## About Webmonks

[Webmonks](https://webmonks.in) is a product development startup based in Ahmedabad, India. You can explore all our open-source projects on [GitHub](https://github.com/damku999).

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md).
