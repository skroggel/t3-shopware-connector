<?php

$ll = 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_db.xlf:';
return [
    'ctrl' => [
        'title' => $ll . 'tx_shopwareconnector_domain_model_cart',
        'label' => 'name',
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
        'iconfile' => 'EXT:shopware_connector/Resources/Public/Icons/tx_shopwareconnector_domain_model_cart.svg',
        'searchFields' => 'name, value, group_name',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --palette--;' . $ll . 'palette.general;general,
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
            'showitem' => '
                name,
                --linebreak--,
                value,
                --linebreak--,
                group_name,
                --linebreak--,
                sorting',
        ],
        'shopware' => [
            'label' => $ll . 'palette.shopware',
            'showitem' => '
                sw_id,
                --linebreak--,
                sw_language_id',
        ],
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
                'foreign_table' => 'tx_shopwareconnector_domain_model_cart',
                'foreign_table_where' => 'AND tx_shopwareconnector_domain_model_cart.pid=###CURRENT_PID### AND tx_shopwareconnector_domain_model_cart.sys_language_uid IN (-1,0)',
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
        'shopware_id' => [
            'exclude' => false,
            'readOnly' => true,
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.shopware_id',
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
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.sw_language_id',
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
        'name' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.name',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim,required',
            ],
        ],
        'value' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.value',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'group_name' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.group_name',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'sorting' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.sorting',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
    ],
];
