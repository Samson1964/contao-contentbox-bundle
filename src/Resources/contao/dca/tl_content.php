<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt ein Inhaltselement bereit, das mehrere Bild-Text-Blöcke
 * in einer gemeinsamen Box zusammenfasst — lauffähig unter Contao 4.13 und Contao 5.
 *
 * @license LGPL-3.0-or-later
 */

use Contao\BackendUser;
use Contao\System;

/**
 * Palette des Inhaltselements
 *
 * Das Feld „guests" gibt es nur in Contao 4.13; in Contao 5 wurde es aus
 * tl_content entfernt. Es wird deshalb nur dann in die Palette geschrieben,
 * wenn der Kern es tatsächlich definiert hat. Die DCA-Datei des Bundles wird
 * nach der des Kerns geladen (siehe ContaoManager\Plugin), die Feldliste steht
 * an dieser Stelle also bereits fest.
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['contentbox'] = '{type_legend},type,headline;{contentbox_legend},contentbox;{protected_legend:hide},protected;{expert_legend:hide},'
	. (isset($GLOBALS['TL_DCA']['tl_content']['fields']['guests']) ? 'guests,' : '')
	. 'cssID;{invisible_legend:hide},invisible,start,stop';

/**
 * Felder
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
				// Liefert die im System hinterlegten Bildgrößen als Auswahlliste,
				// beschränkt auf die Größen, die der angemeldete Benutzer sehen darf.
				// Der Dienst heißt seit Contao 5 "contao.image.sizes"; unter Contao 4.13
				// ist "contao.image.image_sizes" nur noch ein Alias darauf, der alte Name
				// führt in Contao 5 dagegen zu einem Fehler. Die Klassennamen stehen
				// voll qualifiziert da, weil Contao 5 keine globalen Klassenaliasse
				// mehr anlegt und "\System" dort nicht mehr existiert.
				'options_callback'        => static function (): array
				{
					return System::getContainer()->get('contao.image.sizes')->getOptionsForUser(BackendUser::getInstance());
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
