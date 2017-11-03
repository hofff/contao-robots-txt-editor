<?php

/**
 * Add import operation
 */
$GLOBALS['BE_MOD']['design']['page']['importRobotsTxt'] = array('Hofff\Contao\RobotsTxtEditor\RobotsTxtEditor', 'importRobotsTxt');

/**
 * Default robots.txt content(used for import, if there is not default file)
 */
$GLOBALS['ROBOTS_TXT']['DEFAULT'] = "User-agent: *
Disallow: /check/
Disallow: /contao-manager.phar.php";

/**
 * File constants 
 */
define('FILE_ROBOTS_TXT', 'robots.txt');
define('FILE_ROBOTS_TXT_DEFAULT', 'robots.txt.default');
define('FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_Folder', 'share');
define('FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_PREFIX', 'robots_');
define('FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_SUFFIX', '.txt');

/**
 * Configuration
 */
// Rewrite condition and rule (escape % with %% !!!):
// RewriteCond %{HTTP_HOST} ^(www\.)?domain-a\.tld$
// RewriteRule ^robots\.txt share/robots_alias\.txt
$GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteCond'] = "RewriteCond %%{HTTP_HOST} ^(www\.)?%s$";
$GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteRule'] = "RewriteRule ^%s %s";

if (Hofff\Contao\RobotsTxtEditor\RobotsTxtEditor::isHtaccessEnabled())
{
  $GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Hofff\Contao\RobotsTxtEditor\RobotsTxtEditorHtaccessWriter';
}
