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
     * @param string $files 
     * @return void
     */
    protected function addFilesToZip($files)
    {
	    $dir = $this->settings['zipDir'];
	    $pathprefix = $this->settings['filemount'];
	    
	    if (strpos($files, ',') !== false) {
			$files = explode(',', $files);
			foreach ($files as $uid) {
			    //$file = $this->fileRepository->findByUid(intval($uid));
			    $this->zip->addFile($pathprefix.$file->getIdentifier(), $dir.$file->getName());
		    }
		} else {
			$file = $this->fileRepository->findByUid(intval($files));
		    $this->zip->addFile($pathprefix.$file->getIdentifier(), $dir.$file->getName());
		}
    }
    
    /**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files
     * @return string
     */
    protected function createZip($files)
    {
	    if ($this->request->hasArgument('files')) {
			$files = $this->request->getArgument('files');
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
		} else {
			return '{"error": "no arguments"}';
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
     * @return void
     */
    public function newAction()
    {
	    $download = new \Azurgruen\AzgrDownloadcenter\Domain\Model\Download();
	    //DebuggerUtility::var_dump($this->getReferenceUids());
        $this->view->assignMultiple([
        	'download' => $download,
        	'files' => json_decode($_COOKIE['azgrdlc'])
        	//'files' => implode(',',json_decode($_COOKIE['azgrdlc']))
        ]);
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
/*
	    $files = explode(',', GeneralUtility::_GP('files'));
	    foreach ($files as $uid) {
		    $file = $this->fileRepository->findByUid(intval($uid));
		    //$fileref = $this->fileRepository->findFileReferenceByUid(intval($uid));
		    DebuggerUtility::var_dump($file);
		    //$download->addFile($file);
		}
*/
	    DebuggerUtility::var_dump($download);
	    exit;
	    $zipFile = $this->createZip($download->files);
	    $download->uuid = $uniqid();
	    $download->file = $zipFile;
	    DebuggerUtility::var_dump($download);
        //$downloads = $this->downloadRepository->add($download);
    }
}
