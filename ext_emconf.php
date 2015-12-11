<?php
/** @var string $_EXTKEY */

$EM_CONF[$_EXTKEY] = [
    'title' => 'reCAPTCHA',
    'description' => 'Easy on Humans, Hard on Bots',
    'category' => 'fe',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearcacheonload' => 0,
    'author' => 'Sebastian Fischer',
    'author_email' => 'typo3@evoweb.de',
    'author_company' => 'evoweb',
    'version' => '7.0.0',
    '_md5_values_when_last_written' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-7.6.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
