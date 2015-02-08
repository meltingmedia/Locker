<?php

/**
 * A simple processor to lock the manager
 */
class LockManager extends BaseLockerProcessor
{
    public function process()
    {
        $locked = $this->service->lock();
        if (!$locked) {
            return $this->failure('Error while trying to lock the manager');
        }

        return $this->success();
    }
}

return 'LockManager';
