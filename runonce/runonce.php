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
 * Class CreateDefaultRobotsTxt
 *
 * Runonce script
 * @copyright  Hofff.com 2016-2016
 * @author     Cliff Parnitzky <cliff@hofff.com>
 * @package    Hofff_robots-txt-editor
 */
class CreateDefaultRobotsTxt extends Controller
{
  /**
   * Initialize the object
   */
  public function __construct()
  {
      parent::__construct();
  }


  /**
   * Run the controller
   */
  public function run()
  {
      if (!file_exists(TL_ROOT . "/" . FILE_ROBOTS_TXT_DEFAULT))
      {
        
        if (copy(TL_ROOT . "/" . FILE_ROBOTS_TXT, TL_ROOT . "/" . FILE_ROBOTS_TXT_DEFAULT))
        {
          \System::log('Initial copied the "' . FILE_ROBOTS_TXT . '" to "' . FILE_ROBOTS_TXT_DEFAULT . '".', 'CreateDefaultRobotsTxt::run()', 'TL_INFO');
        }
        else
        {
          \System::log('Initial copying the "' . FILE_ROBOTS_TXT . '" failed.', 'CreateDefaultRobotsTxt::run()', 'TL_ERROR');
        }
      }
  }
}

/**
 * Instantiate controller
 */
$objCreateDefaultRobotsTxt = new CreateDefaultRobotsTxt();
$objCreateDefaultRobotsTxt->run();