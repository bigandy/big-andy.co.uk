/**
 * External dependencies
 */
import { connect } from 'react-redux';

/**
 * WordPress dependencies
 */
import { __ } from 'i18n';
import { Panel, PanelHeader, IconButton } from 'components';

/**
 * Internal Dependencies
 */
import './style.scss';
import PostStatus from '../post-status';
import PostExcerpt from '../post-excerpt';
import PostTaxonomies from '../post-taxonomies';
import FeaturedImage from '../featured-image';
import DiscussionPanel from '../discussion-panel';
import LastRevision from '../last-revision';

const PostSettings = ( { toggleSidebar } ) => {
	return (
		<Panel>
			<PanelHeader label={ __( 'Post Settings' ) } >
				<div className="editor-sidebar-post-settings__icons">
					<IconButton
						icon="admin-settings"
						label={ __( 'WordPress settings' ) }
					/>
					<IconButton
						onClick={ toggleSidebar }
						icon="no-alt"
						label={ __( 'Close post settings sidebar' ) }
					/>
				</div>
			</PanelHeader>
			<PostStatus />
			<LastRevision />
			<PostTaxonomies />
			<FeaturedImage />
			<PostExcerpt />
			<DiscussionPanel />
		</Panel>
	);
};

export default connect(
	undefined,
	( dispatch ) => ( {
		toggleSidebar: () => dispatch( { type: 'TOGGLE_SIDEBAR' } ),
	} )
)( PostSettings );
