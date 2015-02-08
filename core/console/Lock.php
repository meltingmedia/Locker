<?php namespace Locker\Console;

use MODX\Command\ProcessorCmd;
use Symfony\Component\Console\Input\InputArgument;

class Lock extends ProcessorCmd
{
    protected $processor = 'lock';

    //protected $required = array('name');

    protected $name = 'l:lock';
    protected $description = 'Lock the manager';

    protected function init()
    {
        $init = parent::init();

        //$this->info($this->modx->cmpstarter instanceof \CmpStarter);

        return $init;
    }

    protected function processResponse(array $response = array())
    {
        $id = $response['success'];

        $this->info('Result '. $id);
    }

//    protected function getArguments()
//    {
//        return array(
//            array(
//                'name',
//                InputArgument::REQUIRED,
//                'The item name'
//            ),
//        );
//    }

    protected function beforeRun(array &$properties = array(), array &$options = array())
    {
        //echo print_r($options);

        $options['processors_path'] = '/home/labo/locker/core/processors/';
    }
}
