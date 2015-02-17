<?php
/**
 * Console resolver
 * Register Locker service as "command provider"
 *
 * @see xPDOVehicle::resolve
 *
 * @var xPDOVehicle $this
 * @var xPDOTransport $transport
 * @var xPDOObject|mixed $object
 * @var array $options
 *
 * @var array $fileMeta
 * @var string $fileName
 * @var string $fileSource
 *
 * @var array $r
 * @var string $type (file/php), obviously php :)
 * @var string $body (json)
 * @var integer $preExistingMode
 */

if ($object->xpdo) {
    /** @var $modx modX */
    $modx =& $object->xpdo;

    $service = 'Locker';
    $lower = strtolower($service);

    $key = 'console_commands';
    $data = array(
        $lower => array(
            'service' => $service,
        ),
    );
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject('modSystemSetting', $key);
    if (!$setting) {
        $setting = $modx->newObject('modSystemSetting');
        $setting->set('key', $key);
    }
    $value = $setting->get('value');
    if (!$value || empty($value)) {
        $value = '{}';
    }
    $registered = $modx->fromJSON($value);


    if ($options[xPDOTransport::PACKAGE_ACTION] == xPDOTransport::ACTION_INSTALL ||
        $options[xPDOTransport::PACKAGE_ACTION] == xPDOTransport::ACTION_UPGRADE
    ) {
        // Merge existing services with this one
        $registered = array_merge($registered, $data);
    } else {
        // Uninstall, remove service
        unset($registered[$lower]);
    }

    $setting->set('value', $modx->toJSON($registered));
    $setting->save();
}
