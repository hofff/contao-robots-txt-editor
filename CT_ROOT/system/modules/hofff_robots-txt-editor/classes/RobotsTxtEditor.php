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
}