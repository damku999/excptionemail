<?php

namespace Darshan\ExceptionEmail\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Darshan\ExceptionEmail\Exceptions\DummyException;
use Symfony\Component\Console\Application as ConsoleApplication;

class EmailTest extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'exceptionemail:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if exceptionemail is working.';

    /**
     * The config implementation.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * Create a test command instance.
     *
     * @param  \Illuminate\Config\Repository $config
     * @return void
     */
    public function __construct(Repository $config)
    {
        parent::__construct();

        $this->config = $config;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->overrideConfig();

        try {
            app('exceptionemail')->captureException(new DummyException, true);

            $this->info('ExceptionEmail is working fine ✅');
        } catch (Exception $e) {
            (new ConsoleApplication)->renderThrowable($e, $this->output);
        }
    }

    /**
     * Overriding the default configurations.
     * 
     * @return void
     */
    public function overrideConfig()
    {
        $this->config->set('exceptionemail.capture', [DummyException::class]);
    }
}
