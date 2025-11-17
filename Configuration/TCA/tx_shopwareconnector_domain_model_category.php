<?php

$ll = 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_shopwareconnector_domain_model_category',
        'label' => 'name',
        'label_alt' => 'sw_id',
        'label_alt_force' => 1,
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
        'iconfile' => 'EXT:shopware_connector/Resources/Public/Icons/tx_shopwareconnector_domain_model_category.svg',
        'searchFields' => 'name,description,slug',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --palette--;' . $ll . 'palette.general;general,
                --palette--;' . $ll . 'palette.relations;relations,
                --div--;' . $ll . 'tab.shopware,
                    --palette--;' . $ll . 'palette.shopware;shopware,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    sys_language_uid, l10n_parent, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hide_in_menu, hidden, starttime, endtime'
        ],
    ],
    'palettes' => [
        'general' => [
            'label' => $ll . 'palette.general',
            'showitem' => 'name, --linebreak--, slug, --linebreak--, description',
        ],
        'relations' => [
            'label' => $ll . 'palette.relations',
            'showitem' => 'parent',
        ],
        'shopware' => [
            'label' => $ll . 'palette.shopware',
            'showitem' => 'sw_id, --linebreak--, sw_language_id',
        ],
    ],
    'columns' => [
        'sw_id' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.sw_id',
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
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.sw_language_id',
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
        'parent' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.parent',
            'config' => [
				'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_shopwareconnector_domain_model_category',
                'foreign_table_where' => 'AND tx_shopwareconnector_domain_model_category.pid=###CURRENT_PID### AND tx_shopwareconnector_domain_model_category.sys_language_uid IN (-1,0)',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'name' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.name',
            'config' => [
				'readOnly' => true,
                'required' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.description',
            'config' => [
				'readOnly' => true,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'slug' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.slug',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,unique',
            ],
        ],
        'hide_in_menu' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_category.hide_in_menu',
            'config' => [
				'readOnly' => true,
                'type' => 'check',
                'renderType' => 'checkboxToggle'
            ],
        ],
    ],
];
