<?php

$ll = 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_shopwareconnector_domain_model_media',
        'label' => 'file_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'origUid' => 'l10n_parent',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => 'EXT:shopware_connector/Resources/Public/Icons/tx_shopwareconnector_domain_model_media.svg',
        'searchFields' => 'file_name, title, alternative, mime_type, url',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --palette--;' . $ll . 'palette.general;general,
                --palette--;' . $ll . 'palette.meta;meta,
                --div--;' . $ll . 'tab.shopware,
                    --palette--;' . $ll . 'palette.shopware;shopware,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    sys_language_uid, l10n_parent, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden, starttime, endtime'
        ],
    ],
    'palettes' => [
        'general' => [
            'label' => $ll . 'palette.general',
            'showitem' => 'title, --linebreak--,alternative, --linebreak--, file_name, --linebreak--, url',
        ],
        'meta' => [
            'label' => $ll . 'palette.meta',
            'showitem' => 'mime_type, file_extension, file_size, sorting',
        ],
        'shopware' => [
            'label' => $ll . 'palette.shopware',
            'showitem' => 'sw_id, --linebreak--, sw_language_id',
        ],
    ],
    'columns' => [
        'sw_id' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sw_id',
            'config' => [
				'readOnly' => true,
                'required' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'sw_language_id' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sw_language_id',
            'config' => [
				'readOnly' => true,
                'required' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'checksum' => [
            'config' => [
				'readOnly' => true,
                'type' => 'passthrough',
            ],
        ],
        'mime_type' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.mime_type',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'file_name' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.file_name',
            'config' => [
				'readOnly' => true,
                'required' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'file_extension' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.file_extension',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'file_size' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.file_size',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'number',
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.title',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'alternative' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.alternative',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'url' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.url',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'renderType' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'sorting' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sorting',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'number',
            ],
        ],
    ],
];
