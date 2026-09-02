<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt ein Inhaltselement bereit, das mehrere Bild-Text-Blöcke
 * in einer gemeinsamen Box zusammenfasst — lauffähig unter Contao 4.13 und Contao 5.
 *
 * @license LGPL-3.0-or-later
 */

use Schachbulle\ContaoContentboxBundle\ContentElements\Contentbox;

/**
 * Inhaltselemente
 *
 * Die klassische Anmeldung über $GLOBALS['TL_CTE'] mit einem Klassennamen wird
 * von beiden Contao-Fassungen ausgewertet (ContentElement::findClass); ein
 * Fragment-Controller wäre nicht nötig. Der Klassenname kommt als Konstante
 * ::class statt als Zeichenkette, damit ein Tippfehler sofort auffällt.
 */
$GLOBALS['TL_CTE']['texts']['contentbox'] = Contentbox::class;
