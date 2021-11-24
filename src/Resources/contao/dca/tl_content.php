<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
 *
 * PHP version 5
 * @copyright  Frank Hoppe
 * @author     Frank Hoppe
 * @package    references
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['twitch'] = '{type_legend},type,headline;{source_legend},twitch;{player_legend},playerSize,twitchOptions,playerStart,playerStop,playerCaption,playerAspect;{splash_legend},splashImage;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['twitch'] = array
(
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array
	(
		'mandatory'           => false,
		'maxlength'           => 40,
		'decodeEntities'      => true,
		'tl_class'            => 'w50'
	),
	'sql'                     => "varchar(40) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['twitchOptions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['twitchOptions'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => $GLOBALS['TL_LANG']['tl_content']['twitchOptionsItems'],
	'eval'                    => array('multiple'=>true, 'tl_class'=>'clr'),
	'sql'                     => "text NULL"
);

class tl_content_twich extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import(BackendUser::class, 'User');
	}


	/**
	 * Extract the YouTube ID from an URL
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return mixed
	 */
	public function extractTwitchId($varValue, DataContainer $dc)
	{
		//if ($dc->activeRecord->twitch != $varValue)
		//{
		//	$matches = array();
        //
		//	if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $varValue, $matches))
		//	{
		//		$varValue = $matches[1];
		//	}
		//}

		return $varValue;
	}
}

