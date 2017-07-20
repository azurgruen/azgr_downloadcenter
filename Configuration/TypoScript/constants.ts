# customsubcategory=general=Allgemein
# customsubcategory=filesettings=Dateisystem
# customsubcategory=mail=E-Mail

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
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/general/a; type=int+; label=Default storage PID
    storagePid =
  }
  settings {
	# cat=plugin.tx_azgrdownloadcenter_downloadcenter//a; type=int+; label=New until (days)
    newUntil = 1
	# cat=plugin.tx_azgrdownloadcenter_downloadcenter/general/a; type=boolean; label=Include jQuery
    includejQuery = 0
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/filesettings/a; type=string; label=Filemount
    filemount = fileadmin/
	# cat=plugin.tx_azgrdownloadcenter_downloadcenter/filesettings/a; type=string; label=Upload directory
    uploadDir = zip/
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/filesettings/a; type=string; label=Zip file prefix
    zip.prefix = downloads-
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/filesettings/a; type=string; label=Zip directory
    zip.dir = Downloads
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/filesettings/a; type=string; label=Default files (comma-separated id)
    zip.defaultFiles = 
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/filesettings/a; type=int+; label=Lifespan of files in days
    zip.ttl = 7
    
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/mail/a; type=string; label=Sender name
    mail.senderName = Webmaster
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/mail/a; type=string; label=Sender address
    mail.senderAddress = webmaster@azurgruen.de
    # cat=plugin.tx_azgrdownloadcenter_downloadcenter/mail/a; type=string; label=Subject
    mail.subject = Your download is ready
  }
}
