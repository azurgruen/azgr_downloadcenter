<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class AzgrDownloadcenterWizicon {

        /**
         * Processing the wizard items array
         *
         * @param array $wizardItems The wizard items
         * @return array Modified array with wizard items
         */
        function proc($wizardItems)     {
                $wizardItems['plugins_tx_azgrdownloadcenter_downloadcenter'] = array(
                        'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('azgr_downloadcenter') . 'Resources/Public/Icons/wizard.png',
                        //'title' => $GLOBALS['LANG']->sL('LLL:EXT:examples/locallang.xlf:pierror_wizard_title'),
                        //'description' => $GLOBALS['LANG']->sL('LLL:EXT:examples/locallang.xlf:pierror_wizard_description'),
                        'title' => 'Downloadcenter',
                        'description' => 'Individual collection of downloads chosen by user',
                        'params' => '&defVals[tt_content][CType]=list&&defVals[tt_content][list_type]=azgrdownloadcenter_downloadcenter'
                );
                //file_put_contents('/Users/bs/_www/_sandbox/t3/typo3conf/ext/azgr_staff/azgr.txt', 'asd', FILE_APPEND);

                return $wizardItems;
        }
}

?>