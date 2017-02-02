<?php
namespace Azurgruen\AzgrDownloadcenter\Task;

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
 * Cleaner
 */

class Cleaner extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
	    
    public function getSettings()
    {
	    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');    
		$configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
		$settings = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		return $settings['plugin.']['tx_azgrdownloadcenter_downloadcenter.']['settings.'];
    }
	
	public function execute()
	{
		$settings = $this->getSettings();
		$dir = $settings['uploadDir'];
		$ttl = $settings['zip']['ttl'];
		$resourceFactory = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance();
		$storage = $resourceFactory->getDefaultStorage();
		$folder = $storage->getFolder($dir);
		$files = $storage->getFilesInFolder($folder);
		foreach($files as $file) {
			if ($file->getCreationTime() < strtotime('-'.$ttl.' day')) {
				\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($file->getName());
			}
		}
		return true;
	}
	
}
?>