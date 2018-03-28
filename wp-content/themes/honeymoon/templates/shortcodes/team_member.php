<?php

$content   = trim($content);
$icons_map = array(
	'googleplus' => 'googleplus',
	'linkedin'   => 'linkedin',
	'facebook'   => 'facebook',
	'twitter'    => 'twitter',
	'youtube'    => 'youtube',
	'pinterest'  => 'pinterest',
	'lastfm'     => 'lastfm',
	'instagram'  => 'instagram',
	'dribble'    => 'dribbble2',
	'vimeo'      => 'vimeo',
);

?>
<div class="team-member<?php echo (!empty($content) ? ' has-content' : '')?>">
	<?php if(!empty($picture)): ?>
	<div class="thumbnail">
		<?php if(!empty($url)):?>
			<a href="<?php echo $url ?>" title="<?php echo esc_attr( strip_tags( $name ) )?>">
		<?php endif ?>
			<?php wpv_url_to_image( $picture ) ?>
		<?php if(!empty($url)):?>
			</a>
		<?php endif ?>
	</div>
	<?php endif ?>
	<div class="team-member-info">
		<h1>
			<?php if(!empty($url)):?>
				<a href="<?php echo $url ?>" title="<?php echo esc_attr( strip_tags( $name ) )?>">
			<?php endif ?>
				<?php echo $name?>
			<?php if(!empty($url)):?>
				</a>
			<?php endif ?>
		</h1>
		<?php if(!empty($position)): ?>
			<div class="sep-text centered">
				<span class="sep-text-before"><div class="sep-text-line"></div></span>
				<div class="content">
					<h5 class="regular-title-wrapper team-member-position">
						<?php echo $position ?>
					</h5>
				</div>
				<span class="sep-text-after"><div class="sep-text-line"></div></span>
			</div>
		<?php endif ?>
		<?php if(!empty($email)):?>
			<div><a href="mailto:<?php echo esc_attr( $email ) ?>" title="<?php esc_attr( sprintf( __('email %s', 'honeymoon'), strip_tags( $name ) ) ) ?>"><?php echo $email ?></a></div>
		<?php endif ?>
		<?php if(!empty($phone)):?>
			<div class="team-member-phone"><?php echo $phone?></div>
		<?php endif ?>
		<div class="share-icons clearfix">
			<?php
				$icons = array_keys( $icons_map );
				foreach($icons as $icon): if(!empty($$icon)):  // that's not good enough, should be changed
					$icon_name = isset( $icons_map[$icon] ) ? $icons_map[$icon] : $icon;
			?>
					<a href="<?php echo $$icon?>" title=""><?php echo do_shortcode('[icon name="'.$icon_name.'"]'); ?></a>
			<?php endif; endforeach; ?>
		</div>
	</div>
	<?php if(!empty($content)): ?>
	<div class="team-member-bio">
		<?php echo do_shortcode($content) ?>
	</div>
	<?php endif ?>
</div>
