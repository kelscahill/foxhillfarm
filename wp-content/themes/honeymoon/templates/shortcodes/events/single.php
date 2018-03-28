<?php
	$tag = (int)$count == 1 ? 'span' : 'div';
?>
<?php foreach($events as $event): ?>
	<<?php echo $tag ?>>
		<span class="wpv-countdown single-event style-<?php echo esc_attr( $style ) ?> layout-<?php echo esc_attr( $layout ) ?>" data-until="<?php echo esc_attr( Tribe__Events__Timezones::event_start_timestamp( $event->ID ) ) ?>" data-done="<?php echo esc_attr( $ongoing ) ?>">
			<span class="wpvc-days"><span class="value">&ndash;</span> <span class="word"><?php _e( 'Days', 'honeymoon' ) ?></span></span>
			<span class="wpvc-hours"><span class="value">&ndash;</span> <span class="word"><?php _e( 'Hours', 'honeymoon' ) ?></span></span>
			<span class="wpvc-minutes"><span class="value">&ndash;</span> <span class="word"><?php _e( 'Minutes', 'honeymoon' ) ?></span></span>
			<span class="wpvc-seconds"><span class="value">&ndash;</span> <span class="word"><?php _e( 'Seconds', 'honeymoon' ) ?></span></span>
		</span>
		<a href="<?php tribe_event_link($event) ?>" title="<?php esc_attr_e('Read More') ?>"><?php _e('Read More &rarr;', 'church-event') ?></a>
	</<?php echo $tag ?>>
<?php endforeach; ?>