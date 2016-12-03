<?php
defined('TYPO3_MODE') || die('Access denied.');

// Define FSC as content rendering template
$GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'fluidstyledcontent/Configuration/TypoScript/Static/';

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Azurgruen.AzgrDownloadcenter',
            'Downloadcenter',
            [
                'Downloadcenter' => 'index'
            ],
            // non-cacheable actions
            [
	            'Downloadcenter' => '',
                'Downloads' => ''
            ]
        );
        
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Azurgruen.AzgrDownloadcenter',
            'zip',
            [
                'Downloadcenter' => 'create'
            ],
            // non-cacheable actions
            [
	            'Downloadcenter' => 'create'
            ]
        );

    },
    $_EXTKEY
);
