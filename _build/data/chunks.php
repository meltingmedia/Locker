<?php
/**
 * @var modX $modx
 * @var array $sources
 */

/** @var modChunk[] $chunks */
$chunks = array();
$i = 0;

$chunks[$i] = $modx->newObject('modPlugin');
$chunks[$i]->fromArray(array(
    'name' => 'Manager Locker',
    'description' => 'Handle manager locking state.',
    'plugincode' => file_get_contents($sources['elements'] . 'chunks/manager_locked_message.html'),
), '', true, true);

return $chunks;
