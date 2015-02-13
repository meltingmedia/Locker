<?php

/**
 * A simple processor to get the "lock" status of the manager the manager
 */
class LockStatus extends BaseLockerProcessor
{
    public function process()
    {
        if ($this->service->isLocked()) {
            $message = 'Manager locked';
        } else {
            $message = 'Manager not locked';
        }

        return $this->success($message);
    }
}

return 'LockStatus';
