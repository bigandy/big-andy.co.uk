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
  			:root {
				--red: #C1272D;
			}
  
          	#login h1 a {
				background-image: none;
				background-color: var(--red);
				display: flex;
				justify-content: center;
				align-items: center;
				padding: 1em;
				clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
				color: white;
				font-weight: bold;
  			}
			
			#login h1 a::after {
				text-indent: 0;
				position: absolute;
				content: "A";
			}
			
  			#login [type="submit"] {
				background-color: var(--red);
				border-color: transparent;
				box-shadow: none;
				border-radius: 0;
				text-shadow: none;
  			}

  			#login input:focus {
  				box-shadow: none;
  				border-color: var(--red);
  			}
        </style>';
}
add_action( 'login_enqueue_scripts', 'ba_custom_admin_logo' );
