<?php
namespace Azurgruen\AzgrDownloadcenter\Controller;

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
 * DownloadsController
 */
class DownloadsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	/**
     * DownloadsRepository
     *
     * @var \Azurgruen\AzgrDownloadcenter\Domain\Repository\DownloadsRepository
     * @inject
     */
    protected $downloadsRepository;
	
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $downloads = $this->downloadsRepository->findAll();
        $this->view->assign('downloads', $downloads);
    }
    
    /**
     * action new
     *
     * @param \Azurgruen\AzgrDownloadcenter\Domain\Model\Downloads $download
     * @return void
     */
    public function newAction(\Azurgruen\AzgrDownloadcenter\Domain\Model\Downloads $download = null)
    {
        $this->view->assign('download', $download);
    }
    
    /**
     * action create
     *
     * @param \Azurgruen\AzgrDownloadcenter\Domain\Model\Downloads $download
     * @return void
     */
    public function createAction(\Azurgruen\AzgrDownloadcenter\Domain\Model\Downloads $download = null)
    {
	    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($download);
        $downloads = $this->downloadsRepository->add($download);
    }
}
