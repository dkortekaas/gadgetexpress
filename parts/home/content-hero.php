<?php
/**
 * Template part for displaying content hero
 *
 * @package Gadget Express
 */

?>

<style>
@media only screen and (min-width: 1168px) {
.slider-wrapper {height:550px !important;}
}
</style>

    <div class="slider">
        <div class="slider-wrapper">
            <ul>
                <li>
                    <img src="<?php echo get_template_directory_uri() . '/assets/images/header.jpg'; ?>" alt="Gadget Express - Logo" title="<?php _e('Buy the coolest gadgets at Gadget Express!', 'gadget'); ?>" width="1920" height="960" class="slide">
                    <div class="slider-caption">
                        <?php _e('Buy the coolest gadgets at Gadget Express!', 'gadget'); ?><br/>
                        <a class="btn btn-primary slider-btn" href="/producten" title="<?php _e('Shop Products', 'gadget'); ?>"><?php _e('Shop Products', 'gadget'); ?></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
