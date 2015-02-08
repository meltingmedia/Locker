<?php

/**
 * A simple processor to unlock the manager
 */
class UnLockManager extends BaseLockerProcessor
{
    public function process()
    {
        $locked = $this->service->unlock();
        if (!$locked) {
            return $this->failure('Error while trying to unlock the manager');
        }

        return $this->success();
    }
}

return 'UnLockManager';
