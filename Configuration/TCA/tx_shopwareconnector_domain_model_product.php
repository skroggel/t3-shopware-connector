<?php

$ll = 'LLL:EXT:shopware_connector/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_shopwareconnector_domain_model_product',
        'label' => 'name',
        'label_alt' => 'product_number, ean',
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
        'iconfile' => 'EXT:shopware_connector/Resources/Public/Icons/tx_shopwareconnector_domain_model_product.svg',
        'searchFields' => 'name, description, product_number, ean',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --palette--;' . $ll . 'palette.general;general,
                --div--;' . $ll . 'tab.relations,
                    --palette--;' . $ll . 'palette.relations;relations,
                --div--;' . $ll . 'tab.pricing,
                    --palette--;' . $ll . 'palette.pricing;pricing,
                --div--;' . $ll . 'tab.stock,
                    --palette--;' . $ll . 'palette.stock;stock,
                --div--;' . $ll . 'tab.shipping,
                    --palette--;' . $ll . 'palette.shipping;shipping,
                --div--;' . $ll . 'tab.dimensions,
                    --palette--;' . $ll . 'palette.dimensions;dimensions,
                --div--;' . $ll . 'tab.media,
                    --palette--;' . $ll . 'palette.media;media,
                --div--;' . $ll . 'tab.shopware,
                    --palette--;' . $ll . 'palette.shopware;shopware,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    sys_language_uid, l10n_parent, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden, starttime, endtime'
        ],
    ],
    'palettes' => [
        'general' => ['showitem' => 'name, is_new, --linebreak--, description, --linebreak--, product_number, ean', 'label' => $ll . 'palette.general'],
        'relations' => ['showitem' => 'categories, --linebreak--, properties', 'label' => $ll . 'palette.relations'],
        'pricing' => ['showitem' => 'price, --linebreak--, calculated_price', 'label' => $ll . 'palette.pricing'],
        'stock' => ['showitem' => 'available,--linebreak--, available_stock, stock, restock_time, --linebreak--, purchase_steps, max_purchase, min_purchase, --linebreak--, purchase_unit', 'label' => $ll . 'palette.stock'],
        'shipping' => ['showitem' => 'shipping_free, --linebreak--, delivery_time, --linebreak--, pack_unit, pack_unit_plural', 'label' => $ll . 'palette.shipping'],
        'dimensions' => ['showitem' => 'weight, width, height, length', 'label' => $ll . 'palette.dimensions'],
        'meta' => ['showitem' => 'meta_title, meta_description, meta_keywords, tags', 'label' => $ll . 'palette.meta'],
        'media' => ['showitem' => 'cover, --linebreak--, cover_mime_type', 'label' => $ll . 'palette.media'],
        'shopware' => ['showitem' => 'sw_id, --linebreak--, sw_language_id, --linebreak--, sw_manufacturer_id', 'label' => $ll . 'palette.shopware'],
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
                'foreign_table' => 'tx_shopwareconnector_domain_model_product',
                'foreign_table_where' => 'AND tx_shopwareconnector_domain_model_product.pid=###CURRENT_PID### AND tx_shopwareconnector_domain_model_product.sys_language_uid IN (-1,0)',
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
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.sw_id',
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
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.sw_language_id',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'sw_manufacturer_id' => [
            'exclude' => false,
            'readOnly' => true,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.sw_manufacturer_id',
            'config' => [
				'readOnly' => true,
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
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.parent',
            'config' => [
				'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_shopwareconnector_domain_model_product',
                'foreign_table_where' => 'AND tx_shopwareconnector_domain_model_product.pid=###CURRENT_PID### AND tx_shopwareconnector_domain_model_product.sys_language_uid IN (-1,0)',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'product_number' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.product_number',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'name' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.name',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'description' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.description',
            'config' => [
				'readOnly' => true,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
        ],
        'ean' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.ean',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'is_new' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.is_new',
            'config' => [
				'readOnly' => true,
                'type' => 'check',
                'renderType' => 'checkboxToggle'
            ],
        ],
        'custom_fields' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.custom_fields',
            'config' => [
				'readOnly' => true,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'release_date' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.release_date',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ],
        ],
        'price' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.price',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2,required',
            ],
        ],
        'calculated_price' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.calculated_price',
            'config' => [
				'readOnly' => true,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'available_stock' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.available_stock',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
        'stock' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.stock',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
        'available' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.available',
            'config' => [
				'readOnly' => true,
                'type' => 'check',
                'renderType' => 'checkboxToggle'
            ],
        ],
        'restock_time' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.restock_time',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
        'shipping_free' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.shipping_free',
            'config' => [
				'readOnly' => true,
                'type' => 'check',
                'renderType' => 'checkboxToggle'
            ],
        ],
        'delivery_time' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.delivery_time',
            'config' => [
				'readOnly' => true,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'purchase_steps' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.purchase_steps',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
        'max_purchase' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.max_purchase',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
        'min_purchase' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.min_purchase',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
        'purchase_unit' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.purchase_unit',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'pack_unit' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.pack_unit',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'pack_unit_plural' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.pack_unit_plural',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'weight' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.weight',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
            ],
        ],
        'width' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.width',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
            ],
        ],
        'height' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.height',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
            ],
        ],
        'length' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.length',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 10,
                'eval' => 'double2',
            ],
        ],
        'tags' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.tags',
            'config' => [
				'readOnly' => true,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'cover' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.cover',
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
        'cover_mime_type' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.cover_mime_type',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'meta_title' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.meta_title',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'meta_description' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.meta_description',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'meta_keywords' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.meta_keywords',
            'config' => [
				'readOnly' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
        'categories' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.categories',
            'config' => [
				'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_shopwareconnector_domain_model_category',
                'foreign_table_where' => 'AND tx_shopwareconnector_domain_model_category.pid=###CURRENT_PID### AND tx_shopwareconnector_domain_model_category.sys_language_uid IN (-1,0)',
                'MM' => 'tx_shopwareconnector_product_categories_mm',
                'minitems' => 0,
                'maxitems' => 99999,
            ],
        ],
        'properties' => [
            'exclude' => false,
            'label' => $ll . 'tx_shopwareconnector_domain_model_product.properties',
            'config' => [
				'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_shopwareconnector_domain_model_property',
                'foreign_table_where' => 'AND tx_shopwareconnector_domain_model_property.pid=###CURRENT_PID### AND tx_shopwareconnector_domain_model_property.sys_language_uid IN (-1,0)',
                'minitems' => 0,
                'maxitems' => 99999,
            ],
        ],
    ],
];
