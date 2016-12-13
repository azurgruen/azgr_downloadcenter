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
                'Downloadcenter' => 'index',
                'Download' => 'new, create, get'
            ],
            // non-cacheable actions
            [
	            'Downloadcenter' => '',
                'Download' => 'get'
            ]
        );

    },
    $_EXTKEY
);
