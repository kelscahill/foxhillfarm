<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @author 		Vamtam
 * @package 	wpv
 * @subpackage  construction
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating   = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container">

		<div class="comment-author">
			<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '' ); ?>
		</div>
		<div class="comment-content">
			<?php do_action( 'woocommerce_review_before_comment_meta', $comment ); ?>

			<div class="comment-meta">
				<h5 class="comment-author-link" itemprop="author"><?php comment_author() ?></h5>

				<?php
					if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
						if ( $verified )
							echo '<em class="verified">(' . __( 'verified owner', 'woocommerce' ) . ')</em> ';
				?>

				<h6 title="<?php echo get_comment_date( 'c' ); ?>" class="comment-time"><time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( __( get_option( 'date_format' ), 'woocommerce' ) );  // xss ok ?></time></h6>

				<?php edit_comment_link( sprintf( '[%s]', __( 'Edit', 'honeymoon' ) ) ) ?>

				<?php if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) : ?>
					<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo esc_attr( sprintf( __( 'Rated %d out of 5', 'woocommerce' ), $rating ) ) ?>">
						<span style="width:<?php echo esc_attr( intval( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) ) / 5 * 100 ); ?>%"><strong itemprop="ratingValue"><?php echo intval( get_comment_meta( $GLOBALS['comment']->comment_ID, 'rating', true ) ); ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( 0 === (int) $GLOBALS['comment']->comment_approved ) : ?>
				<p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'woocommerce' ); ?></em></p>
			<?php endif ?>

			<?php do_action( 'woocommerce_review_before_comment_text', $comment ); ?>

			<div itemprop="description" class="description"><?php comment_text(); ?></div>

			<?php do_action( 'woocommerce_review_after_comment_text', $comment ); ?>
		</div>
		<div class="clearfix"></div>
	</div>