<?php
namespace Azurgruen\AzgrDownloadcenter\Domain\Model;

/***
 *
 * This file is part of the "Downloadcenter" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Bernhard Schütz <schuetz@azurgruen.de>, azurgruen // code + design
 *
 ***/

/**
 * Downloads
 */
class Downloads extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * uuid
     *
     * @var string
     */
    protected $uuid = '';

    /**
     * filename
     *
     * @var string
     */
    protected $filename = '';

    /**
     * files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $files;

    /**
     * Returns the uuid
     *
     * @return string $uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Sets the uuid
     *
     * @param string $uuid
     * @return void
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Returns the filename
     *
     * @return string $filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Sets the filename
     *
     * @param string $filename
     * @return void
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Returns the files
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets the files
     *
     * @param string $files
     * @return void
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
}
