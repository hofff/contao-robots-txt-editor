<?php

/**
 * Add import operation
 */
$GLOBALS['BE_MOD']['design']['page']['importRobotsTxt'] = array('Hofff\Contao\RobotsTxtEditor\RobotsTxtEditor', 'importRobotsTxt');

/**
 * File constants 
 */
define('FILE_ROBOTS_TXT', 'robots.txt');
define('FILE_ROBOTS_TXT_DEFAULT', 'robots.txt.default');
define('FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_Folder', 'share');
define('FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_PREFIX', 'robots_');
define('FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_SUFFIX', '.txt');