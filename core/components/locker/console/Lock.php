<?php namespace Locker\Console;

use MODX\Shell\Command\ProcessorCmd;
use Symfony\Component\Console\Input\InputArgument;

class Lock extends ProcessorCmd
{
    protected $processor = 'lock';

    protected $name = 'manager:lock';
    protected $description = 'Lock the manager';
    protected $service = 'locker';


    protected function processResponse(array $response = array())
    {
        $this->info($response['message']);
    }

    protected function beforeRun(array &$properties = array(), array &$options = array())
    {
        /** @var \Locker $service */
        $service = $this->modx->getService($this->service);
        $options['processors_path'] = $service->config['processors_path'];
    }
}
