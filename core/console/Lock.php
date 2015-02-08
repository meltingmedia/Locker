<?php namespace Locker\Console;

use MODX\Command\ProcessorCmd;
use Symfony\Component\Console\Input\InputArgument;

class Lock extends ProcessorCmd
{
    protected $processor = 'lock';

    protected $name = 'manager:lock';
    protected $description = 'Lock the manager';


    protected function processResponse(array $response = array())
    {
        $id = $response['message'];

        $this->info('Result '. $id);
    }

    protected function beforeRun(array &$properties = array(), array &$options = array())
    {
        //echo print_r($options);

        $options['processors_path'] = '/home/labo/locker/core/processors/';
    }
}
