<?php

$GLOBALS['TL_LANG']['tl_page']['robotstxt_legend'] = "robots.txt Editor";

$GLOBALS['TL_LANG']['tl_page']['createRobotsTxt']                                 = array("Create a robots.txt", "Create a custom robots.txt file.");
$GLOBALS['TL_LANG']['tl_page']['robotsTxtContent']                                = array("Content of the robots.txt", "Please enter the content of the robots.txt file.");
$GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessInstalled']    = array("Use domain specific robots.txt", "Please select, if the robots.txt should be domain specific, i.e. for the domain of this page tree a custom robots.txt will be created in the <i>share</i> folder and in the .htaccess file a corresponding rewrite rule is added.<br/><br/>Creates the following .htaccess entry (exemplary):<br/><br/><i>" . sprintf($GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteCond'], "domain-a\.tld") . "<br/>" . sprintf($GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteRule'], "robots\.txt", "share/robots_alias\.txt") . "</i>");
$GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessNotInstalled'] = array($GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessInstalled'][0], "To use this feature the extension <i>hofff/contao-htaccess</i> has to be installed and must be active.<br/><br/>" . $GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessInstalled'][1]);


$GLOBALS['TL_LANG']['tl_page']['robotsTxtAddAbsoluteSitemapPath'] = array("Add absolute sitemap path to robots.txt", "Add the absolute path of the sitemap to the robots.txt");

$GLOBALS['TL_LANG']['tl_page']['robotsTxtContentImport'] = array("Import default robots.txt", "Import the default robots.txt (overwrites existing data).");
