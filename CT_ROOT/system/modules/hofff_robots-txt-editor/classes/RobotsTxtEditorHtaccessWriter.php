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
 * Class RobotsTxtEditorHtaccessWriter
 *
 * EventSubscriber to extend .htaccess file
 * @copyright  Hofff.com 2016-2016
 * @author     Cliff Parnitzky <cliff@hofff.com>
 * @package    Hofff_robots-txt-editor
 */
class RobotsTxtEditorHtaccessWriter implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {
    return array(
      \Bit3\Contao\Htaccess\HtaccessEvents::GENERATE_REWRITES => 'generateRewrites',
    );
  }

  /**
   * Generate this sub module code.
   *
   * @return string
   */
  public function generateRewrites(\Bit3\Contao\Htaccess\Event\GenerateRewritesEvent $event)
  {
    $objPages = \Contao\Database::getInstance()->prepare("SELECT alias, dns FROM tl_page WHERE createRobotsTxt = 1 AND useDomainSpecificRobotsTxt = 1 AND published = 1")->execute();
    
    while ($objPages->next())
    {
      $strRewriteRule = sprintf($GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteCond'], $this->prepareUrl($objPages->dns))//"domain-a\.tld")
                      . "\n"
                      . sprintf($GLOBALS['TL_CONFIG']['RobotsTxtEditorRewriteRule'], $this->prepareUrl(FILE_ROBOTS_TXT)/*"robots\.txt"*/, $this->prepareUrl(RobotsTxtEditor::getDomainSpecificFilePath($objPages->alias))/*"share/robots_alias\.txt"*/);
      $pre = $event->getPre();
      $pre->append(PHP_EOL . $strRewriteRule);
    }
  }
  
  private function prepareUrl($strUrl)
  {
    $strUrl = str_replace('.', '\.', $strUrl);
    return $strUrl;
  }
}