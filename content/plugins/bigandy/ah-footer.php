<?php 
function ah_footer() {
    echo '<script type="text/javascript" src="//use.typekit.net/avh3olg.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>';    
}
add_action('wp_footer', 'ah_footer');