<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt ein Inhaltselement bereit, das mehrere Bild-Text-Blöcke
 * in einer gemeinsamen Box zusammenfasst — lauffähig unter Contao 4.13 und Contao 5.
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoContentboxBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Schachbulle\ContaoContentboxBundle\ContaoContentboxBundle;

/**
 * Meldet das Bundle beim Contao Manager an.
 *
 * Ohne diese Klasse taucht das Bundle nicht im Kernel auf, weil Contao die
 * Bundle-Liste aus den Plugins aller installierten Pakete zusammensetzt.
 */
class Plugin implements BundlePluginInterface
{
	/**
	 * Meldet das Bundle beim Kernel an.
	 *
	 * Das Bundle wird nach dem Contao-Core geladen, damit dessen DCA-Datei
	 * tl_content bereits vorliegt, wenn die eigene Palette und das eigene Feld
	 * ergänzt werden. Die Palette prüft, ob das Feld „guests" überhaupt
	 * existiert — unter Contao 5 gibt es das in tl_content nicht mehr.
	 *
	 * @param ParserInterface $parser Wird nicht ausgewertet, weil das Bundle
	 *                                keine Konfigurationsdateien parsen lässt
	 *
	 * @return array<int, BundleConfig> Die Konfiguration dieses einen Bundles
	 */
	public function getBundles(ParserInterface $parser): array
	{
		return array(
			BundleConfig::create(ContaoContentboxBundle::class)
				->setLoadAfter(array(ContaoCoreBundle::class)),
		);
	}
}
