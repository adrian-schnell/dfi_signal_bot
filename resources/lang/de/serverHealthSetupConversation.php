<?php

return [
    'intro'                         => "*DeFiChain Masternode Server Health* ist ein Dienst, der dir Informationen über deinen Server & deine Masternode zur Verfügung stellt. Und es wäre nicht *DFI Signal*, wenn du nicht benachrichtigt wirst, wenn Probleme erkannt werden.\r\n\r\nDFI Signal benachrichtigt dich, wenn Speicherprobleme erkannt werden, die Systemauslastung zu hoch ist, ein 'chain split' oder andere Probleme auftreten.\r\n\r\n`Um diesen Dienst nutzen zu können, musst du eine Zusatzanwendung auf deinem Server installieren (Anweisungen dazu findest du gleich im weiteren Einrichtungsprozess).`",
    'setup_method_selection'        => 'Der erste Schritt zur Einrichtung von masternode health ist ein API-Schlüssel. Ich kann jetzt einen neuen für dich anlegen - andernfalls verwende einen bestehenden.',
    'setup_method_selection_repeat' => 'Bitte nutze zur Auswahl einen der Buttons!',
    'setup_instructions'            => "🚧 Installiere jetzt die Server-App.\r\nFolge dazu der Installationsanleitung auf [GitHub](https://github.com/defichain-api/masternode-health-server/).\r\n\r\nSolltest du Fragen oder Probleme haben, öffne bitte ein 'issue' auf GitHub!",
    'buttons'                       => [
        'new_key'               => 'neuen API Key erstellen',
        'existing_key'          => 'bestehenden API Key nutzen',
        'existing_key_question' => 'Bitte gib jetzt deinen Masternode Health API Key ein:',
    ],

    'success'   => "✅ Dein API Key und der Informationsdienst sind nun eingerichtet. Rufe die aktuellen Informationen mit `/masternode_health` ab, sobald der Cronjob auf deinem Server eingerichtet ist.",
    'api_key'   => "👾 Bitte notiere dir deinen API Key: `:api_key`",
    'api_error' => '🆘 API Fehler aufgetreten. Versuche es bitte in wenigen Minuten nochmals...',
];
