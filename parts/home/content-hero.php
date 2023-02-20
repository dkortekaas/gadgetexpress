<?php
/**
 * Template part for displaying content hero
 *
 * @package Gadget Express
 */

?>

<style>
@media only screen and (min-width: 1168px) {
.slider-wrapper {height:500px !important;}
}
</style>

    <div class="slider">
        <div class="slider-wrapper">
            <ul>
                <li>
                    <img src="<?php echo get_template_directory_uri() . '/assets/images/gadgetexpress-header.jpg'; ?>" alt="Gadget Express - Logo" title="<?php _e('Buy the coolest gadgets at Gadget Express!', 'gadget'); ?>" width="1400" height="500" class="slide">
                    <div class="slider-caption">
                        <?php _e('Buy the coolest gadgets at Gadget Express!', 'gadget'); ?><br/>
                        <a class="btn btn-primary slider-btn" href="/gadgets" title="<?php _e('Shop Gadgets', 'gadget'); ?>"><?php _e('Shop Gadgets', 'gadget'); ?></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
