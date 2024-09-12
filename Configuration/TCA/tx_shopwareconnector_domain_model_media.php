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
        'general' => ['showitem' => 'title, --linebreak--,alternative, --linebreak--, file_name, --linebreak--, url', 'label' => $ll . 'palette.general'],
        'meta' => ['showitem' => 'mime_type, file_extension, file_size, sorting', 'label' => $ll . 'palette.meta'],
        'shopware' => ['showitem' => 'sw_id, --linebreak--, sw_language_id', 'label' => $ll . 'palette.shopware'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
				'readOnly' => true,
                'type' => 'check',
                'renderType' => 'checkboxToggle'
            ],
        ],
        'starttime' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'sys_language_uid' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
				'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l10n_parent',
            'config' => [
				'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_shopwareconnector_domain_model_media',
                'foreign_table_where' => 'AND tx_shopwareconnector_domain_model_media.pid=###CURRENT_PID### AND tx_shopwareconnector_domain_model_media.sys_language_uid IN (-1,0)',
                'items' => [
                    ['', 0],
                ],
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
				'readOnly' => true,
                'type' => 'passthrough',
            ],
        ],
        'sw_id' => [
            'exclude' => false,
            'readOnly' => true,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sw_id',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'sw_language_id' => [
            'exclude' => false,
            'readOnly' => true,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sw_language_id',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
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
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim,required',
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
                'eval' => 'int',
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
                'renderType' => 'inputLink',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'file,folder,page,mail,telephone',
                        ],
                    ],
                ],
            ],
        ],
        'sorting' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_media.sorting',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
    ],
];
