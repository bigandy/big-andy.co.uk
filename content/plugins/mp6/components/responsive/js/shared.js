;( function( $, window, document, undefined ) {

	'use strict';

	var moby6 = {
		init: function() {
			var _self = this;

			// cached selectors
			this.$wpbody = $( '#wpbody' );
			this.$adminmenu = $( '#adminmenu' );

			// Modify functionality based on custom activate/deactivate event
			$(document.documentElement)
				.on( 'activate.moby6', function() { _self.activate(); } )
				.on( 'deactivate.moby6', function() { _self.deactivate(); } );

			// Remove browser chrome
			window.scrollTo( 0, 1 );

			// Trigger custom events based on active media query.
			this.matchMedia();
			$(window).on( 'resize', $.proxy( this.matchMedia, this ) );

			// workaround to resize the PressThis window back to a width of 770
			// the 'shortcut_link' filter isn't enought, because press-this.php
			// calls window.resizeTo() on $(document).ready()
			if ( $(document.body).hasClass( 'press-this' ) ) {
				setTimeout( function() { window.resizeTo(770, 580); }, 100 );
			}
		},

		activate: function() {
			this.modifySidebarEvents();
			this.disableDraggables();
			this.insertHamburgerButton();
			this.movePostSearch();
		},

		deactivate: function() {
			this.enableDraggables();
			this.removeHamburgerButton();
			this.restorePostSearch();
		},

		matchMedia: function() {
			clearTimeout( this.resizeTimeout );
			this.resizeTimeout = setTimeout( function() {
				var $html = $(document.documentElement);

				if ( window.matchMedia( '(max-width: 768px)' ).matches ) {
					if ( $html.hasClass( 'touch' ) )
						return;

					$html.addClass( 'touch' ).trigger( 'activate.moby6' );
					window.stickymenu && stickymenu.disable();
				} else {
					if ( ! $html.hasClass( 'touch' ) )
						return;

					$html.removeClass( 'touch' ).trigger( 'deactivate.moby6' );
					window.stickymenu && stickymenu.enable();
				}
			}, 150 );
		},

		modifySidebarEvents: function() {
			$(document.body).off( '.wp-mobile-hover' );
			this.$adminmenu.find( 'a.wp-has-submenu' ).off( '.wp-mobile-hover' );

			var scrollStart = 0;
			this.$adminmenu.on( 'touchstart.moby6', 'li.wp-has-submenu > a', function() {
				scrollStart = $(window).scrollTop();
			});

			this.$adminmenu.on( 'touchend.moby6', 'li.wp-has-submenu > a', function(e) {
				e.preventDefault();

				if ( $(window).scrollTop() !== scrollStart )
					return false;

				$(this).find( 'li.wp-has-submenu' ).removeClass( 'selected' );
				$(this).parent( 'li' ).addClass( 'selected' );
			});
		},

		disableDraggables: function() {
			this.$wpbody
				.find( '.hndle' )
				.removeClass( 'hndle' )
				.addClass( 'hndle-disabled' );
		},

		enableDraggables: function() {
			this.$wpbody
				.find( '.hndle-disabled' )
				.removeClass( 'hndle-disabled' )
				.addClass( 'hndle' );
		},

		insertHamburgerButton: function() {
			this.$wpbody
				.find( '.wrap' )
				.prepend( '<div id="moby6-toggle"></div>' );

			this.hamburgerButtonView = new Moby6HamburgerButton( { el: $( '#moby6-toggle' ) } );
		},

		removeHamburgerButton: function() {
			this.hamburgerButtonView.destroy();
		},

		movePostSearch: function() {
			this.searchBox = this.$wpbody.find( 'p.search-box' );
			if ( this.searchBox.length ) {
				this.searchBox.hide();
				if ( this.searchBoxClone === undefined ) {
					this.searchBoxClone = this.searchBox.first().clone().insertAfter( 'div.tablenav.bottom' );
				}
				this.searchBoxClone.show();
			}
		},

		restorePostSearch: function() {
			if ( this.searchBox !== undefined ) {
				this.searchBox.show();
				this.searchBoxClone.hide();
			}
		}
	}

	$(document).ready( $.proxy( moby6.init, moby6 ) );

	/* Hamburger button view */
	var Moby6HamburgerButton = Backbone.View.extend({
		events: {
			'click': 'toggleSidebar'
		},

		initialize: function() {
			this.$wpwrap = $( '#wpwrap' );
			this.render();
		},

		render: function() {
			// Needs to be in a translatable template.
			this.$el.html( '<a href="#" title="Menu"></a>' );
			return this;
		},

		toggleSidebar: function(e) {
			e.preventDefault();
			this.$wpwrap.toggleClass( 'moby6-open' );
		},

		destroy: function() {
			this.undelegateEvents();
			this.$el.removeData().unbind();
			this.remove();
			Backbone.View.prototype.remove.call(this);
		}
	});

})( jQuery, window, document );