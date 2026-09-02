<?php
/**
 * Prüfstand für schachbulle/contao-contentbox-bundle.
 *
 * Bootet den Contao-Kernel der Testinstallation, prüft die Anmeldung des
 * Inhaltselements, lädt die DCA von tl_content und rendert eine Box mit
 * Überschrift, Bild und Text.
 *
 * Aufruf: php pruefstand.php <pfad-zur-installation>
 */

$root = $argv[1] ?? null;

if (!$root || !is_dir($root)) {
    fwrite(STDERR, "Pfad zur Installation fehlt\n");
    exit(1);
}

require $root . '/vendor/autoload.php';

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\ManagerBundle\HttpKernel\ContaoKernel;
use Symfony\Component\HttpFoundation\Request;

$fehler = 0;

function melde(string $name, bool $ok, string $info = ''): void
{
    global $fehler;
    if (!$ok) {
        $fehler++;
    }
    printf("%-58s %s%s\n", $name, $ok ? 'OK' : 'FEHLER', $info !== '' ? '  (' . $info . ')' : '');
}

putenv('APP_ENV=prod');
$_SERVER['APP_ENV'] = 'prod';

$kernel = ContaoKernel::fromInput($root, new Symfony\Component\Console\Input\ArrayInput([]));
$kernel->boot();
$container = $kernel->getContainer();

echo "Contao " . ContaoCoreBundle::getVersion() . ", PHP " . PHP_VERSION . "\n";
echo str_repeat('-', 78) . "\n";

// Bundle im Kernel?
melde('Bundle im Kernel angemeldet', isset($kernel->getBundles()['ContaoContentboxBundle']));

// Framework hochfahren, damit Contao-Klassen und Konfiguration bereitstehen
$request = Request::create('http://localhost/');
$request->attributes->set('_scope', 'frontend');
$container->get('request_stack')->push($request);

/** @var ContaoFramework $framework */
$framework = $container->get('contao.framework');
$framework->initialize();

// Anmeldung des Inhaltselements
$klasse = $GLOBALS['TL_CTE']['texts']['contentbox'] ?? null;
melde('TL_CTE-Eintrag vorhanden', $klasse === Schachbulle\ContaoContentboxBundle\ContentElements\Contentbox::class, (string) $klasse);
melde('Klasse ladbar', class_exists((string) $klasse));

// compile() muss zur abstrakten Methode der Elternklasse passen
$r = new ReflectionMethod($klasse, 'compile');
melde('compile() überschreibt ContentElement::compile()', $r->getDeclaringClass()->getName() === $klasse);

// DCA laden
Contao\Controller::loadDataContainer('tl_content');
Contao\System::loadLanguageFile('tl_content', 'de');

$palette = $GLOBALS['TL_DCA']['tl_content']['palettes']['contentbox'] ?? '';
melde('Palette gesetzt', $palette !== '');

$hatGuestsFeld = isset($GLOBALS['TL_DCA']['tl_content']['fields']['guests']);
$paletteHatGuests = str_contains($palette, 'guests');
melde('guests in Palette nur wenn Feld existiert', $hatGuestsFeld === $paletteHatGuests, 'Feld: ' . var_export($hatGuestsFeld, true) . ', Palette: ' . var_export($paletteHatGuests, true));

// Alle Palettenfelder müssen im DCA definiert sein
$fehlend = [];
foreach (preg_split('/[;,]/', preg_replace('/\{[^}]+\}/', '', $palette)) as $feld) {
    $feld = trim($feld);
    if ($feld !== '' && !isset($GLOBALS['TL_DCA']['tl_content']['fields'][$feld])) {
        $fehlend[] = $feld;
    }
}
melde('Alle Palettenfelder im DCA definiert', $fehlend === [], implode(', ', $fehlend));

melde('Feld contentbox definiert', isset($GLOBALS['TL_DCA']['tl_content']['fields']['contentbox']));

// options_callback der Bildgröße
try {
    $cb = $GLOBALS['TL_DCA']['tl_content']['fields']['contentbox']['eval']['columnFields']['size']['options_callback'];
    $optionen = $cb();
    melde('options_callback Bildgrößen läuft', is_array($optionen), count($optionen) . ' Gruppen');
} catch (Throwable $e) {
    melde('options_callback Bildgrößen läuft', false, get_class($e) . ': ' . $e->getMessage());
}

// Sprachschlüssel
$fehlendeLabels = [];
foreach (['contentbox', 'contentbox_legend', 'contentbox_published', 'contentbox_image', 'contentbox_size', 'contentbox_headline', 'contentbox_text'] as $key) {
    if (empty($GLOBALS['TL_LANG']['tl_content'][$key])) {
        $fehlendeLabels[] = $key;
    }
}
melde('Alle Beschriftungen vorhanden', $fehlendeLabels === [], implode(', ', $fehlendeLabels));

// Eine echte Datei aus der Dateiverwaltung suchen
$datei = Contao\FilesModel::findOneBy(['extension IN (?,?,?)'], ['jpg', 'png', 'gif']);
melde('Testbild in der Dateiverwaltung gefunden', $datei !== null, $datei ? $datei->path : 'keine');

// Inhaltselement rendern
$zeilen = [
    [
        'published' => '1',
        'headline'  => ['value' => 'Erste Zeile', 'unit' => 'h3'],
        'image'     => $datei ? $datei->uuid : '',
        'size'      => serialize(['200', '150', 'crop']),
        'text'      => '<p>Ein Text.</p>',
    ],
    [
        'published' => '',
        'headline'  => ['value' => 'Unsichtbar', 'unit' => 'h3'],
        'image'     => '',
        'size'      => '',
        'text'      => 'Darf nicht erscheinen',
    ],
    [
        'published' => '1',
        'headline'  => ['value' => 'Zeile ohne Bild', 'unit' => 'script'],
        'image'     => '',
        'size'      => '',
        'text'      => '',
    ],
];

$model = new Contao\ContentModel();
$model->setRow([
    'id'         => 1,
    'pid'        => 1,
    'ptable'     => 'tl_article',
    'type'       => 'contentbox',
    'contentbox' => serialize($zeilen),
    'cssID'      => '',
]);

try {
    $element = new $klasse($model, 'main');
    $html = $element->generate();
    melde('Element rendert ohne Fehler', true);
    melde('Sichtbare Zeile im Markup', str_contains($html, 'Erste Zeile'));
    melde('Unsichtbare Zeile unterdrückt', !str_contains($html, 'Unsichtbar'));
    melde('Bild als figure gerendert', !$datei || str_contains($html, 'image_container'));
    melde('Ungültige Überschriftenebene abgefangen', str_contains($html, '<h2>Zeile ohne Bild</h2>'));
    echo "\n--- Markup ---\n" . trim($html) . "\n--- Ende ---\n";
} catch (Throwable $e) {
    melde('Element rendert ohne Fehler', false, get_class($e) . ': ' . $e->getMessage());
    echo $e->getTraceAsString() . "\n";
}

// Leeres und beschädigtes Feld
foreach (['' => 'leeres Feld', 'kaputt' => 'unlesbares Feld'] as $wert => $name) {
    $m = new Contao\ContentModel();
    $m->setRow(['id' => 2, 'pid' => 1, 'ptable' => 'tl_article', 'type' => 'contentbox', 'contentbox' => $wert, 'cssID' => '']);
    try {
        (new $klasse($m, 'main'))->generate();
        melde('Rendert ' . $name . ' ohne Fehler', true);
    } catch (Throwable $e) {
        melde('Rendert ' . $name . ' ohne Fehler', false, get_class($e) . ': ' . $e->getMessage());
    }
}

echo str_repeat('-', 78) . "\n";
echo $fehler === 0 ? "Alle Prüfungen bestanden.\n" : $fehler . " Prüfung(en) fehlgeschlagen.\n";

exit($fehler === 0 ? 0 : 1);
