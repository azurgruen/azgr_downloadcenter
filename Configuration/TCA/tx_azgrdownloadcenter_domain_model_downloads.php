<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads',
        'label' => 'uuid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => 1,
		'versioningWS' => 2,
        'versioning_followPages' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
        'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'uuid,filename,files,salutation,first_name,last_name,company,email',
        'iconfile' => 'EXT:azgr_downloadcenter/Resources/Public/Icons/tx_azgrdownloadcenter_domain_model_downloads.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, uuid, filename, files, salutation, first_name, last_name, company, email',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, uuid, filename, files, salutation, first_name, last_name, company, email, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages'
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_azgrdownloadcenter_domain_model_downloads',
                'foreign_table_where' => 'AND tx_azgrdownloadcenter_domain_model_downloads.pid=###CURRENT_PID### AND tx_azgrdownloadcenter_domain_model_downloads.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],

	    'uuid' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.uuid',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	        
	    ],
	    'filename' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.filename',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	        
	    ],
	    'files' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.files',
	        'config' => 
	        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
			    'images',
			    [
			        'appearance' => [
			            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
			        ],
			        'foreign_types' => [
			            '0' => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ],
			            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
			                'showitem' => '
			                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			                --palette--;;filePalette'
			            ]
			        ],
			        'maxitems' => 99
			    ],
			    $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
			),
	        
	    ],
	    'salutation' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.salutation',
	        'config' => [
			    'type' => 'radio',
			    'items' => [
				    [
					    'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.salutation.I.0',
					    0
				    ],
				    [
					    'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.salutation.I.1',
					    1
				    ]
			    ]
			],
	        
	    ],
	    'first_name' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.firstName',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	        
	    ],
	    'last_name' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.lastName',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	        
	    ],
	    'company' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.company',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	        
	    ],
	    'email' => [
	        'exclude' => 1,
	        'label' => 'LLL:EXT:azgr_downloadcenter/Resources/Private/Language/locallang_db.xlf:tx_azgrdownloadcenter_domain_model_downloads.email',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	        
	    ],
        
    ],
];
