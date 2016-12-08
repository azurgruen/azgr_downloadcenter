
plugin.tx_azgrdownloadcenter_downloadcenter {
  view {
    templateRootPaths.0 = EXT:azgr_downloadcenter/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_azgrdownloadcenter_downloadcenter.view.templateRootPath}
    partialRootPaths.0 = EXT:azgr_downloadcenter/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_azgrdownloadcenter_downloadcenter.view.partialRootPath}
    layoutRootPaths.0 = EXT:azgr_downloadcenter/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_azgrdownloadcenter_downloadcenter.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_azgrdownloadcenter_downloadcenter.persistence.storagePid}
    #recursive = 1
  }
  settings {
	newUntil = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.newUntil}
	includejQuery = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.includejQuery}
	filemount = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.filemount}
    uploadDir = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.uploadDir}
    zipPrefix = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.zipPrefix}
    zipDir = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.zipDir}
    filesDefault = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.filesDefault}
    ttl = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.ttl}
  }
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
  _LOCAL_LANG {
		default {
			# read_more = more >>
		}
	}
}

page.includeCSS.downloadcenter = EXT:azgr_downloadcenter/Resources/Public/Stylesheets/Downloadcenter.css
page.includeJS {
	jquery = https://code.jquery.com/jquery-3.1.1.min.js
	jquery {
		external = 1
		excludeFromConcatenation = 1
		disableCompression = 1
		if.isTrue = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.includejQuery}
	}
	autoform = EXT:azgr_downloadcenter/Resources/Public/Javascript/jquery.autoform.min.js
	downloadcenter = EXT:azgr_downloadcenter/Resources/Public/Javascript/Downloadcenter.js
}

