<?php
/**
 * @var modX $modx
 */

/** @var modSystemSetting[] $settings */
$settings = array();
$i = 0;

$settings[$i] = $modx->newObject('modSystemSetting');
$settings[$i]->fromArray(array(
    'key' => 'locker.flush_sessions_on_lock',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'locker',
), '', true, true);

$i++;

$settings[$i] = $modx->newObject('modSystemSetting');
$settings[$i]->fromArray(array(
    'key' => 'locker.locked',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'locker',
), '', true, true);

$i++;

$settings[$i] = $modx->newObject('modSystemSetting');
$settings[$i]->fromArray(array(
    'key' => 'locker.manager_locked_message',
    'value' => '[[$locker.manager_locked_message]]',
    'xtype' => 'text',
    'namespace' => 'locker',
), '', true, true);

$i++;

$settings[$i] = $modx->newObject('modSystemSetting');
$settings[$i]->fromArray(array(
    'key' => 'locker.status_off_on_lock',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'locker',
), '', true, true);

return $settings;
