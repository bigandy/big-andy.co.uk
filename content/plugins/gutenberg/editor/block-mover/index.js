/**
 * External dependencies
 */
import { connect } from 'react-redux';
import { first, last } from 'lodash';

/**
 * WordPress dependencies
 */
import { IconButton } from 'components';
import { getBlockType } from 'blocks';

/**
 * Internal dependencies
 */
import './style.scss';
import { isFirstBlock, isLastBlock, getBlockIndex, getBlock } from '../selectors';
import { getBlockMoverLabel } from './mover-label';

function BlockMover( { onMoveUp, onMoveDown, isFirst, isLast, uids, blockType, firstIndex } ) {
	// We emulate a disabled state because forcefully applying the `disabled`
	// attribute on the button while it has focus causes the screen to change
	// to an unfocused state (body as active element) without firing blur on,
	// the rendering parent, leaving it unable to react to focus out.
	return (
		<div className="editor-block-mover">
			<IconButton
				className="editor-block-mover__control"
				onClick={ isFirst ? null : onMoveUp }
				icon="arrow-up-alt2"
				label={ getBlockMoverLabel(
					uids.length,
					blockType && blockType.title,
					firstIndex,
					isFirst,
					isLast,
					-1,
				) }
				aria-disabled={ isFirst }
			/>
			<IconButton
				className="editor-block-mover__control"
				onClick={ isLast ? null : onMoveDown }
				icon="arrow-down-alt2"
				label={ getBlockMoverLabel(
					uids.length,
					blockType && blockType.title,
					firstIndex,
					isFirst,
					isLast,
					1,
				) }
				aria-disabled={ isLast }
			/>
		</div>
	);
}

export default connect(
	( state, ownProps ) => ( {
		isFirst: isFirstBlock( state, first( ownProps.uids ) ),
		isLast: isLastBlock( state, last( ownProps.uids ) ),
		firstIndex: getBlockIndex( state, first( ownProps.uids ) ),
		blockType: getBlockType( getBlock( state, first( ownProps.uids ) ).name ),
	} ),
	( dispatch, ownProps ) => ( {
		onMoveDown() {
			dispatch( {
				type: 'MOVE_BLOCKS_DOWN',
				uids: ownProps.uids,
			} );
		},
		onMoveUp() {
			dispatch( {
				type: 'MOVE_BLOCKS_UP',
				uids: ownProps.uids,
			} );
		},
	} )
)( BlockMover );
