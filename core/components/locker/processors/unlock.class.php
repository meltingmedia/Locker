<?php

/**
 * A simple processor to unlock the manager
 */
class UnLockManager extends BaseLockerProcessor
{
    public function process()
    {
        if (!$this->service->isLocked()) {
            return $this->success('Manager already unlocked');
        }

        $locked = $this->service->unlock();
        if (!$locked) {
            return $this->failure('Error while trying to unlock the manager');
        }

        return $this->success('Manager successfully unlocked');
    }
}

return 'UnLockManager';
