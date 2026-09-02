# Contentbox

Ein Inhaltselement für Contao, das mehrere Bild-Text-Blöcke in einer gemeinsamen,
über CSS gestaltbaren Box zusammenfasst.

Die Blöcke werden im Backend als Zeilen eines MultiColumnWizard gepflegt. Jede Zeile
besteht aus einem Haken „Veröffentlicht", einem Bild samt Bildgröße, einer Überschrift
mit wählbarer Auszeichnungsebene und einem Text mit Rich-Text-Editor.

## Voraussetzungen

* Contao 4.13 oder Contao 5
* PHP 7.4 oder neuer
* [menatwork/contao-multicolumnwizard-bundle](https://packagist.org/packages/menatwork/contao-multicolumnwizard-bundle)
  (wird als Abhängigkeit mitinstalliert)

## Installation

Über den Contao Manager nach „Contentbox" suchen, oder auf der Konsole:

```bash
composer require schachbulle/contao-contentbox-bundle
```

## Bedienung

Im Artikel ein neues Inhaltselement anlegen und als Typ **Inhaltebox** (Gruppe „Text")
wählen. Im Abschnitt „Einstellungen" lassen sich beliebig viele Zeilen anlegen.

Nur Zeilen mit gesetztem Haken „Veröffentlicht" erscheinen im Frontend. Zeilen ohne
Bild, ohne Überschrift oder ohne Text sind erlaubt; die jeweiligen Teile entfallen dann.

## Markup

Das Element gibt je Zeile einen `div` aus:

```html
<div class="ce_contentbox block">
	<div>
		<h3>Überschrift</h3>
		<figure class="image_container">
			<img src="…" srcset="…" width="…" height="…">
		</figure>
		<div class="text">…</div>
	</div>
</div>
```

Die Gestaltung erfolgt vollständig über das Stylesheet des Themes. Wer ein anderes
Markup braucht, legt eine eigene Fassung des Templates `ce_contentbox.html5` im
Template-Verzeichnis der Installation ab; die Variable `$this->contentbox` enthält
den fertigen Inhalt.

## Prüfstand

`tests/pruefstand.php` bootet eine vorhandene Contao-Installation, prüft die Anmeldung
des Inhaltselements, lädt die DCA und rendert eine Beispielbox:

```bash
php tests/pruefstand.php /pfad/zur/contao-installation
```

Das Skript ist gegen Contao 4.13.58 und Contao 5.7.7 mit PHP 8.4.24 gelaufen.

## Lizenz

LGPL-3.0-or-later — siehe [LICENSE](LICENSE).

## Autor

Frank Hoppe, [schachbulle.de](https://www.schachbulle.de/)
