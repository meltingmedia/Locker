<?php namespace Locker\Console;

use MODX\Command\ProcessorCmd;
use Symfony\Component\Console\Input\InputArgument;

class Unlock extends ProcessorCmd
{
    protected $processor = 'unlock';

    protected $name = 'manager:unlock';
    protected $description = 'Unlock the manager';

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
