<?php
/**
 * Build the transport package
 */

$tstart = microtime(true);
set_time_limit(0);
$root = dirname(__DIR__) . '/';

// Define package names
define('PKG_NAME', 'Locker');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));
$version = explode('-', file_get_contents($root . 'VERSION'));
define('PKG_VERSION', $version[0]);
define('PKG_RELEASE', $version[1]);

// Define build paths
$sources = array(
    'root'       => $root,
    'build'      => $root . '_build/',
    'data'       => $root . '_build/data/',
    'resolvers'  => $root . '_build/resolvers/',
    'validators' => $root . '_build/validators/',
    'chunks'     => $root . 'core/components/locker/elements/chunks/',
    'lexicon'    => $root . 'core/components/locker/elements/lexicon/',
    'elements'   => $root . 'core/components/locker/elements/',

    'assets'     => $root . 'assets/',
    'core'       => $root . 'core/components/locker/',

    'build_dir'  => '/home/_builds/locker/'
);
unset($root);

// Override with your own defines here (see build.config.sample.php)
require_once $sources['build'] . 'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once $sources['build'] . 'includes/helper.php';

// Instantiate modX
$modx = new modX();
$modx->initialize('mgr');
if (!XPDO_CLI_MODE) {
    echo '<pre>';
}
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
if (isset($sources['build_dir']) && !empty($sources['build_dir'])) {
    $exists = true;
    if (!file_exists($sources['build_dir'])) {
        $exists = mkdir($sources['build_dir'], null, true);
    }
    if ($exists) {
        $builder->directory = $sources['build_dir'];
    }
}
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/');

// Create category
/** @var $category modCategory */
$category = $modx->newObject('modCategory');
$category->set('id', 1);
$category->set('category', PKG_NAME);

// Add plugin
$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in plugins...');
$plugins = include $sources['data'] . 'plugins.php';
if (empty($plugins)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in plugins.');
}
$category->addMany($plugins);

// Create category vehicle
$attr = array(
    xPDOTransport::UNIQUE_KEY                => 'category',
    xPDOTransport::PRESERVE_KEYS             => false,
    xPDOTransport::UPDATE_OBJECT             => true,
    xPDOTransport::RELATED_OBJECTS           => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
        'Plugins' => array(
            xPDOTransport::PRESERVE_KEYS             => false,
            xPDOTransport::UPDATE_OBJECT             => true,
            xPDOTransport::UNIQUE_KEY                => 'name',
            xPDOTransport::RELATED_OBJECTS           => true,
            xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
                'PluginEvents' => array(
                    xPDOTransport::PRESERVE_KEYS => true,
                    xPDOTransport::UPDATE_OBJECT => false,
                    xPDOTransport::UNIQUE_KEY    => array('pluginid', 'event'),
                ),
            ),
        ),
    ),
);
$vehicle = $builder->createVehicle($category, $attr);

$modx->log(modX::LOG_LEVEL_INFO, 'Adding file resolvers to category...');
$vehicle->resolve('file', array(
    'source' => $sources['core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$vehicle->resolve('php', array(
    'source' => $sources['resolvers'] . 'console.php',
));
$builder->putVehicle($vehicle);

// Load system settings
$settings = include $sources['data'] . 'settings.php';
if (!is_array($settings)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in settings.');
} else {
    $attributes = array(
        xPDOTransport::UNIQUE_KEY    => 'key',
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => false,
    );
    foreach ($settings as $setting) {
        $vehicle = $builder->createVehicle($setting, $attributes);
        $builder->putVehicle($vehicle);
    }
    $modx->log(modX::LOG_LEVEL_INFO, 'Packaged in ' . count($settings) . ' System Settings.');
}
unset($settings, $setting, $attributes);

// Now pack in the license file, readme and setup options
$modx->log(modX::LOG_LEVEL_INFO, 'Adding package attributes and setup options...');
$builder->setPackageAttributes(array(
    'license'   => file_get_contents($sources['root'] . 'LICENSE.md'),
    'readme'    => file_get_contents($sources['root'] . 'README.md'),
    'changelog' => file_get_contents($sources['root'] . 'CHANGELOG.md'),
));

// Zip up package
$modx->log(modX::LOG_LEVEL_INFO, 'Packing up transport package zip...');
$builder->pack();

$tend = microtime(true);
$totalTime = sprintf("%2.4f s", ($tend - $tstart));
$modx->log(modX::LOG_LEVEL_INFO, "\n\nPackage Built. \nExecution time: {$totalTime}\n");
if (!XPDO_CLI_MODE) {
    echo '</pre>';
}
exit();
