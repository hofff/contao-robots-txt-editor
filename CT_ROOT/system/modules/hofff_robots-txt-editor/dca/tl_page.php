<?php

$GLOBALS['TL_DCA']['tl_page']['config']['onload_callback'][] = array('tl_page_hofff_robots_txt_editor', 'modifyPaletteAndFields');

$GLOBALS['TL_DCA']['tl_page']['fields']['dns']['eval']['mandatory'] = true;

$GLOBALS['TL_DCA']['tl_page']['fields']['fallback']['eval']['submitOnChange'] = true;

$GLOBALS['TL_DCA']['tl_page']['fields']['createRobotsTxt'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_page']['createRobotsTxt'],
  'exclude'                 => true,
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50', 'submitOnChange'=>true),
  'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_page']['fields']['robotsTxtContent'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_page']['robotsTxtContent'],
  'exclude'                 => true,
  'inputType'               => 'textarea',
  'eval'                    => array('style'=>' min-height:60px', 'tl_class'=>'clr'),
  'xlabel' => array
  (
    array('tl_page_hofff_robots_txt_editor', 'robotsTxtImportWizard')
  ),
  'sql'                     => "text NULL"
);
$GLOBALS['TL_DCA']['tl_page']['fields']['useDomainSpecificRobotsTxt'] = array
(
  'label'                   => Hofff\Contao\RobotsTxtEditor\RobotsTxtEditor::isHtaccessEnabled() ? $GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessInstalled'] : $GLOBALS['TL_LANG']['tl_page']['useDomainSpecificRobotsTxt_htaccessNotInstalled'],
  'exclude'                 => true,
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50', 'submitOnChange'=>true, 'disabled'=>!Hofff\Contao\RobotsTxtEditor\RobotsTxtEditor::isHtaccessEnabled()),
  'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_page']['fields']['robotsTxtAddAbsoluteSitemapPath'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_page']['robotsTxtAddAbsoluteSitemapPath'],
  'exclude'                 => true,
  'inputType'               => 'checkbox',
  'eval'                    => array('tl_class'=>'w50 clr'),
  'sql'                     => "char(1) NOT NULL default ''"
);

/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][] = array('tl_page_hofff_robots_txt_editor', 'updateRobotsTxt');
$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][] = array('tl_page_hofff_robots_txt_editor', 'updateHtaccess');

/**
 * Class tl_page_hofff_robots_txt_editor
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Hofff.com 2016-2016
 * @author     Cliff Parnitzky <cliff@hofff.com>
 * @package    Core
 */
class tl_page_hofff_robots_txt_editor extends tl_page
{
  /**
   * Import the back end user object
   */
  public function __construct()
  {
    parent::__construct();
  }
  
  /**
   * Modify the pallete and fields for this page
   */
  public function modifyPaletteAndFields($dc)
  {
    $objPage = \Database::getInstance()->prepare("SELECT * FROM tl_page WHERE id = ?")->execute($dc->id);
    if ($objPage->next() && $objPage->fallback)
    {
      $arrLegends = explode(";", $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
      $legendKeyToInsert = 0;
      foreach($arrLegends as $legendKey=>$legend)
      {
        if (strpos($legend, "{sitemap") === 0)
        {
          $legendKeyToInsert = $legendKey;
          break;
        }
      }
      array_splice($arrLegends, $legendKeyToInsert, 0, "{robotstxt_legend:hide},createRobotsTxt");
      $GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = implode(";", $arrLegends);
      $GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = "createRobotsTxt";

      $GLOBALS['TL_DCA']['tl_page']['subpalettes']['createRobotsTxt'] = "robotsTxtContent,useDomainSpecificRobotsTxt";
      
      if ($objPage->createRobotsTxt)
      {
        $GLOBALS['TL_DCA']['tl_page']['subpalettes']['createSitemap'] = $GLOBALS['TL_DCA']['tl_page']['subpalettes']['createSitemap'] . ',robotsTxtAddAbsoluteSitemapPath';
      }
    }
  }

  /**
   * Update the robots.txt when the page was stored.
   */
  public function updateRobotsTxt(DataContainer $dc)
  {
    if (Hofff\Contao\RobotsTxtEditor\RobotsTxtEditor::generateRobotsTxts())
    {
      \Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['robotstxt_updated']);
    }
    else
    {
      \Message::addError($GLOBALS['TL_LANG']['ERR']['robotstxt_not_updated']);
    }
  }

  /**
   * Update the .htaccess when the page was stored.
   */
  public function updateHtaccess(DataContainer $dc)
  {
    $objHtaccess = Bit3\Contao\Htaccess\Htaccess::getInstance();
    $objHtaccess->update();
  }

  /**
   * Add a link to the robots.txt import wizard
   * @return string
   */
  public function robotsTxtImportWizard()
  {
    return ' <a href="' . $this->addToUrl('key=importRobotsTxt') . '" title="' . specialchars($GLOBALS['TL_LANG']['tl_page']['robotsTxtContentImport'][1]) . '" onclick="Backend.getScrollOffset()">' . Image::getHtml('theme_import.gif', $GLOBALS['TL_LANG']['tl_page']['robotsTxtContentImport'][0], 'style="vertical-align:text-bottom"') . '</a>';
  }
}