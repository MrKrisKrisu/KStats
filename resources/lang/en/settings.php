<?php

return [
    'password'           => [
        'current_wrong'        => 'The current password is not correct.',
        'changed_successfully' => 'Your password was changes successfully.',
        'change'               => 'Change password',
        'current'              => 'Current password',
        'new'                  => 'New password',
        'new_repeat'           => 'Repeat new password'
    ],
    'settings'           => 'Settings',
    'telegram'           => [
        'connection_removed' => 'The Connection to Telegram was removed successfully.',
        'connect_code'       => 'Telegram-ConnectCode',
        'valid_until'        => 'Code valid until',
        'description'        => 'To use KStats with Telegram you need to add the <a target="tg" href="https://t.me/kstat_bot">KStats Bot</a> and run the command "/connect [code]".',
    ],
    'verify_mail'        => [
        'intro'                 => 'somebody has added this E-Mail Address to an KStats account.',
        'confirmations'         => 'Please confirm, that this is your E-Mail address and click on the following link:',
        'disclaimer'            => 'If you\'ve not done this please dont click the link and send a message to the KStats support.',
        'link_invalid'          => 'The verification link is not valid.',
        'verified_successfully' => 'You\'ve successfully confirmed your E-Mail address.',
        'alert_save'            => 'The address was saved successfully. Please check your inbox for the verification mail.',
        'alert_save_error'      => 'An error occurred while sending verification mail.'
    ],
    'connected'          => 'Your Account is already connected to :service',
    'not_connected'      => 'You need to connect to :service first to see stats.',
    'connect'            => 'Connect',
    'connect_new'        => 'Reconnect',
    'lang'               => [
        'de' => 'German',
        'en' => 'English'
    ],
    'select'             => 'Please choose',
    'set_language'       => 'Set language',
    'alert_set_language' => 'The language settings are successfully saved.',
    'third-party'        => [
        'card-heading'  => 'Connected Third Party',
        'connected'     => 'Successfully connected.',
        'not-connected' => '¯\_(ツ)_/¯ Not connected',
        'connect-to'    => 'Connect to :thirdparty'
    ],
];
