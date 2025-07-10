/*

Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



CKEDITOR.editorConfig = function( config )

{

	// Define changes to default configuration here. For example:

	// config.language = 'fr';

	// config.uiColor = '#AADC6E';
	config.allowedContent = true;
	config.extraAllowedContent = 'div(*)';
	config.extraAllowedContent = 'div(col-md-*,container-fluid,row)';
	config.extraAllowedContent = 'dl; dt dd[dir]';

};

