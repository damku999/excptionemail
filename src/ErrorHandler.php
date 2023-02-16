<?php

namespace Webmonks\ExceptionEmail;

use Illuminate\View\Factory;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Illuminate\Config\Repository;


class ErrorHandler
{
    /**
     * The view factory implementation.
     * 
     * @var \Illuminate\View\Factory
     */
    private $view;

    /**
     * The config implementation.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * Create a new exception handler instance.
     *
     * @param  \Illuminate\View\Factory $view
     * @param  \Illuminate\Config\Repository $config
     * @return void
     */

    public function __construct(Factory $view, Repository $config)
    {
        $this->view = $view;
        $this->config = $config;
    }

    /**
     * Create a string for the given exception.
     * 
     * @param  \Exception $exception
     * @return string
     */
    public function convertExceptionToString($exception)
    {
        return $this->view->make('exceptionemail::email.subject', compact('exception'))->render();
    }

    /**
     * Create a html for the given exception.
     *
     * @param  \Exception $exception
     * @return string
     */
    public function convertExceptionToHtml($exception)
    {
        $flat = $this->getFlattenedException($exception);
        $renderer = new HtmlErrorRenderer(true);
        if (!$this->isNotifyOnly()) {
            $content =  $renderer->getBody($flat);
        } else {
            $content = !empty($exception->getMessage()) ? $exception->getMessage() : 'Some errors on the server please check log file for more information';
        }

        return $this->decorate($content, $renderer->getStylesheet($flat), $flat);
    }

    /**
     * Checks if exceptionemail is silent.
     *
     * @return boolean
     */
    private function isNotifyOnly()
    {
        return $this->config->get('exceptionemail.notify_only', false);
    }

    /**
     * Converts the Exception in a PHP Exception to be able to serialize it.
     *
     * @param $exception
     * @return FlattenException
     */
    private function getFlattenedException($exception)
    {
        if (!$exception instanceof FlattenException) {
            $exception = FlattenException::createFromThrowable($exception);
        }

        return $exception;
    }

    /**
     * Get the html response content.
     *
     * @param  string $content
     * @param  string $css
     * @return string
     */
    private function decorate($content, $css, $exception)
    {
        if (!$this->isNotifyOnly()) {
            $content = $this->removeTitle($content);
        }
        return $this->view->make('exceptionemail::email.body', compact('content', 'css', 'exception'))->render();
    }

    /**
     * Removes title from content as it is same for all exceptions and has no real value.
     * 
     * @param  string $content
     * @return string
     */
    private function removeTitle($content)
    {
        $titles = [
            'Whoops, looks like something went wrong.',
            'Sorry, the page you are looking for could not be found.',
        ];

        foreach ($titles as $title) {
            $content = str_replace("<h1>{$title}</h1>", '', $content);
        }

        return $content;
    }
}
