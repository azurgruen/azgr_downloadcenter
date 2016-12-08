<?php
namespace Azurgruen\AzgrDownloadcenter\Controller;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
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
 * DownloadController
 */
class DownloadController extends ActionController
{
	/**
     * DownloadRepository
     *
     * @var \Azurgruen\AzgrDownloadcenter\Domain\Repository\DownloadRepository
     * @inject
     */
    protected $downloadRepository;
    
    /**
	 * @var \TYPO3\CMS\Core\Resource\FileRepository
	 * @inject
	 */
	protected $fileRepository;
		    
    /**
     * @var ZipArchive
     */
    protected $zip;
    
    /**
     * @var string
     */
    protected $uuid;
    
    /**
     * @var array
     */
    protected $files;
    
    /**
     * @return array
     */
/*
    public function getReferenceUids()
    {
        $this->fileRepository->createQuery();
        $sql = 'SELECT uid,uid_local FROM sys_file_reference';
        $result = $query->statement($sql)->execute()->toArray();
        return $result;
    }
*/
    
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files 
     * @return void
     */
    protected function addFilesToZip($files)
    {
		$dir = $this->settings['zipDir'];
		$pathprefix = $this->settings['filemount'];
		foreach ($files as $fileReference) {
			//DebuggerUtility::var_dump(get_class_methods($fileReference->getOriginalResource()));
			//$file = $fileReference->getOriginalResource()->getOriginalFile();
			$file = $fileReference->getOriginalResource();
			$this->zip->addFile($pathprefix.$file->getIdentifier(), $dir.$file->getName());
		}
    }
    
    /**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files
     * @return string
     */
    protected function createZip($files)
    {
		if (!empty($files)) {
		    $this->zip = new ZipArchive();
			$filename = 'fileadmin/'.$this->settings['uploadDir'].$this->settings['zipPrefix'].$this->uuid.'.zip';
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
		    return $file;
		} else {
			return '{"error": "no files defined"}';
		}
	}
	
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $downloads = $this->downloadRepository->findAll();
        $this->view->assign('downloads', $downloads);
    }
    
    /**
     * action new
     *
     * @param \Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download
     * @return void
     */
    public function newAction(\Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download = null)
    {
	    //$download = new \Azurgruen\AzgrDownloadcenter\Domain\Model\Download();
	    //DebuggerUtility::var_dump($this->getReferenceUids());
        $this->view->assignMultiple([
        	'download' => $download,
        	'files' => json_decode($_COOKIE['azgrdlc'])
        	//'files' => implode(',',json_decode($_COOKIE['azgrdlc']))
        ]);
    }
    
    /**
     * set up download model
     */
    public function initializeCreateAction()
    {
/*
        $download = $this->request->getArgument('download');
        DebuggerUtility::var_dump($download);
*/
		$files = $this->request->getArgument('files');
	    foreach ($files as $uid) {
			$file = $this->fileRepository->findByUid(intval($uid));
			//$fileref = $this->fileRepository->findFileReferenceByUid(intval($uid));
			$fileReference = $this->objectManager->get('Helhum\\UploadExample\\Domain\\Model\\FileReference');
			$fileReference->setFile($file);
			$this->files[] = $fileReference;
			//$download->addFile($fileReference);
			//DebuggerUtility::var_dump($fileReference);
		}
    }
    
    /**
     * action create
     *
     * @param \Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download
     * @ignorevalidation $download
     * @return void
     */
    public function createAction(\Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download = null)
    {
	    foreach ($this->files as $fileReference) {
		    $download->addFile($fileReference);
	    }
		//DebuggerUtility::var_dump($this->files);
		
		$this->uuid = uniqid();
	    $zipFile = $this->createZip($download->getFiles());
	    //exit;
	    $download->setUuid($this->uuid);
	    //$download-setFilename($zipFile);
        $downloads = $this->downloadRepository->add($download);
    }
}
