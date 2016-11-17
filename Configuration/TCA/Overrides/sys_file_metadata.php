<?php
if (!defined('TYPO3_MODE')) {
        die ('Access denied.');
}

$GLOBALS['TCA']['sys_file_metadata']['columns']['description']['defaultExtras'] = 'richtext[]';