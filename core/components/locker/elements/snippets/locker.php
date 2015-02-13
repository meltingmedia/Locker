<?php
/**
 * Sample snippet to run any locker method
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$path = $modx->getOption(
    'locker.core_path',
    null,
    $modx->getOption('core_path') . 'components/locker/'
);

$lockerPath = $modx->getOption('locker.class_path', null, $path);
$lockerClass = $modx->getOption('locker.class_name', null, 'services.Locker');

/** @var iLocker $locker */
$locker = $modx->getService('locker', $lockerClass, $lockerPath);


$action = $modx->getOption('action', $scriptProperties);
if (!$action) {
    return '';
}

if (method_exists($locker, $action)) {
    return $locker->{$action}();
}
