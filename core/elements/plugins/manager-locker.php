<?php
/**
 * Plugin to prevent access to modX manager if in maintenance mode
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @event OnManagerLogin, OnManagerLoginFormRender
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

$locked = $locker->isLocked();

switch ($modx->event->name) {
    case 'OnManagerLoginFormRender':
        if ($locked) {
            $modx->event->output('<div class="error">We are in maintenance!</div>');
        }
        return '';
        break;

    case 'OnManagerLogin':
        if (!$locked) {
            return '';
        }

        // Am i allowed to use modX ?
        if (!$locker->isUserAllowed()) {
            return $locker->displayDenied();
        }

        break;
}

return '';
