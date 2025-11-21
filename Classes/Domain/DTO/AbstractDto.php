<?php
declare(strict_types=1);

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or (at your option) any later version.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Madj2k\ShopwareConnector\Domain\DTO;

/**
 * Class AbstractDto
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class AbstractDto implements DtoInterface
{
    /**
     * @var array
     */
    protected array $data = [];


    /**
     * constructor with reference to original array
     *
     * @param array $data
     */
    public function __construct(array &$data = [])
    {
        $this->data =& $data;
    }


    /**
     * @param bool $ignoreEmpty
     * @return array
     */
    public function _toArray(bool $ignoreEmpty = false): array
    {
        $result = [];
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getMethods() as $method) {
            if (strpos($method->getName(), 'get') === 0 && $method->getNumberOfRequiredParameters() === 0) {
                $key = lcfirst(substr($method->getName(), 3));

                if ($ignoreEmpty) {
                    if (!empty($this->{$method->getName()}())) {
                        $result[$key] = $this->{$method->getName()}();
                    }
                } else {
                    $result[$key] = $this->{$method->getName()}();
                }
            }
        }
        return $result;
    }


    /**
     * @return array
     */
    public function _toArrayRaw(): array
    {
        return $this->data;
    }
}
