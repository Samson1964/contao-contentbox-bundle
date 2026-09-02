<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt ein Inhaltselement bereit, das mehrere Bild-Text-Blöcke
 * in einer gemeinsamen Box zusammenfasst — lauffähig unter Contao 4.13 und Contao 5.
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoContentboxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Lädt die Dienstdefinitionen des Bundles in den Container.
 *
 * Die Basisklasse liegt in Symfony 5.4 (Contao 4.13) wie in Symfony 7
 * (Contao 5) unter demselben Namensraum, die Datei läuft also unverändert
 * unter beiden Fassungen.
 */
class ContaoContentboxExtension extends Extension
{
	/**
	 * Liest die services.yml des Bundles ein.
	 *
	 * Das Inhaltselement selbst wird nicht als Dienst registriert, sondern
	 * klassisch über $GLOBALS['TL_CTE'] — dieser Weg funktioniert in beiden
	 * Contao-Fassungen. Die services.yml hält lediglich die Vorgaben für
	 * künftige Dienste bereit.
	 *
	 * @param array<int|string, mixed> $mergedConfig Die zusammengeführte
	 *                                               Bundle-Konfiguration; das
	 *                                               Bundle wertet sie nicht aus
	 * @param ContainerBuilder         $container    Der Container, in den die
	 *                                               Definitionen geschrieben werden
	 */
	public function load(array $mergedConfig, ContainerBuilder $container): void
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);

		$loader->load('services.yml');
	}
}
