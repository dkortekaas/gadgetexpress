<?php
/**
 * Template part for displaying products on front page
 *
 * @package Gadget Express
 */

?>

    <div class="vc_row wpb_row vc_row-fluid gadgetexpress-no-padding-left gadgetexpress-no-padding-right vc_custom_1531299284109 gadgetexpress-row-full-width">
        <div class="container-fluid">
            <div class="row">
                <div class="wpb_column vc_column_container vc_col-sm-12">
                    <div class="vc_column-inner ">
                        <div class="wpb_wrapper">
                            <div class="gadgetexpress-products-grid gadgetexpress-products style-4 border-bottom">

                                <div class="product-header">
                                    <ul class="nav nav-tabs nav-filter filter" role="tablist">
                                        <li role="presentation" class="active"><a href="#tech" aria-controls="tech" role="tab" data-toggle="tab">
                                            <?php _e('Tech', 'gadget'); ?>
                                        </a></li>
                                        <li role="presentation"><a href="#phone" aria-controls="phone" role="tab" data-toggle="tab">
                                            <?php _e('Phone', 'gadget'); ?>
                                        </a></li>
                                        <li role="presentation"><a href="#household" aria-controls="household" role="tab" data-toggle="tab">
                                            <?php _e('Household', 'gadget'); ?>
                                        </a></li>
                                    </ul>
                                </div>

                                <div class="product-wrapper tab-content">

                                    <div role="tabpanel" class="tab-pane active" id="tech">

                                        <div class="woocommerce columns-5">
                                            <ul class="products columns-5">
                                            <?php
                                            $ties = wc_get_products( array(
                                                'limit' => 10,
                                                'category' => array( 'tech' ),
                                                'orderby' => 'date',
                                                'order' => 'DESC',
                                            ) );                                            

                                            if (!empty($ties)) :
                                                foreach ($ties as $tie) : ?>
                                                    <li class="product type-product col-xs-6 col-sm-4 col-md-1-5 un-5-cols<?php //echo $class_latest; ?>">
                                                        <div class="product-inner clearfix">
                                                            <?php gadgetexpress_product( $tie ); ?>
                                                        </div>
                                                    </li>
                                                <?php
                                                endforeach;
                                            endif;
                                            wp_reset_postdata();
                                            wp_reset_query();
                                            ?>
                                            </ul>
                                        </div>

                                    </div>
 
                                     <div role="tabpanel" class="tab-pane" id="phone">

                                        <div class="woocommerce columns-5">
                                            <ul class="products columns-5">
                                            <?php
                                            $scarfs = wc_get_products( array(
                                                'limit' => 10,
                                                'category' => array( 'phone' ),
                                                'orderby' => 'date',
                                                'order' => 'DESC',
                                            ) );

                                            if (!empty($scarfs)) :
                                                foreach ($scarfs as $scarf) : ?>
                                                    <li class="product type-product col-xs-6 col-sm-4 col-md-1-5 un-5-cols<?php //echo $class_latest; ?>">
                                                        <div class="product-inner clearfix">
                                                            <?php gadgetexpress_product( $scarf ); ?>
                                                        </div>
                                                    </li>
                                                <?php
                                                endforeach;
                                            endif;
                                            wp_reset_postdata();
                                            wp_reset_query();
                                            ?>
                                            </ul>
                                        </div>
                                    </div>
                                
                                    <div role="tabpanel" class="tab-pane" id="household">

                                        <div class="woocommerce columns-5">
                                            <ul class="products columns-5">
                                            <?php
                                            $combisets_products = wc_get_products( array(
                                                'limit' => 10,
                                                'category' => array( 'huishouden', 'household' ),
                                                'orderby' => 'date',
                                                'order' => 'DESC',
                                            ) );

                                            if (!empty($combisets_products)) :
                                                foreach ($combisets_products as $combisets_product) : ?>
                                                    <li class="product type-product col-xs-6 col-sm-4 col-md-1-5 un-5-cols<?php //echo $class_latest; ?>">
                                                        <div class="product-inner clearfix">
                                                            <?php gadgetexpress_product( $combisets_product ); ?>
                                                        </div>
                                                    </li>
                                                <?php
                                                endforeach;
                                            endif;
                                            wp_reset_postdata();
                                            wp_reset_query();
                                            ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="load-more text-center">
                                        <a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>" class="ajax-load-products">
                                            <span class="button-text"><?php _e('All Gadgets', 'gadget'); ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
