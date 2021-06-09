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
public function report(Exception $exception)
{
    app('exceptionemail')->captureException($exception);

    parent::report($exception);
}
```

### Configuration File

Create the ExceptionEmail configuration file  with this command:

```bash
$ php artisan vendor:publish --provider="Darshan\ExceptionEmail\ExceptionEmailServiceProvider"
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

You can also use `'*'` in the `$capture` array which will in turn captures every exception.

```php
'capture' => [
    '*'
],
```

To use this feature you should add the following code in `app/Exceptions/Handler.php`:

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

This is the list of recipients of error emails.

```php
'to' => [
    // 'hello@example.com',
],
```

#### ignored_bots

This is the list of bots for which we should NOT send error emails.

```php
'ignored_bots' => [
    'googlebot',        // Googlebot
    'bingbot',          // Microsoft Bingbot
    'slurp',            // Yahoo! Slurp
    'ia_archiver',      // Alexa
],
```

## Customize

If you need to customize the subject and body of email, run following command:

```bash
$ php artisan vendor:publish --provider="Darshan\ExceptionEmail\ExceptionEmailServiceProvider"
```

> Note - Don't run this command again if you have run it already.

Now the email's subject and body view are located in the `resources/views/vendor/exceptionemail` directory.

We have passed the thrown exception object `$exception` in the view which you can use to customize the view to fit your needs.

## EmailTest
### Test your integration
To verify that ExceptionEmail is configured correctly and our integration is working, use `exceptionemail:test` Artisan command:

```bash
$ php artisan exceptionemail:test
```

A `Darshan\ExceptionEmail\Exceptions\DummyException` class will be thrown and captured by ExceptionEmail. The captured exception will appear in your configured email immediately.

## Security

If you discover any security related issues, please email damku999@gmail.com instead of using the issue tracker.

## Credits

- [Darshan Baraiya](https://github.com/damku999)
- [All Contributors](../../contributors)

## About Darshan

[Darshan](https://webmonks.in) is a startup focused, product development company based in Ahmedabad, India. You'll find an overview of all our open source projects [on GitHub](https://github.com/Darshan).

# License

The MIT License. Please see [License File](LICENSE.md) for more information. Copyright © 2020 [Darshan](https://webmonks.in)
