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

namespace Madj2k\ShopwareConnector\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Media
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @package Madj2k_ShopwareConnector
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Media extends AbstractEntity
{
    /**
     * @var string
     */
    protected string $swId = '';


    /**
     * @var string
     */
    protected string $swLanguageId = '';


    /**
     * @var string
     */
    protected string $mimeType = '';


    /**
     * @var string
     */
    protected string $fileName = '';


    /**
     * @var string
     */
    protected string $fileExtension = '';


    /**
     * @var int
     */
    protected int $fileSize = 0;


    /**
     * @var string
     */
    protected string $title = '';


    /**
     * @var string
     */
    protected string $alternative = '';


    /**
     * @var string
     */
    protected string $url = '';


    /**
     * @var int
     */
    protected int $sorting = 0;


    /**
     * Get the swId
     *
     * @return string
     */
    public function getSwId(): string
    {
        return $this->swId;
    }


    /**
     * Set the swId
     *
     * @param string $swId
     * @return void
     */
    public function setSwId(string $swId): void
    {
        $this->swId = $swId;
    }


    /**
     * Get the Shopware language ID
     *
     * @return string
     */
    public function getSwLanguageId(): string
    {
        return $this->swLanguageId;
    }


    /**
     * Set the Shopware language ID
     *
     * @param string $swLanguageId
     * @return void
     */
    public function setSwLanguageId(string $swLanguageId): void
    {
        $this->swLanguageId = $swLanguageId;
    }


    /**
     * Get the MIME type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }


    /**
     * Set the MIME type
     *
     * @param string $mimeType
     * @return void
     */
    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }


    /**
     * Get the file name
     *
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }


    /**
     * Set the file name
     *
     * @param string $fileName
     * @return void
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }


    /**
     * Get the file extension
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }


    /**
     * Set the file extension
     *
     * @param string $fileExtension
     * @return void
     */
    public function setFileExtension(string $fileExtension): void
    {
        $this->fileExtension = $fileExtension;
    }


    /**
     * Get the file size
     *
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }


    /**
     * Set the file size
     *
     * @param int $fileSize
     * @return void
     */
    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }


    /**
     * Get the title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * Set the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    /**
     * Get the alternative text
     *
     * @return string
     */
    public function getAlternative(): string
    {
        return $this->alternative;
    }


    /**
     * Set the alternative text
     *
     * @param string $alternative
     * @return void
     */
    public function setAlternative(string $alternative): void
    {
        $this->alternative = $alternative;
    }


    /**
     * Get the URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    /**
     * Set the URL
     *
     * @param string $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }


    /**
     * Get the sorting
     *
     * @return int
     */
    public function getSorting(): int
    {
        return $this->sorting;
    }


    /**
     * Set the sorting
     *
     * @param int $sorting
     * @return void
     */
    public function setSorting(int $sorting): void
    {
        $this->sorting = $sorting;
    }
}
