/**
 * External dependencies
 */
import classnames from 'classnames';
import { noop } from 'lodash';

/**
 * WordPress dependencies
 */
import { __ } from 'i18n';

/**
 * Internal dependencies
 */
import './style.scss';

function FormToggle( { className, checked, id, onChange = noop, showHint = true } ) {
	const wrapperClasses = classnames(
		'components-form-toggle',
		className,
		{ 'is-checked': checked }
	);

	return (
		<span className={ wrapperClasses }>
			<input
				className="components-form-toggle__input"
				id={ id }
				type="checkbox"
				checked={ checked }
				onChange={ onChange }
			/>
			{ showHint &&
				<span className="components-form-toggle__hint" aria-hidden>
					{ checked ? __( 'On' ) : __( 'Off' ) }
				</span>
			}
		</span>
	);
}

export default FormToggle;
