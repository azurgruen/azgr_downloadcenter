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
 * Category
 */
class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category {

	/**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @lazy
     */
    protected $images;
    
    /**
     * Initialize images
     *
     * @return \Azurgruen\AzgrDownloadcenter\Domain\Model\Category
     */
    public function __construct()
    {
        $this->images = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * Get the first image
     *
     * @return FileReference|null
     */
    public function getFirstImage()
    {
        $images = $this->getImages();
        foreach ($images as $image) {
            return $image;
        }

        return null;
    }

}