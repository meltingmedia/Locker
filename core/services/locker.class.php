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
    }

    public function lock(array $options = array())
    {
        // Flush all sessions ?
        if ($this->modx->getOption('locker.flush_sessions_on_lock')) {
            //
        }

        // site_status setting (for all contexts)
        if ($this->modx->getOption('locker.status_off_on_lock')) {
            //
        }

        return $this->setLock(true);
    }

    public function unlock(array $options = array())
    {
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
            $this->displayDenied();
        }

        return $allowed;
    }

    public function displayDenied()
    {
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Rendering "denied"/maintenance message');

        $this->modx->sendForward(
            $this->modx->getOption('site_unavailable_page'),
            array(
                'error_type' => '401',
                'error_header' => 'Status: 401 Unauthorized',
                'response_code' => 'Status: 401 Unauthorized',
                'error_message' => 'We are in maintenance',
                'error_pagetitle' => 'We are in maintenance',
            )
        );
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


    public function getCommands()
    {
        return array(
            '\\Locker\\Console\\Lock',
            '\\Locker\\Console\\Unlock',
            '\\Locker\\Console\\Status',
        );
    }
}
