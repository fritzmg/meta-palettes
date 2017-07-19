<?php

/**
 * MetaPalettes for the Contao Open Source CMS
 *
 * @link      https://github.com/bit3/contao-meta-palettes
 * @copyright 2013-2014 bit3 UG
 * @copyright 2015-2017 Contao Community Alliance
 * @author    Tristan Lins <tristan.lins@infinitysoft.de>
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @package   MetaPalettes
 * @license   LGPL-3.0+
 */

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = ['cca.meta_palettes.listener.hook_listener', 'generatePalettes'];

$GLOBALS['TL_EVENTS']['dc-general.factory.build-data-definition'][] = array(
    'ContaoCommunityAlliance\MetaPalettes\MetaPalettesBuilder::process',
    200
);

/**
 * Backwards compatibility
 */
spl_autoload_register(function($class) {
    if ('MetaPalettes' === $class) {
        class_alias('ContaoCommunityAlliance\MetaPalettes\MetaPalettes', 'MetaPalettes');
        return true;
    }
    return false;
});
