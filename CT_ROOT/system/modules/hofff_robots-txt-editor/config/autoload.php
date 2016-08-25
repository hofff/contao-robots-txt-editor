<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Hofff',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Hofff\Contao\RobotsTxtEditor\RobotsTxtEditor'               => 'system/modules/hofff_robots-txt-editor/classes/RobotsTxtEditor.php',
	'Hofff\Contao\RobotsTxtEditor\RobotsTxtEditorHtaccessWriter' => 'system/modules/hofff_robots-txt-editor/classes/RobotsTxtEditorHtaccessWriter.php',
));
