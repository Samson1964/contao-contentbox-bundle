<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
 *
 * PHP version 5
 * @copyright  Frank Hoppe
 * @author     Frank Hoppe
 * @package    Contentbox
 * @license    LGPL
 * @filesource
 */

namespace Schachbulle\ContaoContentboxBundle\ContentElements;

/**
 * Class Reference
 *
 */
class Contentbox extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_contentbox';

	/**
	 * Generate the module
	 */
	protected function compile()
	{

		$daten = unserialize($this->contentbox);
		$content = '';

		foreach($daten as $item)
		{
			if($item['published'])
			{
				$content .= '<div>';
				if($item['headline']['value'])
				{
					$content .= '<'.$item['headline']['unit'].'>'.$item['headline']['value'].'</'.$item['headline']['unit'].'>';
				}
				// Bild extrahieren
				if($item['image'])
				{
					// Foto aus der Datenbank
					$objFile = \FilesModel::findByPk($item['image']);
					$bildgroesse = $item['size'];

					$objBild = new \stdClass();
					\Controller::addImageToTemplate($objBild, array('singleSRC' => $objFile->path, 'size' => $bildgroesse), \Config::get('maxImageWidth'), null, $objFile);

					$content .= '<figure class="image_container">';
					//$content .= '<a href="'.$objBild->singleSRC.'"><img src="'.$objBild->src.'" '.$objBild->imageSize.'></a>';
					$content .= '<img src="'.$objBild->src.'" '.$objBild->imageSize.'>';
					$content .= '</figure>'; 
				}

				if($item['text'])
				{
					$content .= '<div class="text">'.$item['text'].'</div>';
				}
				$content .= '</div>';
			}
		}

		$this->Template->contentbox = $content;
		return;

	}

}
