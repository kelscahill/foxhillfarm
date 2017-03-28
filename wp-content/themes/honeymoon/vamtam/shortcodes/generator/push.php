<?php
return array(
	'name' => __('Vertical Blank Space', 'honeymoon') ,
	'value' => 'push',
	'options' => array(
		array(
			"name" => __("Height", 'honeymoon') ,
			"id" => "h",
			"default" => 30,
			'min' => -200,
			'max' => 200,
			"type" => "range",
		) ,
	) ,
);
