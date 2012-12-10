copy the templates folder & everything in it into the fileadmin area so that you end up with the following structure:

fieladmin
   - templates
   - main
       - images

then copy the following into the setup field of a new template on the root/home page


#---------------------------------------------------------------------------------------------

#navbar
temp.navigation = HMENU
temp.navigation.1 = TMENU
temp.navigation.1 {
	NO.allWrap = <li> | </li>
	ACT = 1
  	ACT.allWrap = <li class="active"> | </li>
	wrap = <ul> | </ul>
}
#submenu
lib.subMenu = COA
lib.subMenu.2 = HMENU
lib.subMenu.2 {
	special = directory
	#special.value = 8
	special.value.data = leveluid:1
	entryLevel = 1
	expAll = 1
	1 = TMENU
	1 {
		expAll = 1
		NO.allWrap = <li>|</li>| |
		CUR = 1
		CUR.allWrap = <li class="active">|</li> | |
		wrap = <ul> | </ul>
	}
	2 = TMENU
	2 {
		expAll = 1
		NO.allWrap = <li>|</li>| |
		CUR = 1
		CUR.allWrap = <li class="subActive">|</li> | |
		wrap = <ul> | </ul>
	}
}

# Template content object:
temp.mainTemplate = TEMPLATE
temp.mainTemplate {
	template = FILE
	template.file = fileadmin/templates/main/main.html
	workOnSubpart = DOCUMENT_BODY
	subparts.navigation < temp.navigation
	subparts.CONTENT < styles.content.get
	subparts.LEFT_CONTENT < lib.subMenu
}

#Header template
temp.headTemplate = TEMPLATE
temp.headTemplate {
	template = FILE
	# put anything you want to go in the head tags in this file
	template.file = fileadmin/templates/main/header.html
	workOnSubpart = HEADER
}

# Default PAGE object:
page = PAGE

# force HTML5 & correct encoding
config.doctype = <!DOCTYPE html>
config.xmlprologue = none
page.config.metaCharset = utf-8

[browser = msie]&&[version = <9]
page.includeJS {
	file1 = http://html5shiv.googlecode.com/svn/trunk/html5.js
	file1.external = 1
}
[end]

page.typeNum = 0

page.headerData.10  < temp.headTemplate
page.10 < temp.mainTemplate

