<?php

$EM_CONF['recaptcha'] = [
    'title' => 'reCAPTCHA',
    'description' => 'Integrated google reCAPTCHA and invisible reCAPTCHA.
Easy on Humans, Hard on Bots',
    'version' => '8.2.6',
    'author' => 'Sebastian Fischer',
    'author_email' => 'typo3@evoweb.de',
    'author_company' => 'evoWeb',
    'category' => 'fe',
    'state' => 'stable',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.2.99',
        ],
    ],
];
