<?php

/**
 * A base processor with locker service
 */
abstract class BaseLockerProcessor extends modProcessor
{
    /**
     * @var iLocker
     */
    public $service;

    public function initialize()
    {
        $this->getLocker();

        return parent::initialize();
    }

    protected function getLocker()
    {
        $path = $this->modx->getOption('locker.core_path', null, $this->modx->getOption('core_path') . 'components/locker/');
        $lockerPath = $this->modx->getOption('locker.class_path', null, $path);
        $lockerClass = $this->modx->getOption('locker.class_name', null, 'services.Locker');

        $this->service = $this->modx->getService('locker', $lockerClass, $lockerPath);
    }
}
