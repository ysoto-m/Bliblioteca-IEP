<?php
return [
    'host'       => getenv('MAIL_HOST'),
    'port'       => getenv('MAIL_PORT'),
    'username'   => getenv('MAIL_USER'),
    'password'   => getenv('MAIL_PASS'),
    'from_email' => getenv('MAIL_FROM'),
    'from_name'  => getenv('MAIL_NAME'),
    'smtp_secure'=> getenv('MAIL_SECURE') ?: 'tls',
];
