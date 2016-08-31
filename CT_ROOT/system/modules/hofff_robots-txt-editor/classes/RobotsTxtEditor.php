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
    
    if (!file_exists(TL_ROOT . "/" . FILE_ROBOTS_TXT_DEFAULT))
    {
      \Message::addError($GLOBALS['TL_LANG']['ERR']['no_robotstxt_default']);
      $this->redirect(str_replace('&key=importRobotsTxt', '', \Environment::get('request')));
    }

    $objVersions = new \Versions($dc->table, \Input::get('id'));
    $objVersions->create();
    
    $strFileContent = file_get_contents(TL_ROOT . "/" . FILE_ROBOTS_TXT_DEFAULT);

    \Database::getInstance()->prepare("UPDATE " . $dc->table . " SET robotsTxtContent=? WHERE id=?")
                            ->execute($strFileContent, \Input::get('id'));

    $this->redirect(str_replace('&key=importRobotsTxt', '', \Environment::get('request')));
  }
  
  /**
   * Create the robots.txt
   * @param \DataContainer
   */
  public function createRobotsTxt(\DataContainer $dc)
  {
    $filePath = TL_ROOT . "/" . FILE_ROBOTS_TXT;
    
    $objPage = $dc->activeRecord;
        
    if ($objPage != null)
    {
      if (static::isDomainSpecicCreationAllowed($dc->activeRecord->useDomainSpecificRobotsTxt))
      {
        $filePath = TL_ROOT . "/" . static::getDomainSpecificFilePath($dc->activeRecord->alias);
        
        // delete the old file, if the alias was changed
        $objOldPage = \Contao\Database::getInstance()->prepare("SELECT * FROM tl_version WHERE fromTable=? AND pid=? ORDER BY version DESC")
                                                   ->limit(1)
                                                   ->execute('tl_page', $dc->id);
        
        if ($objOldPage != null && ($strAliasOld = deserialize($objOldPage->data)['alias']) && $strAliasOld!= $objPage->alias)
        {
          \Message::addInfo($GLOBALS['TL_LANG']['MSC']['DomainSpecificRobotsTxt_cleared']);
          $filePathOld = TL_ROOT . "/" . static::getDomainSpecificFilePath($strAliasOld);
          
          if (file_exists($filePathOld))
          {
            unlink($filePathOld);
          }
        }
      }
      
      $fileContent = $objPage->robotsTxtContent;
      
      if ($objPage->createSitemap && $objPage->sitemapName != '' && $objPage->robotsTxtAddAbsoluteSitemapPath)
      {
        $strDomain = ($objPage->useSSL ? 'https://' : 'http://') . ($objPage->dns ?: \Environment::get('host')) . TL_PATH . '/'; 
        
        $fileContent .= "\n";
        $fileContent .= "Sitemap: " . $strDomain . "share/" . $objPage->sitemapName . ".xml";
      }
      
      if (file_put_contents($filePath, $fileContent) === FALSE)
      {
        return false;
      }
      else
      {
        return true;
      }
    }
    
    return false;
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
  public static function getDomainSpecificFilePath ($strAlias)
  {
    return FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_Folder . "/" . FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_PREFIX . $strAlias . FILE_ROBOTS_TXT_DOMAIN_SPECIFIC_SUFFIX;;
  }
}