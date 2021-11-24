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

		$this->Template->contentbox = unserialize($this->contentbox);
		return;

	}

}
