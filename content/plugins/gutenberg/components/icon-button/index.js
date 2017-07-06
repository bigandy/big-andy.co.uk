/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Internal dependencies
 */
import './style.scss';
import Button from '../button';
import Dashicon from '../dashicon';

function IconButton( { icon, children, label, className, focus, ...additionalProps } ) {
	const classes = classnames( 'components-icon-button', className );

	return (
		<Button { ...additionalProps } aria-label={ label } className={ classes } focus={ focus }>
			<Dashicon icon={ icon } />
			{ children }
		</Button>
	);
}

export default IconButton;
