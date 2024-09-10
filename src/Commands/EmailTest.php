<?php

namespace Webmonks\ExceptionEmail\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Webmonks\ExceptionEmail\Exceptions\DummyException;
use Symfony\Component\Console\Application as ConsoleApplication;

class EmailTest extends Command
{
    protected $name = 'exceptionemail:test';
    protected $description = 'Check if exceptionemail is working.';
    private $config;

    /**
     * Create a new instance of the EmailTest command.
     *
     * @param Repository $config The configuration repository.
     * @return void
     */
    public function __construct(Repository $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * Handles the exception email test command.
     *
     * @throws Exception description of exception
     * @return void
     */
    public function handle()
    {
        $this->overrideConfig();

        try {
            // Capture DummyException and send email
            app('exceptionemail')->captureException(new DummyException);
            $this->info('ExceptionEmail is working fine ✅');
        } catch (Exception $e) {
            (new ConsoleApplication)->renderThrowable($e, $this->output);
        }
    }

    /**
     * Overrides the configuration to capture the DummyException.
     *
     * @return void
     */
    public function overrideConfig()
    {
        $this->config->set('exceptionemail.capture', [DummyException::class]);
    }
}
