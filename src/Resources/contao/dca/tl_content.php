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
					'valign'            => 'top',
					'columnPos'           => '1'
				)
			),
			'image' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_content']['contentbox_image'],
				'exclude'                 => true,
				'inputType'               => 'fileTree',
				'eval'                    => array
				(
					'filesOnly'           => true,
					'fieldType'           => 'radio',
					'valign'              => 'top',
					'style'               => 'width:400px',
					'columnPos'           => '1'
				),
			),
			'size' => array
			(
				'exclude'                 => true,
				'inputType'               => 'imageSize',
				'label'                   => &$GLOBALS['TL_LANG']['tl_content']['contentbox_size'],
				'eval'                    => array
				(
					'rgxp'                => 'natural',
					'includeBlankOption'  => true,
					'nospace'             => true,
					'valign'              => 'top',
					'helpwizard'          => true,
					'columnPos'           => '1'
				),
				'options_callback'        => static function ()
				{
					return \System::getContainer()->get('contao.image.image_sizes')->getOptionsForUser(\BackendUser::getInstance());
				},
			),
			'headline' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_content']['contentbox_headline'],
				'exclude'                 => true,
				'search'                  => true,
				'inputType'               => 'inputUnit',
				'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
				'eval'                    => array
				(
					'maxlength'           => 200,
					'valign'              => 'top',
					'style'               => 'width:300px',
					'columnPos'           => '2'
				),
			),
			'text'                        => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_content']['contentbox_text'],
				'exclude'                 => true,
				'inputType'               => 'textarea',
				'eval'                    => array
				(                         
					'rte'                 =>'tinyMCE',
					'style'               => 'width:600px; height:250px;',
					'valign'              => 'top',
					'allowHtml'           => true,
					'columnPos'           => '2'
				)
			),
		)
	),
	'sql'                               => "blob NULL"
);
