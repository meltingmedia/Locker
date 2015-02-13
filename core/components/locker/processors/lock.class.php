<?php

/**
 * A simple processor to lock the manager
 */
class LockManager extends BaseLockerProcessor
{
    public function process()
    {
        if ($this->service->isLocked()) {
            return $this->success('Manager already locked');
        }

        $locked = $this->service->lock();
        if (!$locked) {
            return $this->failure('Error while trying to lock the manager');
        }

        return $this->success('Manager locked');
    }
}

return 'LockManager';
