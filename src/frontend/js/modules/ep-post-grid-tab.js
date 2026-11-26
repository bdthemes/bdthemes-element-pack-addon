/**
 * Start post grid tab widget script
 */

( function( $, elementor ) {

	'use strict';

	var widgetPostGridTab = function( $scope, $ ) {

		const $postGridTab = $scope.find( '.bdt-post-grid-tab' ),
			gridTab      = $postGridTab.find('.gridtab');

		if ( ! $postGridTab.length ) {
			return;
		}

		// data-settings can come from page markup a Contributor wrote, and gridtab
		// concatenates its numeric, colour and selector options into a <style> tag
		// and into HTML. Rebuild the options from the numeric/boolean keys the
		// widget really emits, so selectors, colours and callbacks always keep the
		// library defaults (same approach as Element Pack Pro's Static Grid Tab).
		const toInt = function ( value ) {
			const n = parseInt( value, 10 );
			return Number.isFinite( n ) ? n : undefined;
		};
		const rawSettings = $postGridTab.data( 'settings' );
		const raw = ( rawSettings && typeof rawSettings === 'object' ) ? rawSettings : {};
		const rawConfig = ( raw.config && typeof raw.config === 'object' ) ? raw.config : {};

		const settings = {
			config: {
				layout: rawConfig.layout === 'tab' ? 'tab' : 'grid',
				activeTab: toInt( rawConfig.activeTab ) || 0,
				showClose: rawConfig.showClose === true
			},
			responsive: null
		};

		// PHP drops zero values, so only copy keys that are present; missing ones
		// fall back to the library defaults exactly as before.
		if ( toInt( raw.grid ) > 0 ) {
			settings.grid = toInt( raw.grid );
		}
		if ( toInt( raw.tabPadding ) !== undefined ) {
			settings.tabPadding = Math.max( 0, toInt( raw.tabPadding ) );
		}
		if ( toInt( raw.borderWidth ) !== undefined ) {
			settings.borderWidth = Math.max( 0, toInt( raw.borderWidth ) );
		}
		if ( Array.isArray( raw.responsive ) ) {
			settings.responsive = raw.responsive.map( function ( item ) {
				const itemSettings = ( item && item.settings && typeof item.settings === 'object' ) ? item.settings : {};
				const itemGrid = toInt( itemSettings.grid );
				// An empty or zero column count keeps falling back to the desktop grid.
				return {
					breakpoint: Math.max( 0, toInt( item && item.breakpoint ) || 0 ),
					settings: itemGrid > 0 ? { grid: itemGrid } : {}
				};
			} );
		}

		gridTab.gridtab( settings );

	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-post-grid-tab.default', widgetPostGridTab );
	});

}( jQuery, window.elementorFrontend ) );

/**
 * End post grid tab widget script
 */

 