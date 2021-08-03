# Laravel Exception Notifications

An easy way to send emails with stack trace whenever an exception occurs on the server for Laravel applications.

![exceptionemail example image](exceptionemail.png?raw=true "ExceptionEmail")

## Install

### Install via Composer

```
$ composer require darshan/exceptionemail
```
### Configure Laravel

### Add ExceptionEmail's Exception Capturing

Add exception capturing to `app/Exceptions/Handler.php`:

```php
use Throwable;
public function report(Throwable $exception)
{
    app('exceptionemail')->captureException($exception);

    parent::report($exception);
}
```

### Configuration File

Create ExceptionEmail configuration file  with this command:

```bash
php artisan vendor:publish --provider="Webmonks\ExceptionEmail\ExceptionEmailServiceProvider"
```

The config file will be published in  `config/exceptionemail.php`

Following are the configuration attributes used for the ExceptionEmail.

#### silent

The package comes with `'silent' => true,` configuration by default, since you probably don't want error emailing enabled on your development environment. Especially if you've set `'debug' => true,`.

```php
'silent' => env('IS_EXCEPTION_EMAIL_SILENT', true),
```

For sending emails when an exception occurs set `IS_EXCEPTION_EMAIL_SILENT=false` in your `.env` file.


#### capture

It contains the list of the exception types that should be captured. You can add your exceptions here for which you want to send error emails.

By default, the package has included `Symfony\Component\Debug\Exception\FatalErrorException::class`.

```php
'capture' => [
    Symfony\Component\Debug\Exception\FatalErrorException::class,
],
```

You can also use `'*'` in the `$capture` array which will, in return captures every exception.

```php
'capture' => [
    '*'
],
```

#### Ignore Exception to sending over emails.

By default, the package has included nothing to ignore exception email list 

```php
'ignored_exception' => [
    // Webmonks\ExceptionEmail\Exceptions\DummyException::class,
],
```
But if you want to ignore any specific type of exceptions you can add in the `$ignored_exception` array which will, in return ignored those specified exceptions.

#### Usage

```php
'ignored_exception' => [
    Symfony\Component\Debug\Exception\FatalErrorException::class,
],
```

To use this feature you need to add the following code in `app/Exceptions/Handler.php`:

```php
public function report(Exception $exception)
{
    if ($this->shouldReport($exception)) {
        app('exceptionemail')->captureException($exception);
    }

    parent::report($exception);
}
```

#### to

It's a list of recipients who will receive error emails.

```php
'to' => [
    // 'hello@example.com',
],
```

#### ignored_bots

It's a list of bots for where you would like to ignore sending error emails.

```php
'ignored_bots' => [
    'googlebot',        // Googlebot
    'bingbot',          // Microsoft Bingbot
    'slurp',            // Yahoo! Slurp
    'ia_archiver',      // Alexa
],
```

## Customize

Run the following command in order to customize the subject and body of the email

```bash
php artisan vendor:publish --provider="Webmonks\ExceptionEmail\ExceptionEmailServiceProvider"
```

> Note - Don't run this command again if you have already run it.

Now, subject and body views are located in the `resources/views/vendor/exceptionemail` directory for the emails.

We have passed the thrown exception object `$exception` in a view which you can use to customize according to your need.

## EmailTest
### Test your integration
To verify ExceptionEmail is configured correctly and our integration is working, use `exceptionemail:test` Artisan command:

```bash
$ PHP artisan exceptionemail:test
```

A `Webmonks\ExceptionEmail\Exceptions\DummyException` class will be thrown and captured by ExceptionEmail. The captured exception will appear in your configured email immediately.

## Security

If you discover any security-related issues, please email damku999@gmail.com instead of using the issue tracker.

## Credits

- [Darshan Baraiya](https://github.com/damku999)
- [squareboat](https://github.com/squareboat/sneaker)
- [All Contributors](../../contributors)

## About Webmonks

[Webmonks](https://webmonks.in) is a startup company for product development based in Ahmedabad, India. You'll find an overview of all our open source projects [on GitHub](https://github.com/damku999).

# License

The MIT License. Please see [License File](LICENSE.md) for more information. Copyright © 2020 [Webmonks](https://webmonks.in)
