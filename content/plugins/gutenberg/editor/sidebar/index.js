/**
 * External dependencies
 */
import { connect } from 'react-redux';

/**
 * WordPress Dependencies
 */
import { withFocusReturn } from 'components';

/**
 * Internal Dependencies
 */
import './style.scss';
import PostSettings from './post-settings';
import BlockInspector from './block-inspector';
import { getSelectedBlock } from '../selectors';

const Sidebar = ( { selectedBlock } ) => {
	return (
		<div className="editor-sidebar">
			{ ! selectedBlock && <PostSettings /> }
			{ selectedBlock && <BlockInspector /> }
		</div>
	);
};

export default connect(
	( state ) => {
		return {
			selectedBlock: getSelectedBlock( state ),
		};
	}
)( withFocusReturn( Sidebar ) );
