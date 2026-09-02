<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt ein Inhaltselement bereit, das mehrere Bild-Text-Blöcke
 * in einer gemeinsamen Box zusammenfasst — lauffähig unter Contao 4.13 und Contao 5.
 *
 * @license LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoContentboxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle-Klasse der Inhaltebox.
 *
 * Die Klasse bleibt leer, weil das Bundle weder eigene Compiler-Pässe noch
 * abweichende Verzeichnisse braucht. Symfony leitet Name und Pfad aus dem
 * Klassennamen und dem Dateiort ab; die Klasse muss aber vorhanden sein,
 * damit das Contao-Manager-Plugin sie beim Kernel anmelden kann.
 */
class ContaoContentboxBundle extends Bundle
{
}
