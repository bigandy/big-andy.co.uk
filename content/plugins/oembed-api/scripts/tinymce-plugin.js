( function( tinymce ) {
	tinymce.PluginManager.add( 'autoembed', function( editor, url ) {
		editor.on( 'init', function() {
			var scriptId = editor.dom.uniqueId();

			var scriptElm = editor.dom.create( 'script', {
				id: scriptId,
				type: 'text/javascript',
				src: url + '/frontend.js'
			} );

			editor.getDoc().getElementsByTagName( 'head' )[0].appendChild( scriptElm );
		} );
	} );
} )( window.tinymce );
