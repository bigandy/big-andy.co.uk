const { registerBlockType } = wp.blocks;

const blockStyle = {
	backgroundColor: '#900',
	color: '#fff',
	padding: '20px',
};

registerBlockType( 'bigandy-gutenberg/block', {
	title: 'Bigandy Gutenberg',
	icon: 'universal-access-alt',
	category: 'layout',
	edit() {
		return (
			<div style={ blockStyle }>
				Wonderful things happen to those who <em>wait</em>!
			</div>
		);
	},
	save() {
		
		return (
			<div style={ blockStyle }>
				Hello World!
			</div>
		);
	},
} );
