
plugin.tx_azgrdownloadcenter_downloadcenter {
  view {
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/file; type=string; label=Path to template root (FE)
    templateRootPath =
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/file; type=string; label=Path to template partials (FE)
    partialRootPath =
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/file; type=string; label=Path to template layouts (FE)
    layoutRootPath =
  }
  persistence {
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter//a; type=string; label=Default storage PID
    #storagePid =
  }
  settings {
	# cat=plugin.tx_azgrdownloadcenter_downloadcenter//a; type=boolean; label=Include jQuery
    includejQuery = 0
  }
}

plugin.tx_azgrdownloadcenter_zip {
	settings {
		# cat=plugin.tx_azgrdownloadcenter_zip/settings; type=string; label=Filemount
	    filemount = fileadmin/
		# cat=plugin.tx_azgrdownloadcenter_zip/settings; type=string; label=Upload directory
	    uploadDir = uploads/zip/
	    # cat=plugin.tx_azgrdownloadcenter_zip/settings; type=string; label=Zip file prefix
	    zipPrefix = downloads-
	    # cat=plugin.tx_azgrdownloadcenter_zip/settings; type=string; label=Zip directory
	    zipDir = Downloads
	    # cat=plugin.tx_azgrdownloadcenter_zip/settings; type=string; label=Always add following files (comma-separated id)
	    filesDefault = 
  }
}
