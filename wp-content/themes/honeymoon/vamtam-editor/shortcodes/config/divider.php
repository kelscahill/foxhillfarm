<?php
return array(
	'name' => __('Divider', 'honeymoon') ,
	'icon' => array(
		'char' => WPV_Editor::get_icon('minus'),
		'size' => '30px',
		'lheight' => '45px',
		'family' => 'vamtam-editor-icomoon',
	),
	'value' => 'divider',
	'controls' => 'name clone edit delete',
	'options' => array(
		array(
			'name' => __('Type', 'honeymoon') ,
			'desc' => __('"Clear floats" is just a div element with <em>clear:both</em> styles. Although it is safe to say that unless you already know how to use it, you will not need this, you can <a href="https://developer.mozilla.org/en-US/docs/CSS/clear">click here for a more detailed description</a>.', 'honeymoon'),
			'id' => 'type',
			'default' => 1,
			'options' => array(
				1 => __('Divider line 1 px with accent line', 'honeymoon') ,
				2 => __('Divider double lines', 'honeymoon') ,
				3 => __('Divider line 1 px', 'honeymoon') ,
				'clear' => __('Clear floats', 'honeymoon') ,
			) ,
			'type' => 'select',
			'class' => 'add-to-container',
		) ,
	) ,
);
