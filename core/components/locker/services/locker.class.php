<?php
/**
 * @var modX $this
 *
 * @see xPDO::getService
 */
$iPath = $this->getOption('locker.core_path', null, $this->getOption('core_path') . 'components/locker/');
require_once $iPath . 'vendor/autoload.php';

/**
 * Locker service to prevent manager connexion from unauthorized users
 */
class Locker implements iLocker
{
    /**
     * @var modX
     */
    public $modx;

    public function __construct(modX $modx, array $options = array())
    {
        $this->modx = $modx;
        if (!$this->modx->lexicon) {
            $this->modx->getService('lexicon', $this->modx->getOption('lexicon_class', null, 'modLexicon'));
        }
        $this->modx->lexicon->load('locker:default');
    }

    public function lock()
    {
        if ($this->modx->getOption('locker.status_off_on_lock')) {
            $this->setSiteStatus(false);
        }

        if ($this->modx->getOption('locker.flush_sessions_on_lock')) {
            $this->flushSessions();
        }

        return $this->setLock(true);
    }

    public function unlock()
    {
        if ($this->modx->getOption('locker.status_off_on_lock')) {
            $this->setSiteStatus(true);
        }

        return $this->setLock(false);
    }

    public function isLocked()
    {
        return $this->getLockStorage()->get('value');
    }

    public function isUserAllowed()
    {
        /** @var modUser $user */
        $user = $this->modx->event->params['user'];

        // Assign user so we can make use of modX::hasPermission
        $this->modx->user = $user;

        $allowed = $this->modx->hasPermission('use_in_maintenance_mode');
        if (!$allowed) {
            // Grab attributes
            $attributes = $this->modx->event->params['attributes'];
            // Merge all context keys were the user is supposed to be logged in
            $ctx = array_merge(array('mgr'), $attributes['addContexts']);
            // Remove the sessions
            $user->removeSessionContext($ctx);
        }

        return $allowed;
    }

    /**
     * Convenient method to display an "error message" when a user tries to log into the manager when locked (and not being allowed to use the manager when locked)
     *
     * @return void
     */
    public function displayDenied()
    {
        $id = $this->modx->getOption('site_unavailable_page');
        if ($id) {
            $url = $this->modx->makeUrl($id, '', '', 'full');

            return $this->modx->sendRedirect($url);
        }

        return $this->modx->sendUnauthorizedPage();
    }

    /**
     * Convenient method to wipe all users sessions
     *
     * @return bool
     */
    protected function flushSessions()
    {
        /** @var modProcessorResponse $flushed */
        $flushed = $this->modx->runProcessor('security/flush');

        return $flushed->isError();
    }

    /**
     * Convenient method to toggle the site_status (online/offline) setting
     *
     * @param bool $status
     *
     * @return bool
     */
    protected function setSiteStatus($status)
    {
        /** @var modSystemSetting $setting */
        $setting = $this->modx->getObject('modSystemSetting', array(
            'key' => 'site_status'
        ));
        $setting->set('value', (bool) $status);

        $saved = $setting->save();
        if ($saved) {
            $this->modx->getCacheManager()->refresh();
        }

        return $saved;
    }
    /**
     * Convenient method to update the "lock" state value
     *
     * @param bool $value
     *
     * @return bool Whether or not the update went fine
     */
    protected function setLock($value)
    {
        $store = $this->getLockStorage();
        $store->set('value', $value);

        return $store->save();
    }

    /**
     * Convenient method to retrieve the system setting where the lock "state" is stored
     *
     * @return modSystemSetting
     */
    protected function getLockStorage()
    {
        /** @var modSystemSetting $setting */
        $setting = $this->modx->getObject('modSystemSetting', array(
            'key' => 'locker.locked',
        ));
        if (!$setting) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->fromArray(array(
                'key' => 'locker.locked',
                'xtype' => 'combo-boolean',
                'namespace' => 'locker',
                'value' => false,
            ), '', true, true);
            $setting->save();
        }

        return $setting;
    }

    /**
     * An array of CLI commands to register for MODX Shell
     *
     * @return array
     */
    public function getCommands()
    {
        return array(
            '\\Locker\\Console\\Lock',
            '\\Locker\\Console\\Unlock',
            '\\Locker\\Console\\Status',
        );
    }
}
