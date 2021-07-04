<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


?>


<div class="item-wp-title"><h2>{@wp_title}</h2></div>

<div class="item-oembed">{@acf_oembed}</div>

<div class="item-acf-title">{@acf_title}</div>

<div class="item-pic">
	<img src="{@pic_src}" width="{@pic_width}" height="{@pic_height}" border="0" />
</div>

{@acf_profile}

{@acf_source}

{@acf_links}


<?php
// EOF