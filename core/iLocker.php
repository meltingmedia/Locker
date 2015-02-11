<?php

/**
 * A manager "locker" interface to implement
 */
interface iLocker
{
    /**
     * Set the lock to prevent users to log in the manager
     *
     * @return bool - Whether or not the lock was properly set
     */
    public function lock();

    /**
     * Remove the lock to allow regular users to log back into the manager
     *
     * @return bool
     */
    public function unlock();

    /**
     * Check whether or not the manager is currently considered as locked
     *
     * @return bool
     */
    public function isLocked();

    /**
     * Check whether or not the current user is allowed to use the manager while being in "lock" mode
     *
     * @return bool
     */
    public function isUserAllowed();

    /**
     * Display the "denied" message
     *
     * @return mixed
     */
    public function displayDenied();
}
