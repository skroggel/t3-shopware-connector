<?php
$ll = 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_db.xlf:';
return [
    'ctrl' => [
        'title' => $ll . 'tx_shopwareconnector_domain_model_media',
        'label' => 'title',
        'label_alt' => 'file_name',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sw_language_id',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'versioningWS' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title, file_name, mime_type, url',
        'iconfile' => 'EXT:shopware_connector/Resources/Public/Icons/tx_shopwareconnector_domain_model_media.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                title, alternative, mime_type, file_name, file_extension, file_size, url, sorting,
                --div--;' . $ll . 'tabs.checksum, sw_id, sw_language_id, checksum,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sw_language_id, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, crdate, tstamp
            ',
        ],
    ],
    'columns' => [
        'title' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'alternative' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.alternative',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'mime_type' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.mime_type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'file_name' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.file_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'file_extension' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.file_extension',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim,required',
            ],
        ],
        'file_size' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.file_size',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'int,required',
            ],
        ],
        'url' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.url',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required',
            ],
        ],
        'sorting' => [
            'exclude' => 1,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sorting',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'sw_id' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sw_id',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'sw_language_id' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sw_language_id',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'checksum' => [
            'exclude' => 0,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.checksum',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
    ],
];
