<?php

$GLOBALS['TL_LANG']['tl_page']['robotstxt_legend'] = "robots.txt Editor";

$GLOBALS['TL_LANG']['tl_page']['createRobotsTxt']                                 = array("Eine robots.txt erstellen", "Eine individuelle robots.txt erstellen.");
$GLOBALS['TL_LANG']['tl_page']['robotsTxtContent']                                = array("Inhalt der robots.txt", "Geben Sie den Inhalt der robots.txt an.");
$GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessInstalled']    = array("Domainspezifische robots.txt verwenden", "Wählen Sie, ob die robots.txt domainspezifisch sein soll, d.h. für die Domain dieses Seitenbaums wird eine individuelle robots.txt im <i>share</i> Verzeichnis angelegt und in die .htaccess Datei wird eine entsprechende Rewrite Regel eingetragen.<br/><br/>Erstellt folgenden .htaccess Eintrag (beispielhaft):<br/><br/><i>" . sprintf($GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteCond'], "domain-a\.tld") . "<br/>" . sprintf($GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteRule'], "robots\.txt", "share/robots_alias\.txt") . "</i>");
$GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessNotInstalled'] = array($GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessInstalled'][0], "Für dieses Feature muss die Erweiterung <i>hofff/contao-htaccess</i> installiert und aktiv sein.<br/><br/>" . $GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessInstalled'][1]);

$GLOBALS['TL_LANG']['tl_page']['robotsTxtAddAbsoluteSitemapPath'] = array("Absoluten Sitemap Pfad zu robots.txt hinzufügen", "Den absoluten Pfad der Sitemap zur robots.txt hinzufügen.");

$GLOBALS['TL_LANG']['tl_page']['robotsTxtContentImport'] = array("Default robots.txt importieren", "Die default robots.txt importieren (überschreibt vorhandene Daten).");
