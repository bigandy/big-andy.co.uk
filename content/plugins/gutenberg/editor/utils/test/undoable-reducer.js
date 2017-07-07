/**
 * External dependencies
 */
import { expect } from 'chai';

/**
 * Internal dependencies
 */
import { undoable, combineUndoableReducers } from '../undoable-reducer';

describe( 'undoableReducer', () => {
	describe( 'undoable()', () => {
		const counter = ( state = 0, { type } ) => (
			type === 'INCREMENT' ? state + 1 : state
		);

		it( 'should return a new reducer', () => {
			const reducer = undoable( counter );

			expect( reducer ).to.be.a( 'function' );
			expect( reducer( undefined, {} ) ).to.eql( {
				past: [],
				present: 0,
				future: [],
			} );
		} );

		it( 'should track history', () => {
			const reducer = undoable( counter );

			let state;
			state = reducer( undefined, {} );
			state = reducer( state, { type: 'INCREMENT' } );

			expect( state ).to.eql( {
				past: [ 0 ],
				present: 1,
				future: [],
			} );
		} );

		it( 'should perform undo', () => {
			const reducer = undoable( counter );

			let state;
			state = reducer( undefined, {} );
			state = reducer( state, { type: 'INCREMENT' } );
			state = reducer( state, { type: 'UNDO' } );

			expect( state ).to.eql( {
				past: [],
				present: 0,
				future: [ 1 ],
			} );
		} );

		it( 'should perform redo', () => {
			const reducer = undoable( counter );

			let state;
			state = reducer( undefined, {} );
			state = reducer( state, { type: 'INCREMENT' } );
			state = reducer( state, { type: 'UNDO' } );
			state = reducer( state, { type: 'REDO' } );

			expect( state ).to.eql( {
				past: [ 0 ],
				present: 1,
				future: [],
			} );
		} );

		it( 'should reset history by options.resetTypes', () => {
			const reducer = undoable( counter, { resetTypes: [ 'RESET_HISTORY' ] } );

			let state;
			state = reducer( undefined, {} );
			state = reducer( state, { type: 'INCREMENT' } );
			state = reducer( state, { type: 'RESET_HISTORY' } );
			state = reducer( state, { type: 'INCREMENT' } );
			state = reducer( state, { type: 'INCREMENT' } );

			expect( state ).to.eql( {
				past: [ 1, 2 ],
				present: 3,
				future: [],
			} );
		} );
	} );

	describe( 'combineUndoableReducers()', () => {
		it( 'should return a combined reducer with getters', () => {
			const reducer = combineUndoableReducers( {
				count: ( state = 0 ) => state,
			} );
			const state = reducer( undefined, {} );

			expect( reducer ).to.be.a( 'function' );
			expect( state ).to.have.keys( 'history' );
			expect( state.count ).to.equal( 0 );
			expect( state.history ).to.eql( {
				past: [],
				present: {
					count: 0,
				},
				future: [],
			} );
		} );
	} );
} );
