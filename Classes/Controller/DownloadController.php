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
     * Name for cookie containing file uids
     */
	const COOKIE_FILES = 'azgrdlc_files';
	
	/**
     * Name for cookie containing zip uuids
     */
	const COOKIE_AUTH = 'azgrdlc_auth';
	
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
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files 
     * @return void
     */
    protected function addFilesToZip($files)
    {
		$dir = $this->settings['zip']['dir'];
		$pathprefix = $this->settings['filemount'];
		foreach ($files as $fileReference) {
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
			$filename = 'fileadmin/'.$this->settings['uploadDir'].$this->settings['zip']['prefix'].$this->uuid.'.zip';
			if ($this->zip->open($filename, ZipArchive::CREATE) !== true) {
			    return '{"error": "no file created"}';
			    exit;
			}
			if (!empty($this->settings['zip']['defaultFiles'])) {
				$this->addFilesToZip($this->settings['zip']['defaultFiles']);
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
	 * @param array $arguments
     * @return string
     */
	protected function buildUri($arguments = null)
	{
        $uriBuilder = $this->getControllerContext()->getUriBuilder();
        $uriBuilder->reset();
		$uriBuilder->setArguments([
			'tx_azgrdownloadcenter_downloadcenter' => $arguments
		]);
		$uri = $uriBuilder->setCreateAbsoluteUri(true)->setUseCacheHash(false)->buildFrontendUri();
		return $uri;
	}
	
	/**
     * @param array $sender sender of the email in the format array('sender@domain.tld' => 'Sender Name')
     * @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param string $subject subject of the email
     * @param string $templateName template name (UpperCamelCase)
     * @param array $variables variables to be passed to the Fluid view
     * @return boolean TRUE on success, otherwise false
     */
    protected function sendTemplateEmail(array $sender, array $recipient, $subject, $layout, $template, array $variables)
    {
		$emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$templatePathAndFilename = GeneralUtility::getFileAbsFileName($template . '.html');
		//$layoutPath = GeneralUtility::getFileAbsFileName($layout . '.html');
		//DebuggerUtility::var_dump(get_class_methods($emailView));
		$emailView->setLayoutRootPath($layout);
		$emailView->setTemplatePathAndFilename($templatePathAndFilename);
		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();
		
		$message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
		$message
		->setTo($recipient)
		->setFrom($sender)
		->setSubject($subject);
		
		// Possible attachments here
/*
		foreach ($attachments as $attachment) {
		    $message->attach($attachment);
		}
*/
		
		$message->setBody($emailBody, 'text/plain');
		//$message->setBody($emailBody, 'text/html');
		
		$message->send();
		return $message->isSent();
    }
	
    /**
     * action get
     *
     * @return void
     */
    public function getAction()
    {
	    $uuid = $this->request->getArgument('download');
	    $error = false;
        $file = 'fileadmin/'.$this->settings['uploadDir'].$this->settings['zip']['prefix'].$uuid.'.zip';
        
        if (!is_file($file)) $error = true;
        
        if (isset($_COOKIE[self::COOKIE_AUTH])) {
	        $cookie = $_COOKIE[self::COOKIE_AUTH];
	        $cookiedata = unserialize($cookie);
	        if (!in_array($uuid, $cookiedata)) $error = true;
        } else {
	        $error = true;
        }
        
        if (!$error) {
	        $headers = [
				'Pragma' => 'no-cache', 
				'Expires' => 'Wed, 21 Oct 2015 00:00:00 GMT',
				//'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
				'Cache-Control' => 'no-cache',
				'Content-Description' => 'File Download',
				'Content-Disposition' => 'attachment; filename="'. $this->settings['zip']['prefix'].$uuid.'.zip"',
				'Content-Transfer-Encoding' => 'binary',
				'Content-Length' => filesize($file),
				'Content-Type' => 'application/zip'
			];
			
			foreach($headers as $header => $data) {
				$this->response->setHeader($header, $data); 
			}
			
			$this->response->sendHeaders();              
			@readfile($file);
        }
    }
    
    /**
     * action new
     *
     * @param \Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download
     * @return void
     */
    public function newAction(\Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download = null)
    {
        $this->view->assignMultiple([
        	'download' => $download,
        	'files' => json_decode($_COOKIE[self::COOKIE_FILES])
        ]);
    }
    
    /**
     * set up download model
     */
    public function initializeCreateAction()
    {
	    // convert files to file references and save them for later use
		$files = $this->request->getArgument('files');
	    foreach ($files as $uid) {
			$file = $this->fileRepository->findByUid(intval($uid));
			$fileReference = $this->objectManager->get('Azurgruen\\AzgrDownloadcenter\\Domain\\Model\\FileReference');
			$fileReference->setFile($file);
			$this->files[] = $fileReference;
			//$download->addFile($fileReference);
		}
    }
    
    /**
     * action create
     *
     * @param \Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download
     * @return void
     */
    public function createAction(\Azurgruen\AzgrDownloadcenter\Domain\Model\Download $download = null)
    {
	    // add file references to the download model (created in initializeCreateAction)
	    foreach ($this->files as $fileReference) {
		    $download->addFile($fileReference);
	    }
		$this->uuid = uniqid();
		$linkarguments = [
			'download' => $this->uuid,
			'controller' => 'Download',
			'action' => 'get'
		];
		if (isset($_COOKIE[self::COOKIE_AUTH])) {
			$cookiedata = unserialize($_COOKIE[self::COOKIE_AUTH]);
			$cookiedata[] = $this->uuid;
		} else {
			$cookiedata = [$this->uuid];
		}
		$cookiedata = serialize($cookiedata);
		$cookie = setcookie(self::COOKIE_AUTH, $cookiedata, time()+60*60*24*$this->settings['zip']['ttl'], '/');
	    $zipFile = $this->createZip($download->getFiles());
	    $download->setUuid($this->uuid);
        $downloads = $this->downloadRepository->add($download);
        $mail = $this->sendTemplateEmail(
        	[$this->settings['mail']['senderAddress'] => $this->settings['mail']['senderName']],
        	[$download->getEmail() => $download->getFirstName() .' '. $download->getLastName()],
        	$this->settings['mail']['subject'],
        	'EXT:azgr_downloadcenter/Resources/Private/Layouts/',
        	$this->view->getTemplateRootPaths()[0] . 'Email/New',
        	[
        		'data' => $download,
        		'file' => $this->buildUri($linkarguments),
        		'ttl' => $this->settings['zip']['ttl']
        	]
        );
	    
        $this->view->assignMultiple([
        	'download' => $download,
        	'file' => $zipFile
        ]);
    }
}
