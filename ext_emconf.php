<?php

$EM_CONF['recaptcha'] = [
    'title' => 'reCAPTCHA',
    'description' => 'Integrates google reCAPTCHA and invisible reCAPTCHA in EXT:form, EXT:sf_register
        and via TypoScript renderer Easy on Humans, Hard on Bots',
    'category' => 'fe',
    'author' => 'Sebastian Fischer',
    'author_email' => 'typo3@evoweb.de',
    'author_company' => 'evoWeb',
    'state' => 'stable',
    'version' => '8.5.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
        ],
    ],
];
