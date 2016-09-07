<?php
// only display custom menu bar if the user is logged in and is an admin
if ( current_user_can( 'update_core' ) && is_user_logged_in() ) {
	function es_admin_css() {
		?>
		<style> #wpadminbar { background: <?php echo esc_attr( HEADERCOLOR ); ?>; }</style>
		<?php
	}
	add_action( 'wp_head', 'es_admin_css' );
	add_action( 'admin_head', 'es_admin_css' );
}

function ba_custom_admin_logo() {
  echo '<style>
          	#login h1 a {
  				background-image: url(' . get_bloginfo( 'template_directory' ) . '/images/ba-octagon.svg);
  			}
  			#login [type="submit"] {
				background-color: #C1272D;
				border-color: transparent;
				box-shadow: none;
				border-radius: 0;
				text-shadow: none;
  			}

  			#login input:focus {
  				box-shadow: none;
  				border-color: #C1272D;
  			}
        </style>';
}
add_action( 'login_enqueue_scripts', 'ba_custom_admin_logo' );
