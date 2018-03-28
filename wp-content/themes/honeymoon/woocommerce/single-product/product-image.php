<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

$attachment_ids = $product->get_gallery_image_ids();

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';

$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">

	<?php
		if ( has_post_thumbnail() ) {
			$large_thumbnail_size = apply_filters( 'single_product_large_thumbnail_size', 'shop_single' );
			$small_thumbnail_size = apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' );

			$main_image_id = get_post_thumbnail_id();

			$attachment_count = count( $attachment_ids );


			if ( $attachment_count > 0 ) :
				array_unshift( $attachment_ids, $main_image_id );
			?>

				<div class="bxslider-wrapper">
					<ul class="bxslider-container" id="product-gallery-<?php echo intval( $post->ID ) ?>">
						<?php foreach ( $attachment_ids as $aid ) : ?>
							<li>
								<?php
									$full_size_image = wp_get_attachment_image_src( $aid, $large_thumbnail_size );
									$image_title     = esc_attr( get_the_title( $aid ) );

									$attributes = array(
										'title'                   => $image_title,
										'data-src'                => $full_size_image[0],
										'data-large_image'        => $full_size_image[0],
										'data-large_image_width'  => $full_size_image[1],
										'data-large_image_height' => $full_size_image[2],
									);

									$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
									$html .= wp_get_attachment_image( $aid, $large_thumbnail_size );
									$html .= '</a></div>';

									echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );
								?>
							</li>
						<?php endforeach ?>
					</ul>
					<script>
						jQuery(function($) {
							var el = $('#product-gallery-<?php echo intval( $post->ID ) ?>');
							el.data('bxslider', el.bxSlider({
								pagerCustom: '#product-gallery-pager-<?php echo intval( $post->ID ) ?>',
								controls: false,
								adaptiveHeight: true
							}));
						});
					</script>
				</div>

			<?php else : ?>
				<?php
					$image_link  = wp_get_attachment_url( $main_image_id );
					$image_title = esc_attr( get_the_title( $main_image_id ) );
					$image       = get_the_post_thumbnail( $post->ID, $large_thumbnail_size, array(
		 'title' => $image_title
					) );
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s"  rel="prettyPhoto">%s</a>', $image_link, $image_title, $image ), $post->ID ); // xss ok
				?>
			<?php endif;

		} else {

			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</div>';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

		}
	?>

	<?php if ( $attachment_ids ) : ?>
		<div class="thumbnails" id="product-gallery-pager-<?php echo intval( $post->ID ) ?>"><?php

			$loop = 0;

			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array();

				if ( $loop == 0 || $loop % $columns == 0 )
					$classes[] = 'first';

				if ( ( $loop + 1 ) % $columns == 0 )
					$classes[] = 'last';

				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
				$image_class = esc_attr( implode( ' ', $classes ) );
				$image_title = esc_attr( get_the_title( $attachment_id ) );

				echo apply_filters( 'wpv_woocommerce_single_product_image_thumbnail_html', sprintf( '<a data-slide-index="%d" href="" class="%s" title="%s">%s</a>', $loop, $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class ); // xss ok

				$loop++;
			}

		?></div>
	<?php endif ?>
</div>
