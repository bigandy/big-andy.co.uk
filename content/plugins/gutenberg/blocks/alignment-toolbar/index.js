/**
 * WordPress dependencies
 */
import { __ } from 'i18n';
import { Toolbar } from 'components';

const ALIGNMENT_CONTROLS = [
	{
		icon: 'editor-alignleft',
		title: __( 'Align left' ),
		align: 'left',
	},
	{
		icon: 'editor-aligncenter',
		title: __( 'Align center' ),
		align: 'center',
	},
	{
		icon: 'editor-alignright',
		title: __( 'Align right' ),
		align: 'right',
	},
];

export default function AlignmentToolbar( { value, onChange } ) {
	return (
		<Toolbar
			controls={ ALIGNMENT_CONTROLS.map( ( control ) => {
				const { align } = control;
				const isActive = ( value === align );

				return {
					...control,
					isActive,
					onClick: () => onChange( isActive ? null : align ),
				};
			} ) }
		/>
	);
}
