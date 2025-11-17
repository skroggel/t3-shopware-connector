<?php
declare(strict_types=1);
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$iconList = [];
foreach (
    [
        'shopwareConnector-plugin-apiRequest' => 'plugin-apiRequest.svg',
    ] as $identifier => $path) {
    $iconList[$identifier] = [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:shopware_connector/Resources/Public/Icons/' . $path,
    ];
}

return $iconList;
