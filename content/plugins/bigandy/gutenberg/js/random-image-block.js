( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;

	var blockStyle = {
		backgroundColor: '#900',
		color: '#fff',
		padding: '20px'
	};

	blocks.registerBlockType( 'bigandy/ah-random-image-block', {
		title: 'Random Image',
		icon: 'universal-access-alt',
		category: 'common',
		edit: function() {
			return el(
				'p',
				{ style: blockStyle },
				'RI (from the editor).'
			);
		},
		save: function() {
			return el(
				'p',
				{ style: blockStyle },
				'RI (from the frontend).'
			);
		},
	} );
} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);
