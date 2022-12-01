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
    'version' => '11.0.3',
    'constraints' => [
        'depends' => [
            'typo3' => '11.0.0-11.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
