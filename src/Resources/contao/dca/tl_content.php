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

$GLOBALS['TL_DCA']['tl_content']['palettes']['contentbox'] = '{type_legend},type,headline;{contentbox_legend},contentbox;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['contentbox'] = array
(
	'label'                             => &$GLOBALS['TL_LANG']['tl_content']['contentbox'],
	'exclude'                           => true,
	'inputType'                         => 'multiColumnWizard',
	'eval'                              => array
	(
		'buttonPos'                     => 'top',
		'buttons'                       => array
		(
			//'copy' 			=> true, 
			//'delete' 		=> true,
			//'up' 			=> true,
			//'down'			=> true
		),
		'columnFields'                  => array
		(
			'published'                 => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_content']['contentbox_published'],
				'exclude'               => true,
				'inputType'             => 'checkbox',
				'eval'                  => array
				(	
					'style'             => 'width: 20px',
					'valign'            => 'top'
				)
			),
			'question'                  => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_content']['contentbox_question'],
				'exclude'               => true,
				'inputType'             => 'textarea',
				'eval'                  => array
				(
					'rte'               =>'tinyMCE',
					'style'             => 'width:400px; height:150px;',
					'allowHtml'         => true,
					//'columnPos'         => '1'
				)
			),
			'answer'                    => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_content']['contentbox_answer'],
				'exclude'               => true,
				'inputType'             => 'textarea',
				'eval'                  => array
				(
					'rte'               =>'tinyMCE',
					'style'             => 'width:400px; height:150px;',
					'allowHtml'         => true,
					//'columnPos'         => '1'
				)
			),
		)
	),
	'sql'                               => "blob NULL"
);
