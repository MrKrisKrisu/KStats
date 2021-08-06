<?php

return [
    'password'           => [
        'current_wrong'        => 'Das aktuelle Passwort ist nicht korrekt.',
        'changed_successfully' => 'Das Passwort wurde erfolgreich geändert.',
        'change'               => 'Passwort ändern',
        'current'              => 'Aktuelles Passwort',
        'new'                  => 'Neues Passwort',
        'new_repeat'           => 'Neues Passwort wiederholen'
    ],
    'settings'           => 'Einstellungen',
    'telegram'           => [
        'connection_removed' => 'Die Verknüpfung mit Telegram wurde entfernt.',
        'connect_code'       => 'Telegram-ConnectCode',
        'valid_until'        => 'Code gültig bis',
        'description'        => 'Um Telegram mit KStats nutzen zu können musst du den <a target="tg" href="https://t.me/kstat_bot">KStats Bot</a> starten und den den Befehl "/connect [code]" ausführen.',
    ],
    'verify_mail'        => [
        'intro'                 => 'jemand hat soeben deine E-Mail Adresse bei KStats deinem Account zugewiesen.',
        'confirmations'         => 'Bitte bestätige, dass diese Adresse zu dir gehört, indem du auf folgenden Link klickst:',
        'disclaimer'            => 'Solltest du das nicht gewesen sein, klicke bitte nicht auf den Link und wende dich an den KStats Support.',
        'link_invalid'          => 'Der Verifizierungslink ist nicht gültig.',
        'verified_successfully' => 'Die E-Mail Adresse wurde erfolgreich bestätigt.',
        'alert_save'            => 'Die E-Mail Adresse wurde gespeichert. Du solltest gleich eine E-Mail mit einem Bestätigungslink erhalten.',
        'alert_save_error'      => 'Es ist ein Fehler beim Senden der Bestätigungsmail aufgetreten.'
    ],
    'connected'          => 'Der Account ist mit einem :service Konto verbunden.',
    'not_connected'      => 'Um Statistiken zu erhalten musst du deinen Account zuerst mit :service verbinden.',
    'connect'            => 'Account verknüpfen',
    'connect_new'        => 'Neuen Account verknüpfen',
    'lang'               => [
        'de' => 'Deutsch',
        'en' => 'Englisch'
    ],
    'select'             => 'Bitte wählen',
    'set_language'       => 'Sprache festlegen',
    'alert_set_language' => 'Die Spracheinstellungen wurden gespeichert.',
    'third-party'        => [
        'card-heading'  => 'Verbundene Drittanbieter',
        'connected'     => 'Statistiken werden gesammelt.',
        'not-connected' => '¯\_(ツ)_/¯ Nicht verbunden',
        'connect-to'    => ':thirdparty verbinden',
        'manage'        => 'Verbindung verwalten'
    ],
    'grocy'              => [
        'connected' => 'Kassenzettel werden übertragen.',
    ]
];
