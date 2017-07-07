/**
 * External dependencies
 */
import { expect } from 'chai';
import { transform } from 'babel-core';
import traverse from 'babel-traverse';

/**
 * Internal dependencies
 */
import babelPlugin from '../babel-plugin';

describe( 'babel-plugin', () => {
	const {
		isValidTranslationKey,
		isSameTranslation,
		getTranslatorComment,
	} = babelPlugin;

	describe( '.isValidTranslationKey()', () => {
		it( 'should return false if not one of valid keys', () => {
			expect( isValidTranslationKey( 'foo' ) ).to.be.false();
		} );

		it( 'should return true if one of valid keys', () => {
			expect( isValidTranslationKey( 'msgid' ) ).to.be.true();
		} );
	} );

	describe( '.isSameTranslation()', () => {
		it( 'should return false if any translation keys differ', () => {
			const a = { msgid: 'foo' };
			const b = { msgid: 'bar' };

			expect( isSameTranslation( a, b ) ).to.be.false();
		} );

		it( 'should return true if all translation keys the same', () => {
			const a = { msgid: 'foo', comments: { reference: 'a' } };
			const b = { msgid: 'foo', comments: { reference: 'b' } };

			expect( isSameTranslation( a, b ) ).to.be.true();
		} );
	} );

	describe( '.getTranslatorComment()', () => {
		function getCommentFromString( string ) {
			let comment;
			traverse( transform( string ).ast, {
				CallExpression( path ) {
					comment = getTranslatorComment( path );
				},
			} );

			return comment;
		}

		it( 'should not return translator comment on same line but after call expression', () => {
			const comment = getCommentFromString( '__( \'Hello world\' ); // translators: Greeting' );

			expect( comment ).to.be.undefined();
		} );

		it( 'should return translator comment on leading comments', () => {
			const comment = getCommentFromString( '// translators: Greeting\n__( \'Hello world\' );' );

			expect( comment ).to.equal( 'Greeting' );
		} );

		it( 'should be case insensitive to translator prefix', () => {
			const comment = getCommentFromString( '// TrANslAtORs: Greeting\n__( \'Hello world\' );' );

			expect( comment ).to.equal( 'Greeting' );
		} );

		it( 'should traverse up parents until it encounters comment', () => {
			const comment = getCommentFromString( '// translators: Greeting\nconst string = __( \'Hello world\' );' );

			expect( comment ).to.equal( 'Greeting' );
		} );

		it( 'should not consider comment if it does not end on same or previous line', () => {
			const comment = getCommentFromString( '// translators: Greeting\n\n__( \'Hello world\' );' );

			expect( comment ).to.be.undefined();
		} );

		it( 'should use multi-line comment starting many lines previous', () => {
			const comment = getCommentFromString( '/* translators: Long comment\nspanning multiple \nlines */\nconst string = __( \'Hello world\' );' );

			expect( comment ).to.equal( 'Long comment spanning multiple lines' );
		} );
	} );
} );
