# Contentbox-Bundle Changelog

## Version 2.0.0 (2026-09-02)

* Add: Unterstützung für Contao 5. Das Bundle läuft jetzt unter Contao 4.13 und Contao 5
  aus derselben Fassung; geprüft gegen Contao 4.13.58 und 5.7.7 mit PHP 8.4.24.
* Change: Die Bildausgabe benutzt das Image Studio (`contao.image.studio`) statt
  `Controller::addImageToTemplate()`. Die alte Methode gibt es in Contao 5 nicht mehr,
  das Studio dagegen in beiden Fassungen mit gleicher Schnittstelle. Das Markup bleibt
  eine `figure.image_container` mit einem `img`-Element, ergänzt um `srcset`.
* Change: Alle Klassennamen stehen voll qualifiziert im Code (`Contao\ContentElement`,
  `Contao\System`, `Contao\BackendUser`). Contao 5 legt keine globalen Klassenaliasse
  mehr an, `extends \ContentElement` bräche dort mit einem Fatal Error ab.
* Change: Das Feld `guests` kommt nur noch dann in die Palette, wenn Contao es in
  `tl_content` definiert — in Contao 5 wurde es entfernt.
* Fix: Die gespeicherten Zeilen werden mit `StringUtil::deserialize()` gelesen statt mit
  `unserialize()`. Ein leeres oder unlesbares Feld führte bisher zu einem Fehler beim
  Durchlaufen der Zeilen; jetzt bleibt die Box in diesem Fall leer.
* Fix: Der interne Schlüssel `hasSingleAspectRatio` landete als Attribut im `img`-Element
  und ergab ungültiges HTML. Es werden nur noch echte Bildattribute geschrieben.
* Fix: Eine von Hand veränderte Auszeichnungsebene der Überschrift konnte ein beliebiges
  Element in die Seite schreiben. Erlaubt sind jetzt nur `h1` bis `h6`, sonst `h2`.
* Fix: Die Beschriftung des Feldes „Bildgröße" fehlte in der Sprachdatei.
* Change: `composer.json` verlangt jetzt `contao/core-bundle: ^4.13 || ^5.0`,
  PHP `^7.4 || ^8.0` und den MultiColumnWizard in `^3.6 || ^4.0` statt in beliebiger
  Fassung. Das überflüssige `doctrine/doctrine-cache-bundle` ist aus `require-dev` raus.
* Change: Alle PHP-Dateien mit `declare(strict_types=1);`, deutschen Kommentarblöcken
  und in UTF-8 ohne BOM.
* Add: README mit Beschreibung, Installation und Bedienung.

## Version 1.0.2 (2026-08-02)

* Change: Die Auswahlliste der Bildgrößen im Inhaltselement holt den Dienst jetzt unter
  seinem aktuellen Namen `contao.image.sizes`. Der bisher benutzte Name
  `contao.image.image_sizes` ist unter Contao 4.13 nur ein veralteter Alias auf denselben
  Dienst und in Contao 5 entfernt — dort bräche das Bearbeiten des Inhaltselements mit
  „You have requested a non-existent service“ ab.

## Version 1.0.1 (2026-07-30)

* Change: Beschreibung, Keywords und Homepage in der composer.json ergänzt, damit Packagist das Paket verständlich darstellt und über die Suche auffindbar macht

## Version 1.0.0 (2025-09-09)

* Add: Abhängigkeit PHP 8

## Version 0.0.4 (2021-11-24)

* Erste arbeitsfähige Version

## Version 0.0.3 (2021-11-24)

* Falsches Bundle in composer.json geladen

## Version 0.0.2 (2021-11-24)

* Ausbau des Bundles

## Version 0.0.1 (2021-11-24)

* Alpha-Version
