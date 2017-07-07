/**
 * External dependencies
 */
import { last, take, clone, uniq, map, difference, each, identity, some, throttle } from 'lodash';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __, _n, sprintf } from 'i18n';
import { Component } from 'element';

/**
 * Internal dependencies
 */
import './style.scss';
import Token from './token';
import TokenInput from './token-input';
import SuggestionsList from './suggestions-list';
import withInstanceId from '../higher-order/with-instance-id';

const initialState = {
	incompleteTokenValue: '',
	inputOffsetFromEnd: 0,
	isActive: false,
	selectedSuggestionIndex: -1,
	selectedSuggestionScroll: false,
};

class FormTokenField extends Component {
	constructor() {
		super( ...arguments );
		this.state = initialState;
		this.onKeyDown = this.onKeyDown.bind( this );
		this.onKeyPress = this.onKeyPress.bind( this );
		this.onFocus = this.onFocus.bind( this );
		this.onBlur = this.onBlur.bind( this );
		this.deleteTokenBeforeInput = this.deleteTokenBeforeInput.bind( this );
		this.deleteTokenAfterInput = this.deleteTokenAfterInput.bind( this );
		this.addCurrentToken = this.addCurrentToken.bind( this );
		this.onContainerTouched = this.onContainerTouched.bind( this );
		this.renderToken = this.renderToken.bind( this );
		this.onTokenClickRemove = this.onTokenClickRemove.bind( this );
		this.onSuggestionHovered = this.onSuggestionHovered.bind( this );
		this.onSuggestionSelected = this.onSuggestionSelected.bind( this );
		this.onInputChange = this.onInputChange.bind( this );
		this.bindInput = this.bindInput.bind( this );
		this.bindTokensAndInput = this.bindTokensAndInput.bind( this );
		this.throlltedSpeak = throttle( this.speak.bind( this ), 1000 );
	}

	componentDidUpdate() {
		if ( this.state.isActive && ! this.input.hasFocus() ) {
			this.input.focus(); // make sure focus is on input
		}
	}

	componentWillReceiveProps( nextProps ) {
		if ( nextProps.disabled && this.state.isActive ) {
			this.setState( {
				isActive: false,
				incompleteTokenValue: '',
			} );
		}
	}

	bindInput( ref ) {
		this.input = ref;
	}

	bindTokensAndInput( ref ) {
		this.tokensAndInput = ref;
	}

	onFocus( event ) {
		this.setState( { isActive: true } );
		if ( 'function' === typeof this.props.onFocus ) {
			this.props.onFocus( event );
		}
	}

	onBlur() {
		if ( this.inputHasValidValue() ) {
			this.setState( { isActive: false }, this.addCurrentToken );
		} else {
			this.setState( initialState );
		}
	}

	onKeyDown( event ) {
		let preventDefault = false;

		switch ( event.keyCode ) {
			case 8: // backspace (delete to left)
				preventDefault = this.handleDeleteKey( this.deleteTokenBeforeInput );
				break;
			case 9: // tab
				preventDefault = this.addCurrentToken();
				break;
			case 13: // enter/return
				preventDefault = this.addCurrentToken();
				break;
			case 37: // left arrow
				preventDefault = this.handleLeftArrowKey();
				break;
			case 38: // up arrow
				preventDefault = this.handleUpArrowKey();
				break;
			case 39: // right arrow
				preventDefault = this.handleRightArrowKey();
				break;
			case 40: // down arrow
				preventDefault = this.handleDownArrowKey();
				break;
			case 46: // delete (to right)
				preventDefault = this.handleDeleteKey( this.deleteTokenAfterInput );
				break;
			case 32: // space
				if ( this.props.tokenizeOnSpace ) {
					preventDefault = this.addCurrentToken();
				}
				break;
			default:
				break;
		}

		if ( preventDefault ) {
			event.preventDefault();
		}
	}

	onKeyPress( event ) {
		let preventDefault = false;
		switch ( event.charCode ) {
			case 44: // comma
				preventDefault = this.handleCommaKey();
				break;
			default:
				break;
		}

		if ( preventDefault ) {
			event.preventDefault();
		}
	}

	onContainerTouched( event ) {
		// Prevent clicking/touching the tokensAndInput container from blurring
		// the input and adding the current token.
		if ( event.target === this.tokensAndInput && this.state.isActive ) {
			event.preventDefault();
		}
	}

	onTokenClickRemove( event ) {
		this.deleteToken( event.value );
	}

	onSuggestionHovered( suggestion ) {
		const index = this.getMatchingSuggestions().indexOf( suggestion );

		if ( index >= 0 ) {
			this.setState( {
				selectedSuggestionIndex: index,
				selectedSuggestionScroll: false,
			} );
		}
	}

	onSuggestionSelected( suggestion ) {
		this.addNewToken( suggestion );
	}

	onInputChange( event ) {
		const text = event.value;
		const separator = this.props.tokenizeOnSpace ? /[ ,\t]+/ : /[,\t]+/;
		const items = text.split( separator );
		const tokenValue = last( items ) || '';

		if ( items.length > 1 ) {
			this.addNewTokens( items.slice( 0, -1 ) );
		}

		this.setState( {
			incompleteTokenValue: tokenValue,
			selectedSuggestionIndex: -1,
			selectedSuggestionScroll: false,
		} );

		this.props.onInputChange( tokenValue );

		const showMessage = tokenValue.trim().length > 1;
		if ( showMessage ) {
			const matchingSuggestions = this.getMatchingSuggestions( tokenValue );
			if ( !! matchingSuggestions.length ) {
				this.throlltedSpeak( sprintf( _n(
					'%d result found, use up and down arrow keys to navigate.',
					'%d results found, use up and down arrow keys to navigate.',
					matchingSuggestions.length
				), matchingSuggestions.length ) );
			} else {
				this.throlltedSpeak( __( 'No results.' ) );
			}
		}
	}

	handleDeleteKey( deleteToken ) {
		let preventDefault = false;
		if ( this.input.hasFocus() && this.isInputEmpty() ) {
			deleteToken();
			preventDefault = true;
		}

		return preventDefault;
	}

	handleLeftArrowKey() {
		let preventDefault = false;
		if ( this.isInputEmpty() ) {
			this.moveInputBeforePreviousToken();
			preventDefault = true;
		}

		return preventDefault;
	}

	handleRightArrowKey() {
		let preventDefault = false;
		if ( this.isInputEmpty() ) {
			this.moveInputAfterNextToken();
			preventDefault = true;
		}

		return preventDefault;
	}

	handleUpArrowKey() {
		this.setState( ( state ) => ( {
			selectedSuggestionIndex: Math.max( ( state.selectedSuggestionIndex || 0 ) - 1, 0 ),
			selectedSuggestionScroll: true,
		} ) );

		return true; // preventDefault
	}

	handleDownArrowKey() {
		this.setState( ( state, props ) => ( {
			selectedSuggestionIndex: Math.min(
				( state.selectedSuggestionIndex + 1 ) || 0,
				this.getMatchingSuggestions(
					state.incompleteTokenValue,
					props.suggestions,
					props.value,
					props.maxSuggestions,
					props.saveTransform
				).length - 1
			),
			selectedSuggestionScroll: true,
		} ) );

		return true; // preventDefault
	}

	handleCommaKey() {
		if ( this.inputHasValidValue() ) {
			this.addNewToken( this.state.incompleteTokenValue );
		}

		return true; // preventDefault
	}

	moveInputToIndex( index ) {
		this.setState( ( state, props ) => ( {
			inputOffsetFromEnd: props.value.length - Math.max( index, -1 ) - 1,
		} ) );
	}

	moveInputBeforePreviousToken() {
		this.setState( ( state, props ) => ( {
			inputOffsetFromEnd: Math.min( state.inputOffsetFromEnd + 1, props.value.length ),
		} ) );
	}

	moveInputAfterNextToken() {
		this.setState( ( state ) => ( {
			inputOffsetFromEnd: Math.max( state.inputOffsetFromEnd - 1, 0 ),
		} ) );
	}

	deleteTokenBeforeInput() {
		const index = this.getIndexOfInput() - 1;

		if ( index > -1 ) {
			this.deleteToken( this.props.value[ index ] );
		}
	}

	deleteTokenAfterInput() {
		const index = this.getIndexOfInput();

		if ( index < this.props.value.length ) {
			this.deleteToken( this.props.value[ index ] );
			// update input offset since it's the offset from the last token
			this.moveInputToIndex( index );
		}
	}

	addCurrentToken() {
		let preventDefault = false;
		const selectedSuggestion = this.getSelectedSuggestion();

		if ( selectedSuggestion ) {
			this.addNewToken( selectedSuggestion );
			preventDefault = true;
		} else if ( this.inputHasValidValue() ) {
			this.addNewToken( this.state.incompleteTokenValue );
			preventDefault = true;
		}

		return preventDefault;
	}

	addNewTokens( tokens ) {
		const tokensToAdd = uniq(
			tokens
				.map( this.props.saveTransform )
				.filter( Boolean )
				.filter( token => ! this.valueContainsToken( token ) )
		);

		if ( tokensToAdd.length > 0 ) {
			const newValue = clone( this.props.value );
			newValue.splice.apply(
				newValue,
				[ this.getIndexOfInput(), 0 ].concat( tokensToAdd )
			);
			this.props.onChange( newValue );
		}
	}

	addNewToken( token ) {
		this.addNewTokens( [ token ] );
		this.speak( this.props.messages.added );

		this.setState( {
			incompleteTokenValue: '',
			selectedSuggestionIndex: -1,
			selectedSuggestionScroll: false,
		} );

		if ( this.state.isActive ) {
			this.input.focus();
		}
	}

	deleteToken( token ) {
		const newTokens = this.props.value.filter( ( item ) => {
			return this.getTokenValue( item ) !== this.getTokenValue( token );
		} );
		this.props.onChange( newTokens );
		this.speak( this.props.messages.removed );
	}

	getTokenValue( token ) {
		if ( 'object' === typeof token ) {
			return token.value;
		}

		return token;
	}

	getMatchingSuggestions(
		searchValue = this.state.incompleteTokenValue,
		suggestions = this.props.suggestions,
		value = this.props.value,
		maxSuggestions = this.props.maxSuggestions,
		saveTransform = this.props.saveTransform,
	) {
		let match = saveTransform( searchValue );
		const startsWithMatch = [];
		const containsMatch = [];

		if ( match.length === 0 ) {
			suggestions = difference( suggestions, value );
		} else {
			match = match.toLocaleLowerCase();

			each( suggestions, ( suggestion ) => {
				const index = suggestion.toLocaleLowerCase().indexOf( match );
				if ( value.indexOf( suggestion ) === -1 ) {
					if ( index === 0 ) {
						startsWithMatch.push( suggestion );
					} else if ( index > 0 ) {
						containsMatch.push( suggestion );
					}
				}
			} );

			suggestions = startsWithMatch.concat( containsMatch );
		}

		return take( suggestions, maxSuggestions );
	}

	speak( message ) {
		wp.a11y.speak( message, 'assertive' );
	}

	getSelectedSuggestion() {
		if ( this.state.selectedSuggestionIndex !== -1 ) {
			return this.getMatchingSuggestions()[ this.state.selectedSuggestionIndex ];
		}
	}

	valueContainsToken( token ) {
		return some( this.props.value, ( item ) => {
			return this.getTokenValue( token ) === this.getTokenValue( item );
		} );
	}

	getIndexOfInput() {
		return this.props.value.length - this.state.inputOffsetFromEnd;
	}

	isInputEmpty() {
		return this.state.incompleteTokenValue.length === 0;
	}

	inputHasValidValue() {
		return this.props.saveTransform( this.state.incompleteTokenValue ).length > 0;
	}

	renderTokensAndInput() {
		const components = map( this.props.value, this.renderToken );
		components.splice( this.getIndexOfInput(), 0, this.renderInput() );

		return components;
	}

	renderToken( token ) {
		const value = this.getTokenValue( token );
		const status = token.status ? token.status : undefined;

		return (
			<Token
				key={ 'token-' + value }
				value={ value }
				status={ status }
				title={ token.title }
				displayTransform={ this.props.displayTransform }
				onClickRemove={ this.onTokenClickRemove }
				isBorderless={ token.isBorderless || this.props.isBorderless }
				onMouseEnter={ token.onMouseEnter }
				onMouseLeave={ token.onMouseLeave }
				disabled={ 'error' !== status && this.props.disabled }
				messages={ this.props.messages }
			/>
		);
	}

	renderInput() {
		const { autoCapitalize, autoComplete, maxLength, value, placeholder, instanceId } = this.props;

		let props = {
			instanceId,
			autoCapitalize,
			autoComplete,
			ref: this.bindInput,
			key: 'input',
			disabled: this.props.disabled,
			value: this.state.incompleteTokenValue,
			onBlur: this.onBlur,
			isExpanded: this.state.isActive,
			selectedSuggestionIndex: this.state.selectedSuggestionIndex,
		};

		if ( value.length === 0 && placeholder ) {
			props.placeholder = placeholder;
		}

		if ( ! ( maxLength && value.length >= maxLength ) ) {
			props = { ...props, onChange: this.onInputChange };
		}

		return (
			<TokenInput { ...props } />
		);
	}

	render() {
		const {
			disabled,
			placeholder = _( 'Add item.' ),
			instanceId,
		} = this.props;
		const classes = classnames( 'components-form-token-field', {
			'is-active': this.state.isActive,
			'is-disabled': disabled,
		} );

		let tokenFieldProps = {
			ref: 'main',
			className: classes,
			tabIndex: '-1',
		};
		const matchingSuggestions = this.getMatchingSuggestions();
		const showSuggestions = this.state.incompleteTokenValue.trim().length > 1;

		if ( ! disabled ) {
			tokenFieldProps = Object.assign( {}, tokenFieldProps, {
				onKeyDown: this.onKeyDown,
				onKeyPress: this.onKeyPress,
				onFocus: this.onFocus,
			} );
		}

		return (
			<div { ...tokenFieldProps } >
				<label htmlFor={ `components-form-token-input-${ instanceId }` } className="screen-reader-text">
					{ placeholder }
				</label>
				<div ref={ this.bindTokensAndInput }
					className="components-form-token-field__input-container"
					tabIndex="-1"
					onMouseDown={ this.onContainerTouched }
					onTouchStart={ this.onContainerTouched }
				>
					{ this.renderTokensAndInput() }
				</div>

				{ showSuggestions && (
					<SuggestionsList
						instanceId={ instanceId }
						match={ this.props.saveTransform( this.state.incompleteTokenValue ) }
						displayTransform={ this.props.displayTransform }
						suggestions={ matchingSuggestions }
						selectedIndex={ this.state.selectedSuggestionIndex }
						scrollIntoView={ this.state.selectedSuggestionScroll }
						isExpanded={ this.state.isActive }
						onHover={ this.onSuggestionHovered }
						onSelect={ this.onSuggestionSelected }
					/>
				) }
				<div id={ `components-form-token-suggestions-howto-${ instanceId }` } className="screen-reader-text">
					{ __( 'Separate with commas' ) }
				</div>
			</div>
		);
	}
}

FormTokenField.defaultProps = {
	suggestions: Object.freeze( [] ),
	maxSuggestions: 100,
	value: Object.freeze( [] ),
	placeholder: '',
	displayTransform: identity,
	saveTransform: ( token ) => token.trim(),
	onChange: () => {},
	onInputChange: () => {},
	isBorderless: false,
	disabled: false,
	tokenizeOnSpace: false,
	messages: {
		added: __( 'Item added.' ),
		removed: __( 'Item removed.' ),
		remove: __( 'Remove item: %s.' ),
	},
};

export default withInstanceId( FormTokenField );
