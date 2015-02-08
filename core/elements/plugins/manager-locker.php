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

// Check locked state
$locked = $locker->isLocked();

switch ($modx->event->name) {
    case 'OnManagerLoginFormRender':
        if ($locked) {
            // Display a warning in the login form
            $modx->event->output('<div class="error">We are in maintenance!</div>');
        }
        return '';
        break;

    case 'OnManagerLogin':
        if (!$locked) {
            return '';
        }

        // Make sure the user is allowed to use the manager
        if (!$locker->isUserAllowed()) {
            return $locker->displayDenied();
        }

        break;
}

return '';
