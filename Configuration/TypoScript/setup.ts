
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
	autoform = EXT:azgr_downloadcenter/Resources/Public/Javascript/jquery.autoform.min.js
	#autoform = http://localhost/_schuetz/jquery.autoform.js
	#autoform.external = 1
	downloadcenter = EXT:azgr_downloadcenter/Resources/Public/Javascript/Downloadcenter.js
}

plugin.tx_azgrdownloadcenter_zip {
	settings {
	    filemount = {$plugin.tx_azgrdownloadcenter_zip.settings.filemount}
	    uploadDir = {$plugin.tx_azgrdownloadcenter_zip.settings.uploadDir}
	    zipPrefix = {$plugin.tx_azgrdownloadcenter_zip.settings.zipPrefix}
	    zipDir = {$plugin.tx_azgrdownloadcenter_zip.settings.zipDir}
	    filesDefault = {$plugin.tx_azgrdownloadcenter_zip.settings.filesDefault}
	  }
}

zip = PAGE
zip {
	config {
		disableAllHeaderCode = 1
		additionalHeaders = Content-type:application/json
		debug = 0
		no_cache = 1
	}
	typeNum = 65478
	10 < tt_content.list.20.azgrdownloadcenter_zip
}