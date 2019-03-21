﻿/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	
	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.

	/* config.plugins = 'dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,div,resize,toolbar,elementspath,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,undo,wsc,youtube'; */

	config.plugins = 
	'dialogui,dialog,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,menu,contextmenu,resize,toolbar,elementspath,entities,popup,filebrowser,floatingspace,listblock,richcombo,font,format,htmlwriter,wysiwygarea,indent,indentblock,indentlist,justify,menubutton,link,list,liststyle,pastetext,pastefromword,removeformat,showblocks,showborders,scayt,stylescombo,tab,undo,wsc';

	config.skin = 'bootstrapck';
	// %REMOVE_END%

	// Define changes to default configuration here. For example:
  config.language = 'pt';
	// config.uiColor = '#AADC6E';
};