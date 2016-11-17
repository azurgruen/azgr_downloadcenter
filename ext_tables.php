<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Azurgruen.AzgrDownloadcenter',
            'Downloadcenter',
            'Downloadcenter'
        );
        
        $pluginSignature = str_replace('_', '', $extKey) . '_downloadcenter';
        
        $GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['AzgrDownloadcenterWizicon'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Classes/Helper/AzgrDownloadcenterWizicon.php';
        
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,recursive,pages';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_downloadcenter.xml');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'Downloadcenter');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_azgrdownloadcenter_domain_model_downloads', 'EXT:azgr_downloadcenter/Resources/Private/Language/locallang_csh_tx_azgrdownloadcenter_domain_model_downloads.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_azgrdownloadcenter_domain_model_downloads');

    },
    $_EXTKEY
);
