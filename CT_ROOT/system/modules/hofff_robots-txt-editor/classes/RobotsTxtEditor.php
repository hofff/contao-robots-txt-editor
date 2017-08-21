<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package Hofff_robots-txt-editor
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Hofff\Contao\RobotsTxtEditor;


/**
 * Class RobotsTxtEditor
 *
 * Helper to prepare options
 * @copyright  Hofff.com 2016-2016
 * @author     Cliff Parnitzky <cliff@hofff.com>
 * @package    Hofff_robots-txt-editor
 */
class RobotsTxtEditor extends \System
{
  /**
   * Import the default robots.txt
   * @param \DataContainer
   */
  public function importRobotsTxt(\DataContainer $dc)
  {
    if (\Input::get('key') != 'importRobotsTxt')
    {
      return '';
    }
    
    if (version_compare(VERSION, "4.4", '>='))
    {
      $strFileContent = $GLOBALS['ROBOTS_TXT']['DEFAULT'];
    }
    else
    {
      if (!file_exists(TL_ROOT . "/" . FILE_ROBOTS_TXT_DEFAULT))
      {
        \Message::addError($GLOBALS['TL_LANG']['ERR']['no_robotstxt_default']);
        $this->redirect(str_replace('&key=importRobotsTxt', '', \Environment::get('request')));
      }

      $objVersions = new \Versions($dc->table, \Input::get('id'));
      $objVersions->create();
      
      $strFileContent = file_get_contents(TL_ROOT . "/" . FILE_ROBOTS_TXT_DEFAULT);
    }

    \Database::getInstance()->prepare("UPDATE " . $dc->table . " SET robotsTxtContent=? WHERE id=?")
                            ->execute($strFileContent, \Input::get('id'));

    $this->redirect(str_replace('&key=importRobotsTxt', '', \Environment::get('request')));
  }
  
  /**
   * Generate the robots.txt files
   */
  public static function generateRobotsTxts()
  {
    // delete all existing domain specific robots.txt files
    foreach (scandir(static::getDomainSpecificFolderPath(true)) as $entry)
    {
      if (!is_dir($entry) &&
          ($pos = strpos($entry, FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_PREFIX)) !== FALSE && $pos == 0 &&
          ($pos = strpos($entry, FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_SUFFIX)) !== FALSE && $pos == (strlen($entry) - strlen(FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_SUFFIX)))
      {
        $filePathOld = static::getDomainSpecificFolderPath(true) . "/" . $entry;
          
        if (file_exists($filePathOld))
        {
          unlink($filePathOld);
        }
      }
    }
    
    // create all robots.txt files
    $blnGenerationSuccess = true;
    
    $objFallbackRootPage = static::getFallbackRootPages();
    while ($objFallbackRootPage->next())
    {
      $filePath = static::getSingleFilePath();
      
      if (static::isDomainSpecicCreationAllowed($objFallbackRootPage->useDomainSpecificRobotsTxt))
      {
        $filePath = static::getDomainSpecificFilePath($objFallbackRootPage->alias, true);
      }
      
      $fileContent = $objFallbackRootPage->robotsTxtContent;
      
      if ($objFallbackRootPage->createSitemap && $objFallbackRootPage->sitemapName != '' && $objFallbackRootPage->robotsTxtAddAbsoluteSitemapPath)
      {
        $strDomain = ($objFallbackRootPage->useSSL ? 'https://' : 'http://') . ($objFallbackRootPage->dns ?: \Environment::get('host')) . TL_PATH . '/'; 
        
        $fileContent .= "\n";
        $objRootPage = static::getRootPagesByDns($objFallbackRootPage->dns);
        while ($objRootPage->next())
        {
          $fileContent .= "Sitemap: " . $strDomain . "share/" . $objRootPage->sitemapName . ".xml\n";
        }
      }
      
      if (file_put_contents($filePath, $fileContent) === FALSE)
      {
        $blnGenerationSuccess = false;
      }
    }
    
    return $blnGenerationSuccess;
  }
  
  /**
   * Checks whether the extension 'htaccess' is installed and active.
   * @return True, if the extension 'htaccess' is installed and active.
   */
  public static function isHtaccessEnabled ()
  {
    return in_array('htaccess', \ModuleLoader::getActive());
  }
  
  /**
   * Return the path to the single file
   */
  public static function getSingleFilePath ()
  {
    $strPath = TL_ROOT;
    if (version_compare(VERSION, "4.4", '>='))
    {
      $strPath .= "/web";
    }
    
    return $strPath .= "/" . FILE_ROBOTS_TXT;
  }
  
  /**
   * Checks whether creation of a domain specific robots.txt is allowed.
   * @param $blnUseDomainSpecificRobotsTxt The value from the DataContainer.
   * @return True, if the extension 'htaccess' is installed and the parametrized value in the page is checked.
   */
  public static function isDomainSpecicCreationAllowed ($blnUseDomainSpecificRobotsTxt)
  {
    return static::isHtaccessEnabled() && $blnUseDomainSpecificRobotsTxt;
  }
  
  /**
   * Returns the file path to the domain specific robots.txt file.
   */
  public static function getDomainSpecificFolderPath ($blnFullPath = false)
  {
    $domainSpecificFolderPath = FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_Folder;
    if (version_compare(VERSION, "4.4", '>='))
    {
      $domainSpecificFolderPath = "web/" . $domainSpecificFolderPath;
    }
    if ($blnFullPath)
    {
      $domainSpecificFolderPath = TL_ROOT . "/" . $domainSpecificFolderPath;
    }
    return $domainSpecificFolderPath;
  }
  
  /**
   * Returns the file path to the domain specific robots.txt file.
   */
  public static function getDomainSpecificFilePath ($strAlias, $blnFullPath = false)
  {
    return static::getDomainSpecificFolderPath($blnFullPath) . "/" . FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_PREFIX . $strAlias . FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_SUFFIX;
  }
  
  /**
   * Returns the fallback root page for a dns.
   */
  public static function getFallbackRootPages()
  {
    return \Database::getInstance()->prepare("SELECT * FROM tl_page WHERE published = 1 AND fallback = 1 ")
                                   ->execute($strDns);
  }
  
  /**
   * Returns the root pages for a dns.
   */
  public static function getRootPagesByDns($strDns)
  {
    return \Database::getInstance()->prepare("SELECT * FROM tl_page WHERE published = 1 AND dns = ? ORDER BY fallback DESC, sorting")
                                   ->execute($strDns);
  }
}