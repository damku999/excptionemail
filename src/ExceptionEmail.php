<?php

namespace Darshan\ExceptionEmail;

use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Config\Repository;
use Throwable;

class ExceptionEmail
{
    /**
     * The config implementation.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * The exception handler implementation.
     *
     * @var \Darshan\ExceptionEmail\ErrorHandler
     */
    private $handler;

    /**
     * The mailer instance.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * The log writer implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Create a new exceptionemail instance.
     *
     * @param  \Illuminate\Config\Repository $config
     * @param  \Darshan\ExceptionEmail\ErrorHandler $handler
     * @param  \Illuminate\Contracts\Mail\Mailer $mailer
     * @param  \Psr\Log\LoggerInterface $logger
     * @return void
     */
    public function __construct(
        Repository $config,
        ErrorHandler $handler,
        Mailer $mailer,
        LoggerInterface $logger
    )
    {
        $this->config = $config;

        $this->handler = $handler;

        $this->mailer = $mailer;

        $this->logger = $logger;
    }

    /**
     * Checks an exception which should be tracked and captures it if applicable.
     *
     * @param  Throwable|\Exception $exception
     * @return void
     */
    public function captureException($exception, $testing = false)
    {
        try {
            if ($this->isSilent()) {
                return;
            }

            if ($this->isExceptionFromBot()) {
                return;
            }
            if ($this->isIgnoredException()) {
                return;
            }
            if ($this->shouldCapture($exception)) {
                $this->capture($exception);
            }
        } catch (Throwable $e) {
            $this->logger->error(sprintf(
                'Exception thrown in ExceptionEmail when capturing an exception (%s: %s)',
                get_class($e),
                $e->getMessage()
            ));

            $this->logger->error($e);

            if ($testing) {
                throw $e;
            }
        }
    }

    /**
     * Capture an exception.
     *
     * @param  \Exception|Throwable $exception
     * @return void
     */
    private function capture($exception)
    {
        $recipients = $this->config->get('exceptionemail.to');

        $subject = $this->handler->convertExceptionToString($exception);

        $body = $this->handler->convertExceptionToHtml($exception);

        $this->mailer->to($recipients)->send(new ErrorMailer($subject, $body));
    }

    /**
     * Checks if exceptionemail is silent.
     *
     * @return boolean
     */
    private function isSilent()
    {
        return $this->config->get('exceptionemail.silent', false);
    }

    /**
     * Determine if the exception is in the "capture" list.
     *
     * @param  Throwable|\Exception $exception
     * @return boolean
     */
    private function shouldCapture($exception)
    {
        $capture = $this->config->get('exceptionemail.capture');

        if (! is_array($capture)) {
            return false;
        }

        if (in_array('*', $capture)) {
            return true;
        }

        foreach ($capture as $type) {
            if ($exception instanceof $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the exception is from the bot.
     *
     * @return boolean
     */
    private function isExceptionFromBot()
    {
        $ignored_bots = $this->config->get('exceptionemail.ignored_bots');

        $agent = array_key_exists('HTTP_USER_AGENT', $_SERVER)
                    ? strtolower($_SERVER['HTTP_USER_AGENT'])
                    : null;

        if (is_null($agent)) {
            return false;
        }

        foreach ($ignored_bots as $bot) {
            if ((strpos($agent, $bot) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the exception is from the into ignore.
     *
     * @return boolean
     */
    private function isIgnoredException()
    {
        $ignored_exception = $this->config->get('exceptionemail.ignored_exception');
        if (! is_array($ignored_exception)) {
            return false;
        }

        foreach ($ignored_exception as $type) {
            if ($exception instanceof $type) {
                return true;
            }
        }
        return false;
    }
}
