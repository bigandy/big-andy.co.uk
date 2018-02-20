/**
 * BLOCK: ba-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType, Editable } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'cgb/block-ba-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'ba-block - CGB Block' ), // Block title.
	icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'ba-block — CGB Block' ),
		__( 'CGB Example' ),
		__( 'create-guten-block' ),
	],

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	// edit: function( props ) {
	// 	console.log(props);
	// 	return (
	// 		<div className={ props.className }>
	// 			<p>— Hello from the backend.</p>

	// 			<p>
	// 				It was created via { 'Magic' }
	// 			</p>
	// 		</div>
	// 	);
	// },

	attributes: {
        content: {
            type: 'array',
            source: 'children',
            selector: 'h2',
        },
    },

	edit( { className, attributes, setAttributes, focus, setFocus } ) {
        return (
            <Editable
                tagName="h2"
                className={ className }
                value={ attributes.content }
                onChange={ ( content ) => setAttributes( { content } ) }
                focus={ focus }
                setFocus={ setFocus }
            />
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save( { attributes } ) {
		return <div>{ attributes.content }</div>;
	}
	// save( {props, attributes} ) {
	// 	console.log(props, attributes);

	// 	// return (
	// 	// 	<div className={ props.className }>
	// 	// 		<p>{props.author}</p>
	// 	// 		<p>— Hello from the frontend.</p>
	// 	// 		<p>
	// 	// 			CGB BLOCK: <code>ba-block</code> is a new Gutenberg block.
	// 	// 		</p>
	// 	// 		<p>
	// 	// 			It was created via{ ' ' }
	// 	// 			<code>
	// 	// 				<a href="https://github.com/ahmadawais/create-guten-block">
	// 	// 					create-guten-block
	// 	// 				</a>
	// 	// 			</code>.
	// 	// 		</p>
	// 	// 	</div>
	// 	// );
	// },
} );
