<?php
declare(strict_types=1);
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$iconList = [];
foreach (
    [
        'shopwareconnector-plugin-full' => 'plugin-full.svg',
        'shopwareconnector-plugin-list' => 'plugin-list.svg',
        'shopwareconnector-plugin-detail' => 'plugin-detail.svg',
        'shopwareconnector-plugin-download' => 'plugin-download.svg',
    ] as $identifier => $path) {
    $iconList[$identifier] = [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:shopware_connector/Resources/Public/Icons/' . $path,
    ];
}

return $iconList;
