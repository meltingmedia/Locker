<?php namespace Locker\Console;

use MODX\Command\ProcessorCmd;
use Symfony\Component\Console\Input\InputArgument;

class Status extends ProcessorCmd
{
    protected $processor = 'lockstatus';

    protected $name = 'manager:status-lock';
    protected $description = 'Get the manager "lock" status ';

    protected function processResponse(array $response = array())
    {
        $this->info($response['message']);
    }

    protected function beforeRun(array &$properties = array(), array &$options = array())
    {
        //echo print_r($options);

        $options['processors_path'] = '/home/labo/locker/core/processors/';
    }
}
