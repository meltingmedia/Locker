<?php
/**
 * @var modX $modx
 */

$events = array();

$events['OnManagerLogin'] = $modx->newObject('modPluginEvent');
$events['OnManagerLogin']->fromArray(array(
    'event' => 'OnManagerLogin',
), '', true, true);

$events['OnManagerLoginFormRender'] = $modx->newObject('modPluginEvent');
$events['OnManagerLoginFormRender']->fromArray(array(
    'event' => 'OnManagerLoginFormRender',
), '', true, true);

return $events;
