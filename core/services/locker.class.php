<?php
/**
 * @var modX $this
 *
 * @see xPDO::getService
 */
$iPath = $this->getOption('locker.core_path', null, $this->getOption('core_path') . 'components/locker/');
require_once $iPath . 'iLocker.php';

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
        // Set system setting "maintenance mode"
        $setting = $this->getLockStorage();
        $setting->set('value', true);

        // Flush all sessions ?

        return $setting->save();
    }

    public function unlock(array $options = array())
    {
        $setting = $this->getLockStorage();
        $setting->set('value', false);

        return $setting->save();
    }

    public function isLocked()
    {
        $this->modx->log(modX::LOG_LEVEL_INFO, 'checking locking state');
        return $this->getLockStorage()->get('value');
        // Read "maintenance mode" setting's value

        //return true;

        return false;
    }

    public function isUserAllowed()
    {
        /** @var modUser $user */
        $user = $this->modx->event->params['user'];
        //$this->modx->log(modX::LOG_LEVEL_INFO, 'Checking if user is allowed to log in maintenance mode : '. print_r($user->toArray(), true));
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Checking if user is allowed to log in lock mode');

        // Assign user so we can make use of modX::hasPermission
        $this->modx->user = $user;

        // Grab attributes
        $attributes = $this->modx->event->params['attributes'];
        //$this->modx->log(modX::LOG_LEVEL_INFO, 'attributes : '. print_r($attributes, true));

        $allowed = $this->modx->hasPermission('use_in_maintenance_mode');
        if (!$allowed) {
            // Merge all context keys were the user is supposed to be logged in
            $ctx = array_merge(array('mgr'), $attributes['addContexts']);
            // Remove the sessions
            $user->removeSessionContext($ctx);
            $this->displayDenied();
        }
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Allowed .!? '. $allowed);

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
}
