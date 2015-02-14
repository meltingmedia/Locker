<?php namespace Locker\Console;

use MODX\Command\ProcessorCmd;
use Symfony\Component\Console\Input\InputArgument;

class Unlock extends ProcessorCmd
{
    protected $processor = 'unlock';

    protected $name = 'manager:unlock';
    protected $description = 'Unlock the manager';
    protected $service = 'locker';

    protected function processResponse(array $response = array())
    {
        $this->info($response['message']);
    }

    protected function beforeRun(array &$properties = array(), array &$options = array())
    {
        /** @var \Locker $service */
        $service = $this->getApplication()->getMODX()->getService($this->service);
        $options['processors_path'] = $service->config['processors_path'];
    }
}
