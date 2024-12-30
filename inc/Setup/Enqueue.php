<?php

namespace RT\Kariez\Setup;

use RT\Kariez\Helpers\Constants;
use RT\Kariez\Options\Opt;
use RT\Kariez\Traits\SingletonTraits;

/**
 * Enqueue.
 */
class Enqueue {
	use SingletonTraits;

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 12);
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 25 );
	}

	function register_scripts(){
		wp_register_style( 'kariez-gfonts', $this->fonts_url(), [], Constants::get_version() );
		wp_register_style( 'rt-magnific-popup', kariez_get_css( 'magnific-popup', true ), [], Constants::get_version() );
		wp_register_style( 'rt-animate', kariez_get_css( 'animate', true ), [], Constants::get_version() );
		wp_register_style( 'swiper-min', kariez_get_css( 'swiper.min', true ), [], Constants::get_version() );
		wp_register_style( 'rt-animated-headline', kariez_get_css( 'animated-headline', true ), [], Constants::get_version() );

		wp_register_script( 'rt-animated-headline', kariez_get_js( 'animated-headline' ), [ 'jquery' ], Constants::get_version(), true );
		wp_register_script( 'rt-counterup', kariez_get_js( 'counterup' ), [ 'jquery' ], Constants::get_version(), true );
		wp_register_script( 'rt-waypoints', kariez_get_js( 'waypoints' ), [ 'jquery' ], Constants::get_version(), true );
		wp_register_script( 'rt-parallax-scroll', kariez_get_js( 'parallax-scroll' ), [ 'jquery' ], Constants::get_version(), true );
		wp_register_script( 'rt-ele-parallax', kariez_get_js( 'ele-parallax' ), [ 'jquery' ], Constants::get_version(), true );
		wp_register_script( 'rt-appear', kariez_get_js( 'appear' ), [ 'jquery' ], Constants::get_version(), true );
		wp_register_script( 'rt-magnific-popup', kariez_get_js( 'magnific-popup' ), [ 'jquery' ], Constants::get_version(), true );// magnific js
		wp_register_script( 'rt-nice-select', kariez_get_js( 'nice-select' ), [ 'jquery' ], Constants::get_version(), true );// Isotope js
		wp_register_script( 'rt-isotope', kariez_get_js( 'isotope.min' ), [ 'jquery' ], Constants::get_version(), true );// Swiper js
		wp_register_script( 'rt-swiper', kariez_get_js( 'swiper.min' ), [ 'jquery' ], Constants::get_version(), true );// headRoom js
		wp_register_script( 'rt-headroom', kariez_get_js( 'headroom' ), [ 'jquery' ], Constants::get_version(), true );// headRoom js
		wp_register_script( 'rt-wow', kariez_get_js( 'wow.min' ), [ 'jquery' ], Constants::get_version(), true );

	}

	/**
	 * Enqueue all necessary scripts and styles for the theme
	 * @return void
	 */
	public function enqueue_scripts() {
		// CSS
		wp_enqueue_style( 'kariez-gfonts' );
		wp_enqueue_style( 'rt-animate' );
		wp_enqueue_style( 'swiper-min' );
		wp_enqueue_style( 'rt-magnific-popup' );
		wp_enqueue_style( 'kariez-main', kariez_get_css( 'style', true ), [], Constants::get_version() );

		// JS
		wp_enqueue_script( 'rt-appear', kariez_get_js( 'appear' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'rt-magnific-popup', kariez_get_js( 'magnific-popup' ), [ 'jquery' ], Constants::get_version(), true );// magnific js
		wp_enqueue_script( 'rt-isotope', kariez_get_js( 'isotope.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'rt-swiper', kariez_get_js( 'swiper.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'rt-headroom', kariez_get_js( 'headroom' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'rt-wow', kariez_get_js( 'wow.min' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'rt-ele-parallax', kariez_get_js( 'ele-parallax' ), [ 'jquery' ], Constants::get_version(), true );
		wp_enqueue_script( 'kariez-main', kariez_get_js( 'scripts' ), [ 'jquery', 'imagesloaded' ], Constants::get_version(), true );

		// Extra
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// localize script
		$kariez_localize_data = array(
			'rtl' => is_rtl()?'rtl':'ltr',

			// Ajax
			'ajaxURL' => admin_url('admin-ajax.php'),
			'kariezNonce' => wp_create_nonce( 'rt-kariez-nonce' ),
		);
		wp_localize_script( 'kariez-main', 'kariezObj', $kariez_localize_data );

	}

	public function fonts_url() {

		if ( 'off' === _x( 'on', 'Google font: on or off', 'kariez' ) ) {
			return '';
		}

		//Default variable.
		$subsets = '';

		$body_font = json_decode( kariez_option( 'rt_body_typo' ), true );
		$menu_font = json_decode( kariez_option( 'rt_menu_typo' ), true );
		$h_font    = json_decode( kariez_option( 'rt_all_heading_typo' ), true );

		$bodyFont = $body_font['font'] ?? 'Archivo'; // Body Font
		$menuFont = $menu_font['font'] ?? $bodyFont; // Menu Font
		$hFont    = $h_font['font'] ?? $body_font; // Heading Font
		$hFontW   = $h_font['regularweight'] ?? '';

		$heading_fonts = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];

		foreach ( $heading_fonts as $heading ) {
			$heading_font         = json_decode( kariez_option( "rt_heading_{$heading}_typo" ), true );
			${$heading . '_font'} = $heading_font;
			${$heading . 'Font'}  = '';
			if ( ! empty( $heading_font['font'] ) ) {
				${$heading . 'Font'}  = $heading_font['font'] == 'Inherit' ? $hFont : $heading_font['font'];
				${$heading . 'FontW'} = $heading_font['font'] == 'Inherit' ? $hFontW : $heading_font['regularweight'];
			}
		}

		$check_families = [];
		$font_families  = [];

		// Body Font
		$font_families[]  = $bodyFont . ':100,200,300,400,500,600,700,800,900';
		$check_families[] = $bodyFont;

		// Menu Font
		if ( ! in_array( $menuFont, $check_families ) ) {
			$font_families[]  = $menuFont . ':100,200,300,400,500,600,700,800,900';
			$check_families[] = $menuFont;
		}

		// Heading Font
		if ( ! in_array( $hFont, $check_families ) ) {
			$font_families[]  = $hFont . ':100,200,300,400,500,600,700,800,900';
			$check_families[] = $hFont;
		}

		//Check all heading fonts
		foreach ( $heading_fonts as $heading ) {
			$hDynamic = ${$heading . '_font'};
			if ( ! empty( $hDynamic['font'] ) ) {
				if ( ! in_array( ${$heading . 'Font'}, $check_families ) ) {
					$font_families[]  = ${$heading . 'Font'} . ':' . ${$heading . 'FontW'};
					$check_families[] = ${$heading . 'Font'};
				}
			}
		}

		$final_fonts = array_unique( $font_families );
		$query_args  = [
			'family'  => urlencode( implode( '|', $final_fonts ) ),
			'display' => urlencode( 'fallback' ),
		];

		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );

		return esc_url_raw( $fonts_url );
	}
}


