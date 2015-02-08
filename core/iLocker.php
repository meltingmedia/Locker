<?php

/**
 * A manager "locker" interface to implement
 */
interface iLocker
{
    /**
     * Set the lock to prevent users to log in the manager
     *
     * @param array $options - An optional array of options to perform the locking
     *
     * @return bool - Whether or not the lock was properly set
     */
    public function lock(array $options = array());

    /**
     * Remove the lock to allow regular users to log back into the manager
     *
     * @param array $options - An optional array of options to perform the "unlock"
     *
     * @return bool
     */
    public function unlock(array $options = array());

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
