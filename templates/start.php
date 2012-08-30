<? use Studip\Button; ?>

<p>
    <?= _('Sie können mit dem Anmelden-Button direkt in den Plugin-Marktplatz springen.') ?>
    <?= _('Bei diesem Vorgang werden Name, Vorname, Benutzername und E-Mail Adresse in verschlüsselter Form übertragen.') ?>
</p>

<div style="padding-top:10px;">
    <form name="mplogin" method="post" action="<?= $uri ?>" target="_blank">
        <input type="hidden" name="cryptinformation" value="<?= $cryptinformation ?>">
        <input type="hidden" name="cryptloginkey" value="<?= $cryptloginkey ?>">
        <?= Button::createAccept(_('Im Plugin-Marktplatz anmelden')) ?>
    </form>
</div>

<?
$infobox_content = array(array(
    'kategorie' => _('Hinweise:'),
    'eintrag'   => array(array(
        'icon' => Assets::image_path('icons/16/black/info-circle.png'),
        'text' => _('Durch Betätigen des Anmelde-Buttons gelangen Sie direkt als angemeldeter Nutzer in den Plugin-Marktplatz.')
    ),
    array(
        'icon' => Assets::image_path('icons/16/black/info-circle.png'),
        'text' => sprintf(_('Alternativ können Sie sich direkt auf dem '
                           .'<a href="%s" target="_blank">Plugin-Marktplatz</a> '
                           .'mit Ihren Nutzerdaten des Stud.IP Entwicklerservers '
                           .'anmelden.'),
                           MARKETPLACE_URI)
    ))
));
$infobox = array('picture' => 'infobox/modules.jpg', 'content' => $infobox_content);
?>
