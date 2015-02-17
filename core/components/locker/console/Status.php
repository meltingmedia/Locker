<?php namespace Locker\Console;

use MODX\Shell\Command\ProcessorCmd;

/**
 * A MODX Shell command to get the current "lock status" of the manager
 */
class Status extends ProcessorCmd
{
    protected $processor = 'lockstatus';

    protected $name = 'manager:status-lock';
    protected $description = 'Get the manager "lock" status ';
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
