<?php
/**
 * @var modX $modx
 * @var array $sources
 */

/** @var modPlugin[] $plugins */
$plugins = array();
$i = 0;

$plugins[$i] = $modx->newObject('modPlugin');
$plugins[$i]->fromArray(array(
    'name' => 'Manager Locker',
    'description' => 'Handle manager locking state.',
    'plugincode' => Helper::getPHPContent($sources['elements'] . 'plugins/manager-locker.php'),
), '', true, true);

$properties = $sources['data'] . 'properties/manager-locker.php';
if (file_exists($properties)) {

}

$events = $sources['data'] . 'events/manager-locker.php';
if (file_exists($events)) {
    $vents = include_once $events;
    $modx->log(modX::LOG_LEVEL_INFO, 'Adding '. count($vents). ' system events to the plugin');
    $plugins[$i]->addMany($vents);
}

return $plugins;
