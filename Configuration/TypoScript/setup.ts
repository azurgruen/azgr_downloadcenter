config.disableCharsetHeader = 1

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
    zip {
    	prefix = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.zip.prefix}
	    dir = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.zip.dir}
	    defaultFiles = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.zip.defaultFiles}
	    ttl = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.zip.ttl}
	}
	mail {
		senderName = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.mail.senderName}
		senderAddress = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.mail.senderAddress}
		subject = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.mail.subject}
	}
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
	jquery = https://code.jquery.com/jquery-3.2.1.min.js
	jquery {
		external = 1
		excludeFromConcatenation = 1
		disableCompression = 1
		if.isTrue = {$plugin.tx_azgrdownloadcenter_downloadcenter.settings.includejQuery}
	}
	autoform = EXT:azgr_downloadcenter/Resources/Public/Javascript/jquery.autoform.min.js
	downloadcenter = EXT:azgr_downloadcenter/Resources/Public/Javascript/Downloadcenter.js
}

