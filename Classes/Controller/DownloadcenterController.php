<?php
namespace Azurgruen\AzgrDownloadcenter\Controller;

use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \ZipArchive;

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
     * DownloadsRepository
     *
     * @var \Azurgruen\AzgrDownloadcenter\Domain\Repository\DownloadsRepository
     * @inject
     */
    protected $downloadsRepository = null;
    
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
     * @var string
     */
    protected $hash;
    
    /**
     * @var ZipArchive
     */
    protected $zip;
    
    public function __construct()
    {
	    $this->hash = uniqid();
    }
	
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
    
    /**
     * @param string $files 
     * @return void
     */
    public function addFilesToZip($files)
    {
	    $dir = $this->settings['zipDir'];
	    $pathprefix = $this->settings['filemount'];
	    
	    if (strpos($files, ',') !== false) {
			$files = explode(',', $files);
			foreach ($files as $uid) {
			    $file = $this->fileRepository->findByUid(intval($uid));
			    $this->zip->addFile($pathprefix.$file->getIdentifier(), $dir.$file->getName());
		    }
		} else {
			$file = $this->fileRepository->findByUid(intval($files));
		    $this->zip->addFile($pathprefix.$file->getIdentifier(), $dir.$file->getName());
		}
    }
    
    /**
     * action create
     *
     * @return string
     */
    public function createAction()
    {
	    if (!defined('TYPO3_MODE')) {
		    die('Access denied.');
		}
	    if ($this->request->hasArgument('files')) {
			$files = $this->request->getArgument('files');
			if (!empty($files)) {
			    $this->zip = new ZipArchive();
				$filename = 'fileadmin/'.$this->settings['uploadDir'].$this->settings['zipPrefix'].$this->hash.'.zip';
				if ($this->zip->open($filename, ZipArchive::CREATE) !== true) {
				    return '{"error": "no file created"}';
				    exit;
				}
				if (!empty($this->settings['filesDefault'])) {
					$this->addFilesToZip($this->settings['filesDefault']);
				}
				$this->addFilesToZip($files);
			    $this->zip->close();
				$file = $this->request->getBaseUri().$filename;
			    return json_encode(['file' => $file], JSON_UNESCAPED_SLASHES);
			} else {
				return '{"error": "no files defined"}';
			}
		} else {
			return '{"error": "no arguments"}';
		}
	}
	    
    /**
     * action list
     *
     * @return void
     */
    public function indexAction()
    {
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
        	'downloadsections' => $this->downloadsections,
        	'hash' => $this->hash
        ]);
    }
}
