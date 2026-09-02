<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt ein Inhaltselement bereit, das mehrere Bild-Text-Blöcke
 * in einer gemeinsamen Box zusammenfasst — lauffähig unter Contao 4.13 und Contao 5.
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoContentboxBundle\ContentElements;

use Contao\ContentElement;
use Contao\StringUtil;
use Contao\System;

/**
 * Inhaltselement „Inhaltebox".
 *
 * Das Element fasst beliebig viele Zeilen aus dem MultiColumnWizard zu einem
 * Block zusammen; jede Zeile besteht aus Überschrift, Bild und Text. Das
 * fertige Markup wird als Zeichenkette an das Template übergeben, weil die
 * Zeilenzahl frei ist und das Template dadurch ohne Schleife auskommt.
 *
 * Der Klassenname der Elternklasse steht voll qualifiziert da: Contao 5 legt
 * keine globalen Klassenaliasse mehr an, ein „extends \ContentElement" bricht
 * dort mit einem Fatal Error ab.
 */
class Contentbox extends ContentElement
{
	/**
	 * Name des Frontend-Templates ohne Dateiendung.
	 *
	 * Ein Name ohne Schrägstrich gilt in Contao 5 als Legacy-Template, die
	 * vorhandene ce_contentbox.html5 bedient damit beide Fassungen.
	 *
	 * @var string
	 */
	protected $strTemplate = 'ce_contentbox';

	/**
	 * Baut das Markup der Box aus den gespeicherten Zeilen zusammen.
	 *
	 * Die Zeilen liegen als serialisiertes Feld in der Spalte contentbox.
	 * Nicht veröffentlichte Zeilen werden übersprungen, ebenso alles, was
	 * keine Zeile ist — ein leeres oder beschädigtes Feld führt so zu einer
	 * leeren Box statt zu einem Fehler. Das Ergebnis landet in der
	 * Template-Variablen contentbox; einen Rückgabewert gibt es nicht.
	 */
	protected function compile(): void
	{
		$content = '';

		foreach (StringUtil::deserialize($this->contentbox, true) as $item)
		{
			// Leere und nicht freigeschaltete Zeilen bleiben außen vor
			if (!\is_array($item) || empty($item['published']))
			{
				continue;
			}

			$content .= '<div>';
			$content .= $this->parseHeadline($item);
			$content .= $this->parseImage($item);

			if (!empty($item['text']))
			{
				$content .= '<div class="text">' . $item['text'] . '</div>';
			}

			$content .= '</div>';
		}

		$this->Template->contentbox = $content;
	}

	/**
	 * Erzeugt die Überschrift einer Zeile.
	 *
	 * Der inputUnit-Widget speichert Text und Auszeichnungsebene gemeinsam als
	 * Feld mit den Schlüsseln „value" und „unit". Die Ebene wird gegen die im
	 * DCA erlaubten Werte geprüft, damit ein von Hand veränderter Datensatz
	 * kein beliebiges Element in die Seite schreiben kann; fehlt sie oder ist
	 * sie unbekannt, wird h2 verwendet.
	 *
	 * @param array<string, mixed> $item Eine Zeile des MultiColumnWizard
	 *
	 * @return string Das Überschriften-Element, oder eine leere Zeichenkette,
	 *                wenn die Zeile keinen Überschriftentext hat
	 */
	private function parseHeadline(array $item): string
	{
		$headline = $item['headline'] ?? array();

		if (!\is_array($headline) || empty($headline['value']))
		{
			return '';
		}

		$unit = $headline['unit'] ?? '';

		if (!\in_array($unit, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'), true))
		{
			$unit = 'h2';
		}

		return '<' . $unit . '>' . $headline['value'] . '</' . $unit . '>';
	}

	/**
	 * Erzeugt das Bild einer Zeile.
	 *
	 * Statt des früheren Controller::addImageToTemplate() wird das Image Studio
	 * benutzt: Die Methode gibt es in Contao 5 nicht mehr, das Studio dagegen
	 * in beiden Fassungen mit gleicher Schnittstelle. Das Markup bleibt das
	 * bisherige — ein einzelnes img-Element in einer figure —, ergänzt um die
	 * von getImg() gelieferten Angaben zu srcset und Abmessungen.
	 *
	 * Das Feld enthält die UUID aus der Dateiverwaltung. Zeigt sie ins Leere,
	 * weil die Datei gelöscht wurde, liefert buildIfResourceExists() null und
	 * die Zeile erscheint ohne Bild statt mit einem Fehler.
	 *
	 * @param array<string, mixed> $item Eine Zeile des MultiColumnWizard
	 *
	 * @return string Die figure samt Bild, oder eine leere Zeichenkette, wenn
	 *                die Zeile kein Bild hat oder die Datei fehlt
	 */
	private function parseImage(array $item): string
	{
		if (empty($item['image']))
		{
			return '';
		}

		$figure = System::getContainer()
			->get('contao.image.studio')
			->createFigureBuilder()
			->fromUuid((string) $item['image'])
			->setSize(StringUtil::deserialize($item['size'] ?? null))
			->buildIfResourceExists()
		;

		if (null === $figure)
		{
			return '';
		}

		$img = $figure->getImage()->getImg();
		$attributes = '';

		// Aus getImg() werden nur die echten Bildattribute übernommen. Das Feld
		// enthält zusätzlich den internen Schlüssel "hasSingleAspectRatio", den
		// Contao im eigenen Template gesondert auswertet — als Attribut
		// geschrieben ergäbe er ungültiges HTML.
		foreach (array('src', 'srcset', 'sizes', 'width', 'height', 'loading') as $name)
		{
			$value = $img[$name] ?? null;

			if (!\is_scalar($value) || '' === $value)
			{
				continue;
			}

			$attributes .= ' ' . $name . '="' . StringUtil::specialchars((string) $value) . '"';
		}

		return '<figure class="image_container"><img' . $attributes . '></figure>';
	}
}
