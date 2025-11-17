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

        'shopware_id' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.shopware_id',
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
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.sw_language_id',
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
        'name' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_cart.name',
            'config' => [
				'readOnly' => true,
                'required' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trimd',
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
                'eval' => 'number',
            ],
        ],
    ],
];
