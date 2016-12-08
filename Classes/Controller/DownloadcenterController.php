<?php
namespace Azurgruen\AzgrDownloadcenter\Controller;

use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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
 * DownloadcenterController
 */
class DownloadcenterController extends ActionController
{
	
	/**
     * DownloadRepository
     *
     * @var \Azurgruen\AzgrDownloadcenter\Domain\Repository\DownloadRepository
     * @inject
     */
    protected $downloadRepository = null;
    
    /**
     * @var \Azurgruen\AzgrDownloadcenter\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository;
    
    /**
	 * @var \TYPO3\CMS\Core\Resource\FileCollectionRepository
	 * @inject
	 */
	protected $fileCollectionRepository;
	
	/**
	 * @var \TYPO3\CMS\Core\Resource\FileRepository
	 * @inject
	 */
	protected $fileRepository;
	
	/**
     * @var array
     */
    protected $downloadsections = [];
    
	/**
     * @param int $uid 
     * @return array
     */
    public function getCategoriesFromFile($uid)
    {
        $query = $this->categoryRepository->createQuery();
        $sql = "SELECT sys_category.* FROM sys_category
            INNER JOIN sys_category_record_mm ON sys_category_record_mm.uid_local = sys_category.uid AND sys_category_record_mm.fieldname = 'categories' AND sys_category_record_mm.tablenames = 'sys_file_metadata'
            INNER JOIN sys_file_metadata ON  sys_category_record_mm.uid_foreign = sys_file_metadata.uid
            WHERE sys_file_metadata.file = '" . (int)$uid . "'
            AND sys_category.deleted = 0
            ORDER BY sys_category_record_mm.sorting_foreign ASC";
        $result = $query->statement($sql)->execute()->toArray();
        return $result;
    }
    
/*
    protected function getFileReferences($uid) {
		$fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
		$fileObjects = $fileRepository->findByRelation('tt_content', 'image', $uid);
		// get Imageobject information
		$files = array();
 
		//print_r($fileObjects);
 
		foreach ($fileObjects as $key => $value) {
		  $files[$key]['reference'] = $value->getReferenceProperties();
		  $files[$key]['original'] = $value->getOriginalFile()->getProperties();
		}
 
 
		return $files;
	}
*/
    
/*
    protected function getFileReferences()
    {
	    $asd = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
	    $query = $asd->createQuery();
	    $sql = "SELECT uid, uid_local FROM sys_file_reference";
        $result = $query->statement($sql)->execute()->toArray();
        return $result;
    }
*/
    
    /**
     * action index
     *
     * @return void
     */
    public function indexAction()
    {
	    //$refs = $this->getFileReferences($this->configurationManager->getContentObject()->data['uid']);
	    //$refs = $this->getFileReferences();
	    foreach ($this->settings['downloadsections'] as $key => $downloads) {
		    $this->downloadsections[$key] = [
			    'title' => $downloads['title'],
			    'collections' => []
		    ];
		    $collectionIds = explode(',', $downloads['filecollections']);
		    foreach ($collectionIds as $collectionId) {
			    /** @var \TYPO3\CMS\Core\Collection\StaticRecordCollection $collection */
			    $collection = $this->fileCollectionRepository->findByUid($collectionId);
			    $categoryId = $collection->getItemsCriteria();
			    if (intval($categoryId) !== 1) {
				    $collectionCategory = $this->categoryRepository->findByUid($categoryId);
				    $collection->category = $collectionCategory;
			    }
			    if ($collection !== null) {
					$collection->loadContents();
					$this->downloadsections[$key]['collections'][] = $collection;
					foreach ($collection as $file) {
						$file->categories = $this->getCategoriesFromFile($file->getUid());
						//$file->ref = $this->fileRepository->findFileReferenceByUid($file->getUid());
						if ($file->getCreationTime() > strtotime('-'.$this->settings['newUntil'].' day')) {
							$file->isnew = true;
						} else {
							$file->isnew = false;
						}
					}
				}
		    }
	    }
	    $this->contentObj = $this->configurationManager->getContentObject();
        //DebuggerUtility::var_dump($this->downloadsections);
        //$this->view->assign('downloadsections', $this->downloadsections);
        $this->view->assignMultiple([
	        'header' => $this->contentObj->data['header'],
        	'downloadsections' => $this->downloadsections
        ]);
    }
}
