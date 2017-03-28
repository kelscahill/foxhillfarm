<?php

/**

 * @package   Revolution Slider

 * @author    ThemePunch <info@themepunch.com>

 * @link      http://www.revolution.themepunch.com/

 * @copyright 2015 ThemePunch

 */

 

if( !defined( 'ABSPATH') ) exit();



class RevSliderNavigation {

	

	public function __construct(){

		

	}

	

	

	public function init_by_id($nav_id){

		if(intval($nav_id) == 0) return false;

		

		global $wpdb;

		

		$row = $wpdb->get_row($wpdb->prepare("SELECT `id`, `handle`, `type`, `css`, `settings` FROM ".$wpdb->prefix.RevSliderGlobals::TABLE_NAVIGATION_NAME." WHERE `id` = %d", $nav_id), ARRAY_A);

		

		return $row;

		

	}

	

	

	/**

	 * Get all Navigations Short

	 * @since: 5.0

	 **/

	public static function get_all_navigations_short(){

		global $wpdb;

		

		$navigations = $wpdb->get_results("SELECT `id`, `handle`, `name` FROM ".$wpdb->prefix.RevSliderGlobals::TABLE_NAVIGATION_NAME, ARRAY_A);

		

		return $navigations;

	}

	

	

	/**

	 * Get all Navigations

	 * @since: 5.0

	 **/

	public static function get_all_navigations($defaults = true, $raw = false){

		global $wpdb;

		

		$navigations = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.RevSliderGlobals::TABLE_NAVIGATION_NAME, ARRAY_A);

		

		if($raw == false){

			foreach($navigations as $key => $nav){

				$navigations[$key]['css'] = RevSliderBase::stripslashes_deep(json_decode($navigations[$key]['css'], true));

				$navigations[$key]['markup'] = RevSliderBase::stripslashes_deep(json_decode($navigations[$key]['markup'], true));

				if(isset($navigations[$key]['settings'])){

					$navigations[$key]['settings'] = RevSliderBase::stripslashes_deep(json_decode($navigations[$key]['settings'], true));

				}

			}

		}

		

		if($defaults){

			$def = self::get_default_navigations();

			

			if(!empty($def)){

				if($raw == false){

					foreach($def as $key => $nav){

						

						$def[$key]['css'] = RevSliderBase::stripslashes_deep(json_decode($def[$key]['css'], true));

						$def[$key]['markup'] = RevSliderBase::stripslashes_deep(json_decode($def[$key]['markup'], true));

					}

					if(isset($def[$key]['settings'])){

						$def[$key]['settings'] = RevSliderBase::stripslashes_deep(json_decode($def[$key]['settings'], true));

					}

				}

				$navigations = array_merge($navigations, $def);

			}

		}

		

		return $navigations;

	}

	

	

	/**

	 * Creates / Updates Navigation skins

	 * @since: 5.0

	 **/

	public function create_update_full_navigation($data){

		global $wpdb;

		

		if(!empty($data) && is_array($data)){

			

			$navigations = self::get_all_navigations();

			

			foreach($data as $vals){

				$found = false;

				

				if(!isset($vals['markup']) || !isset($vals['css'])) continue;

				if(isset($vals['default']) && $vals['default'] == true) continue; //defaults can't be deleted

				

				if(isset($vals['id'])){ //new will be added temporary to navs to tell here that they are new

					

					if(intval($vals['id']) === 0) continue; //0

					

					foreach($navigations as $nav){

						if($vals['id'] == $nav['id']){

							$found = true;

							break;

						}

					}

				}

				

				if($found == true){ //update

					self::create_update_navigation($vals, $vals['id']);

				}else{ //create

					self::create_update_navigation($vals);

				}

			}

		}

		

		return true;

	}

	

	/**

	 * Creates / Updates Navigation skins

	 * @since: 5.0

	 **/

	public static function create_update_navigation($data, $nav_id = 0){

		global $wpdb;

		

		if(isset($data['default']) && $data['default'] == true) return false;

		

		if(!isset($data['settings'])) $data['settings'] = '';

		

		if($nav_id > 0){

			$response = $wpdb->update($wpdb->prefix.RevSliderGlobals::TABLE_NAVIGATION_NAME,

				array(

					'name' => $data['name'],

					'handle' => sanitize_title($data['name']),

					'markup' => json_encode($data['markup']),

					'css' => json_encode($data['css']),

					'settings' => json_encode($data['settings'])

				),

				array('id' => $nav_id)

			);

		}else{

			if(!isset($data['settings'])) $data['settings'] = '';

			$response = $wpdb->insert($wpdb->prefix.RevSliderGlobals::TABLE_NAVIGATION_NAME, array('name' => $data['name'], 'handle' => sanitize_title($data['name']), 'css' => json_encode($data['css']), 'markup' => json_encode($data['markup']), 'settings' => json_encode($data['settings'])));

		}

		

		return $response;

	}

	

	

	/**

	 * Delete Navigation

	 * @since: 5.0

	 **/

	public function delete_navigation($nav_id = 0){

		global $wpdb;

		

		if(!isset($nav_id) || intval($nav_id) == 0) return __('Invalid ID', 'revslider');

		

		

		$response = $wpdb->delete($wpdb->prefix.RevSliderGlobals::TABLE_NAVIGATION_NAME, array('id' => $nav_id));

		if($response === false) return __('Navigation could not be deleted', 'revslider');

		

		return true;



	}

	

	

	/**

	 * Get Default Navigation

	 * @since: 5.0

	 **/

	public static function get_default_navigations(){

		$navigations = array();

				

		$navigations[] = array(	'id' => 5000,

								'default' => true,

								'name' => 'Hesperiden',

								'handle' => 'round',

								'markup' => '{

											"arrows":"",

											"bullets":"",

											"thumbs":"<span class=\\\\\\"tp-thumb-image\\\\\\"><\/span>\\n<span class=\\\\\\"tp-thumb-title\\\\\\">{{title}}<\/span>",

											"tabs":"<div class=\\\\\\"tp-tab-content\\\\\\">\\n  <span class=\\\\\\"tp-tab-date\\\\\\">{{param1}}<\/span>\\n  <span class=\\\\\\"tp-tab-title\\\\\\">{{title}}<\/span>\\n<\/div>\\n<div class=\\\\\\"tp-tab-image\\\\\\"><\/div>"

											}',

								'css' => '{

											"arrows":".hesperiden.tparrows {\\n\\tcursor:pointer;\\n\\tbackground:#000;\\n\\tbackground:rgba(0,0,0,0.5);\\n\\twidth:40px;\\n\\theight:40px;\\n\\tposition:absolute;\\n\\tdisplay:block;\\n\\tz-index:100;\\n    border-radius: 50%;\\n}\\n.hesperiden.tparrows:hover {\\n\\tbackground:#000;\\n}\\n.hesperiden.tparrows:before {\\n\\tfont-family: \\\\\\"revicons\\\\\\";\\n\\tfont-size:20px;\\n\\tcolor:#fff;\\n\\tdisplay:block;\\n\\tline-height: 40px;\\n\\ttext-align: center;\\n}\\n.hesperiden.tparrows.tp-leftarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\e82c\\\\\\";\\n    margin-left:-3px;\\n}\\n.hesperiden.tparrows.tp-rightarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\e82d\\\\\\";\\n    margin-right:-3px;\\n}",

											"bullets":".hesperiden.tp-bullets {\\n}\\n.hesperiden.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground:transparent;\\n\\tpadding:10px;\\n\\tmargin-left:-10px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n   border-radius:8px;\\n  \\n}\\n.hesperiden .tp-bullet {\\n\\twidth:12px;\\n\\theight:12px;\\n\\tposition:absolute;\\n\\tbackground: #999999; \/* old browsers *\/\\n    background: -moz-linear-gradient(top,  #999999 0%, #e1e1e1 100%); \/* ff3.6+ *\/\\n    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#999999), \\n    color-stop(100%,#e1e1e1)); \/* chrome,safari4+ *\/\\n    background: -webkit-linear-gradient(top,  #999999 0%,#e1e1e1 100%); \/* chrome10+,safari5.1+ *\/\\n    background: -o-linear-gradient(top,  #999999 0%,#e1e1e1 100%); \/* opera 11.10+ *\/\\n    background: -ms-linear-gradient(top,  #999999 0%,#e1e1e1 100%); \/* ie10+ *\/\\n    background: linear-gradient(to bottom,  #999999 0%,#e1e1e1 100%); \/* w3c *\/\\n    filter: progid:dximagetransform.microsoft.gradient( \\n    startcolorstr=\\\\\\"#999999\\\\\\", endcolorstr=\\\\\\"#e1e1e1\\\\\\",gradienttype=0 ); \/* ie6-9 *\/\\n\\tborder:3px solid #e5e5e5;\\n\\tborder-radius:50%;\\n\\tcursor: pointer;\\n\\tbox-sizing:content-box;\\n}\\n.hesperiden .tp-bullet:hover,\\n.hesperiden .tp-bullet.selected {\\n\\tbackground:#666;\\n}\\n.hesperiden .tp-bullet-image {\\n}\\n.hesperiden .tp-bullet-title {\\n}\\n",

											"thumbs":".hesperiden .tp-thumb {\\n  opacity:1;\\n  -webkit-perspective: 600px;\\n  perspective: 600px;\\n}\\n.hesperiden .tp-thumb .tp-thumb-title {\\n    font-size:12px;\\n    position:absolute;\\n    margin-top:-10px;\\n    color:#fff;\\n    display:block;\\n    z-index:1000;\\n    background-color:#000;\\n    padding:5px 10px; \\n    bottom:0px;\\n    left:0px;\\n    width:100%;\\n  box-sizing:border-box;\\n    text-align:center;\\n    overflow:hidden;\\n    white-space:nowrap;\\n    transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n    transform:rotatex(90deg) translatez(0.001px);\\n    transform-origin:50% 100%;\\n    -webkit-transform:rotatex(90deg) translatez(0.001px);\\n    -webkit-transform-origin:50% 100%;\\n    opacity:0;\\n }\\n.hesperiden .tp-thumb:hover .tp-thumb-title {\\n  \\t transform:rotatex(0deg);\\n    -webkit-transform:rotatex(0deg);\\n    opacity:1;\\n}",

											"tabs":".hesperiden .tp-tab { \\n  opacity:1;      \\n  padding:10px;\\n  box-sizing:border-box;\\n  font-family: \\\\\\"Roboto\\\\\\", sans-serif;\\n  border-bottom: 1px solid #e5e5e5;\\n }\\n.hesperiden .tp-tab-image \\n{ \\n  width:60px;\\n  height:60px; max-height:100%; max-width:100%;\\n  position:relative;\\n  display:inline-block;\\n  float:left;\\n\\n}\\n.hesperiden .tp-tab-content \\n{\\n    background:rgba(0,0,0,0); \\n    position:relative;\\n    padding:15px 15px 15px 85px;\\n left:0px;\\n overflow:hidden;\\n margin-top:-15px;\\n    box-sizing:border-box;\\n    color:#333;\\n    display: inline-block;\\n    width:100%;\\n    height:100%;\\n position:absolute; }\\n.hesperiden .tp-tab-date\\n  {\\n  display:block;\\n  color: #aaa;\\n  font-weight:500;\\n  font-size:12px;\\n  margin-bottom:0px;\\n  }\\n.hesperiden .tp-tab-title \\n{\\n    display:block;\\t\\n    text-align:left;\\n    color:#333;\\n    font-size:14px;\\n    font-weight:500;\\n    text-transform:none;\\n    line-height:17px;\\n}\\n.hesperiden .tp-tab:hover,\\n.hesperiden .tp-tab.selected {\\n\\tbackground:#eee; \\n}\\n\\n.hesperiden .tp-tab-mask {\\n}\\n\\n\/* MEDIA QUERIES *\/\\n@media only screen and (max-width: 960px) {\\n\\n}\\n@media only screen and (max-width: 768px) {\\n\\n}"

											}',

								'settings' => '{"width":{"thumbs":"160","arrows":"160","bullets":"160","tabs":"250"},"height":{"thumbs":"160","arrows":"160","bullets":"160","tabs":"80"}}'

							);



		$navigations[] = array('id' => 5001,'default' => true,'name' => 'Gyges','handle' => 'navbar','markup' => '{"arrows":"","bullets":"","tabs":"<div class=\\\\\\"tp-tab-content\\\\\\">\\n  <span class=\\\\\\"tp-tab-date\\\\\\">{{param1}}<\/span>\\n  <span class=\\\\\\"tp-tab-title\\\\\\">{{title}}<\/span>\\n<\/div>\\n<div class=\\\\\\"tp-tab-image\\\\\\"><\/div>","thumbs":"<span class=\\\\\\"tp-thumb-img-wrap\\\\\\">\\n  <span class=\\\\\\"tp-thumb-image\\\\\\"><\/span>\\n<\/span>\\n"}','css' => '{"arrows":"","bullets":".gyges.tp-bullets {\\n}\\n.gyges.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground: #777777; \/* Old browsers *\/\\n    background: -moz-linear-gradient(top,  #777777 0%, #666666 100%); \\n    background: -webkit-gradient(linear, left top, left bottom, \\n    color-stop(0%,#777777), color-stop(100%,#666666)); \\n    background: -webkit-linear-gradient(top,  #777777 0%,#666666 100%); \\n    background: -o-linear-gradient(top,  #777777 0%,#666666 100%); \\n    background: -ms-linear-gradient(top,  #777777 0%,#666666 100%); \\n    background: linear-gradient(to bottom,  #777777 0%,#666666 100%); \\n    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\\\\\\"#777777\\\\\\", \\n    endColorstr=\\\\\\"#666666\\\\\\",GradientType=0 ); \\n\\tpadding:10px;\\n\\tmargin-left:-10px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n  border-radius:10px;\\n}\\n.gyges .tp-bullet {\\n\\twidth:12px;\\n\\theight:12px;\\n\\tposition:absolute;\\n\\tbackground:#333;\\n\\tborder:3px solid #444;\\n\\tborder-radius:50%;\\n\\tcursor: pointer;\\n\\tbox-sizing:content-box;\\n}\\n.gyges .tp-bullet:hover,\\n.gyges .tp-bullet.selected {\\n\\tbackground: #ffffff; \/* Old browsers *\/\\n    background: -moz-linear-gradient(top,  #ffffff 0%, #e1e1e1 100%); \/* FF3.6+ *\/\\n    background: -webkit-gradient(linear, left top, left bottom, \\n    color-stop(0%,#ffffff), color-stop(100%,#e1e1e1)); \/* Chrome,Safari4+ *\/\\n    background: -webkit-linear-gradient(top,  #ffffff 0%,#e1e1e1 100%); \/* Chrome10+,Safari5.1+ *\/\\n    background: -o-linear-gradient(top,  #ffffff 0%,#e1e1e1 100%); \/* Opera 11.10+ *\/\\n    background: -ms-linear-gradient(top,  #ffffff 0%,#e1e1e1 100%); \/* IE10+ *\/\\n    background: linear-gradient(to bottom,  #ffffff 0%,#e1e1e1 100%); \/* W3C *\/\\n    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\\\\\\"#ffffff\\\\\\", \\n    endColorstr=\\\\\\"#e1e1e1\\\\\\",GradientType=0 ); \/* IE6-9 *\/\\n\\n}\\n.gyges .tp-bullet-image {\\n}\\n.gyges .tp-bullet-title {\\n}\\n\\t","tabs":".gyges .tp-tab { \\n  opacity:1;      \\n  padding:10px;\\n  box-sizing:border-box;\\n  font-family: \\\\\\"Roboto\\\\\\", sans-serif;\\n  border-bottom: 1px solid rgba(255,255,255,0.15);\\n }\\n.gyges .tp-tab-image \\n{ \\n  width:60px;\\n  height:60px; max-height:100%; max-width:100%;\\n  position:relative;\\n  display:inline-block;\\n  float:left;\\n\\n}\\n.gyges .tp-tab-content \\n{\\n    background:rgba(0,0,0,0); \\n    position:relative;\\n    padding:15px 15px 15px 85px;\\n left:0px;\\n  overflow:hidden;\\n margin-top:-15px;\\n    box-sizing:border-box;\\n    color:#333;\\n    display: inline-block;\\n    width:100%;\\n    height:100%;\\n position:absolute; }\\n.gyges .tp-tab-date\\n  {\\n  display:block;\\n  color: rgba(255,255,255,0.25);\\n  font-weight:500;\\n  font-size:12px;\\n  margin-bottom:0px;\\n  }\\n.gyges .tp-tab-title \\n{\\n    display:block;  \\n    text-align:left;\\n    color:#fff;\\n    font-size:14px;\\n    font-weight:500;\\n    text-transform:none;\\n    line-height:17px;\\n}\\n.gyges .tp-tab:hover,\\n.gyges .tp-tab.selected {\\n  background:rgba(0,0,0,0.5); \\n}\\n\\n.gyges .tp-tab-mask {\\n}\\n\\n\/* MEDIA QUERIES *\/\\n@media only screen and (max-width: 960px) {\\n\\n}\\n@media only screen and (max-width: 768px) {\\n\\n}","thumbs":".gyges .tp-thumb { \\n      opacity:1\\n  }\\n.gyges .tp-thumb-img-wrap {\\n  padding:3px;\\n    background:#000;\\n  background-color:rgba(0,0,0,0.25);\\n  display:inline-block;\\n\\n  width:100%;\\n  height:100%;\\n  position:relative;\\n  margin:0px;\\n  box-sizing:border-box;\\n    transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n}\\n.gyges .tp-thumb-image {\\n   padding:3px; \\n   display:block;\\n   box-sizing:border-box;\\n   position:relative;\\n    -webkit-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  -moz-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n }  \\n.gyges .tp-thumb-title { \\n     position:absolute; \\n     bottom:100%; \\n     display:inline-block;\\n     left:50%;\\n     background:rgba(255,255,255,0.8);\\n     padding:10px 30px;\\n     border-radius:4px;\\n\\t -webkit-border-radius:4px;\\n     margin-bottom:20px;\\n     opacity:0;\\n      transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n    transform: translateZ(0.001px) translateX(-50%) translateY(14px);\\n    transform-origin:50% 100%;\\n    -webkit-transform: translateZ(0.001px) translateX(-50%) translateY(14px);\\n    -webkit-transform-origin:50% 100%;\\n    white-space:nowrap;\\n }\\n.gyges .tp-thumb:hover .tp-thumb-title {\\n  \\t transform:rotateX(0deg) translateX(-50%);\\n    -webkit-transform:rotateX(0deg) translateX(-50%);\\n    opacity:1;\\n}\\n\\n.gyges .tp-thumb:hover .tp-thumb-img-wrap,\\n .gyges .tp-thumb.selected .tp-thumb-img-wrap {\\n\\n  background: rgba(255,255,255,1);\\n  background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,1)), color-stop(100%, rgba(119,119,119,1)));\\n  background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -o-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: linear-gradient(to bottom, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\\\\\\"#ffffff\\\\\\", endColorstr=\\\\\\"#777777\\\\\\", GradientType=0 );\\n }\\n.gyges .tp-thumb-title:after {\\n        content:\\\\\\" \\\\\\";\\n        position:absolute;\\n        left:50%;\\n        margin-left:-8px;\\n        width: 0;\\n\\t\\theight: 0;\\n\\t\\tborder-style: solid;\\n\\t\\tborder-width: 8px 8px 0 8px;\\n\\t\\tborder-color: rgba(255,255,255,0.8) transparent transparent transparent;\\n        bottom:-8px;\\n   }\\n"}','settings' => '{"width":{"thumbs":"70","arrows":"160","bullets":"160","tabs":"300"},"height":{"thumbs":"70","arrows":"160","bullets":"160","tabs":"80"}}');

		$navigations[] = array(

								'id' => 5002,

								'default' => true,

								'name' => 'Hades',

								'handle' => 'preview1',

								'markup' => '{

												"arrows":"<div class=\\\\\\"tp-arr-allwrapper\\\\\\">\\n\\t<div class=\\\\\\"tp-arr-imgholder\\\\\\"><\/div>\\n<\/div>",

												"bullets":"<span class=\\\\\\"tp-bullet-image\\\\\\"><\/span>",

												"tabs":"<div class=\\\\\\"tp-tab-inner\\\\\\">\\n  <span class=\\\\\\"tp-tab-title\\\\\\">{{param1}}<\/span>\\n  <span class=\\\\\\"tp-tab-price\\\\\\">{{param2}}<\/span>\\n  <span class=\\\\\\"tp-tab-button\\\\\\">{{param3}}<\/span>\\n<\/div>",

												"thumbs":"<span class=\\\\\\"tp-thumb-img-wrap\\\\\\">\\n  <span class=\\\\\\"tp-thumb-image\\\\\\"><\/span>\\n<\/span>\\n"

											}',

								'css' => '{

												"arrows":".hades.tparrows {\\n\\tcursor:pointer;\\n\\tbackground:#000;\\n\\tbackground:rgba(0,0,0,0.15);\\n\\twidth:100px;\\n\\theight:100px;\\n\\tposition:absolute;\\n\\tdisplay:block;\\n\\tz-index:100;\\n}\\n\\n.hades.tparrows:before {\\n\\tfont-family: \\\\\\"revicons\\\\\\";\\n\\tfont-size:30px;\\n\\tcolor:#fff;\\n\\tdisplay:block;\\n\\tline-height: 100px;\\n\\ttext-align: center;\\n  transition: background 0.3s, color 0.3s;\\n}\\n.hades.tparrows.tp-leftarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\e824\\\\\\";\\n}\\n.hades.tparrows.tp-rightarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\e825\\\\\\";\\n}\\n\\n.hades.tparrows:hover:before {\\n   color:#aaa;\\n   background:#fff;\\n   background:rgba(255,255,255,1);\\n }\\n.hades .tp-arr-allwrapper {\\n  position:absolute;\\n  left:100%;\\n  top:0px;\\n  background:#888; \\n  width:100px;height:100px;\\n  -webkit-transition: all 0.3s;\\n  transition: all 0.3s;\\n  -ms-filter: \\\\\\"progid:dximagetransform.microsoft.alpha(opacity=0)\\\\\\";\\n  filter: alpha(opacity=0);\\n  -moz-opacity: 0.0;\\n  -khtml-opacity: 0.0;\\n  opacity: 0.0;\\n  -webkit-transform: rotatey(-90deg);\\n  transform: rotatey(-90deg);\\n  -webkit-transform-origin: 0% 50%;\\n  transform-origin: 0% 50%;\\n}\\n.hades.tp-rightarrow .tp-arr-allwrapper {\\n   left:auto;\\n   right:100%;\\n   -webkit-transform-origin: 100% 50%;\\n  transform-origin: 100% 50%;\\n   -webkit-transform: rotatey(90deg);\\n  transform: rotatey(90deg);\\n}\\n\\n.hades:hover .tp-arr-allwrapper {\\n   -ms-filter: \\\\\\"progid:dximagetransform.microsoft.alpha(opacity=100)\\\\\\";\\n  filter: alpha(opacity=100);\\n  -moz-opacity: 1;\\n  -khtml-opacity: 1;\\n  opacity: 1;  \\n    -webkit-transform: rotatey(0deg);\\n  transform: rotatey(0deg);\\n\\n }\\n    \\n.hades .tp-arr-iwrapper {\\n}\\n.hades .tp-arr-imgholder {\\n  background-size:cover;\\n  position:absolute;\\n  top:0px;left:0px;\\n  width:100%;height:100%;\\n}\\n.hades .tp-arr-titleholder {\\n}\\n.hades .tp-arr-subtitleholder {\\n}\\n",

												"bullets":".hades.tp-bullets {\\n}\\n.hades.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground:transparent;\\n\\tpadding:10px;\\n\\tmargin-left:-10px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n}\\n.hades .tp-bullet {\\n\\twidth:3px;\\n\\theight:3px;\\n\\tposition:absolute;\\n\\tbackground:#888;\\t\\n\\tcursor: pointer;\\n    border:5px solid #fff;\\n\\tbox-sizing:content-box;\\n    box-shadow:0px 0px 3px 1px rgba(0,0,0,0.2);\\n    -webkit-perspective:400;\\n    perspective:400;\\n    -webkit-transform:translatez(0.01px);\\n    transform:translatez(0.01px);\\n}\\n.hades .tp-bullet:hover,\\n.hades .tp-bullet.selected {\\n\\tbackground:#555;\\n  \\n}\\n\\n.hades .tp-bullet-image {\\n  position:absolute;top:-80px; left:-60px;width:120px;height:60px;\\n  background-position:center center;\\n  background-size:cover;\\n  visibility:hidden;\\n  opacity:0;\\n  transition:all 0.3s;\\n  -webkit-transform-style:flat;\\n  transform-style:flat;\\n  perspective:600;\\n  -webkit-perspective:600;\\n  transform: rotatex(-90deg);\\n  -webkit-transform: rotatex(-90deg);\\n  box-shadow:0px 0px 3px 1px rgba(0,0,0,0.2);\\n  transform-origin:50% 100%;\\n  -webkit-transform-origin:50% 100%;\\n  \\n  \\n}\\n.hades .tp-bullet:hover .tp-bullet-image {\\n  display:block;\\n  opacity:1;\\n  transform: rotatex(0deg);\\n  -webkit-transform: rotatex(0deg);\\n  visibility:visible;\\n    }\\n.hades .tp-bullet-title {\\n}\\n",

												"tabs":".hades .tp-tab {\\n  opacity:1;\\n }\\n    \\n.hades .tp-tab-title\\n {\\n      display:block;\\n      color:#333;\\n      font-weight:600;\\n      font-size:18px;\\n      text-align:center;\\n      line-height:25px;      \\n    } \\n.hades .tp-tab-price\\n {\\n\\tdisplay:block;\\n    text-align:center;\\n    color:#999;\\n    font-size:16px;\\n    margin-top:10px;\\n   line-height:20px\\n}\\n\\n.hades .tp-tab-button {\\n    display:inline-block;\\n    margin-top:15px;\\n    text-align:center;\\n\\tpadding:5px 15px;\\n  \\tcolor:#fff;\\n  \\tfont-size:14px;\\n  \\tbackground:#219bd7;\\n   \\tborder-radius:4px;\\n   font-weight:400;\\n}\\n.hades .tp-tab-inner {\\n\\ttext-align:center;\\n}\\n\\n              ",

												"thumbs":".hades .tp-thumb { \\n      opacity:1\\n  }\\n.hades .tp-thumb-img-wrap {\\n  border-radius:50%;\\n  padding:3px;\\n  display:inline-block;\\nbackground:#000;\\n  background-color:rgba(0,0,0,0.25);\\n  width:100%;\\n  height:100%;\\n  position:relative;\\n  margin:0px;\\n  box-sizing:border-box;\\n    transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n}\\n.hades .tp-thumb-image {\\n   padding:3px; \\n   border-radius:50%;\\n   display:block;\\n   box-sizing:border-box;\\n   position:relative;\\n    -webkit-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  -moz-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n }  \\n\\n\\n.hades .tp-thumb:hover .tp-thumb-img-wrap,\\n.hades .tp-thumb.selected .tp-thumb-img-wrap {\\n  \\n   background: rgba(255,255,255,1);\\n  background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,1)), color-stop(100%, rgba(119,119,119,1)));\\n  background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -o-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: linear-gradient(to bottom, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\\\\\\"#ffffff\\\\\\", endColorstr=\\\\\\"#777777\\\\\\", GradientType=0 );\\n \\n      }\\n.hades .tp-thumb-title:after {\\n        content:\\\\\\" \\\\\\";\\n        position:absolute;\\n        left:50%;\\n        margin-left:-8px;\\n        width: 0;\\n\\t\\theight: 0;\\n\\t\\tborder-style: solid;\\n\\t\\tborder-width: 8px 8px 0 8px;\\n\\t\\tborder-color: rgba(0,0,0,0.75) transparent transparent transparent;\\n        bottom:-8px;\\n   }\\n"

											}',

								'settings' => '{"width":{"thumbs":"70","arrows":"160","bullets":"160","tabs":"160"},"height":{"thumbs":"70","arrows":"160","bullets":"160","tabs":"160"}}'

							);				

		$navigations[] = array(

								'id' => 5003,

								'default' => true,

								'name' => 'Ares',

								'handle' => 'preview2',

								'markup' => '{	

											"arrows":"<div class=\\\\\\"tp-title-wrap\\\\\\">\\n\\t<span class=\\\\\\"tp-arr-titleholder\\\\\\">{{title}}<\\/span>\\n <\\/div>\\n",

											"bullets":"<span class=\\\\\\"tp-bullet-title\\\\\\">{{title}}<\\/span>",

											"tabs":"<div class=\\\\\\"tp-tab-content\\\\\\">\\n  <span class=\\\\\\"tp-tab-date\\\\\\">{{param1}}<\/span>\\n  <span class=\\\\\\"tp-tab-title\\\\\\">{{param2}}<\/span>\\n<\/div>\\n<div class=\\\\\\"tp-tab-image\\\\\\"><\/div>"

											}',

								'css' => '{	

											"arrows":".ares.tparrows {\\n\\tcursor:pointer;\\n\\tbackground:#fff;\\n\\tmin-width:60px;\\n    min-height:60px;\\n\\tposition:absolute;\\n\\tdisplay:block;\\n\\tz-index:100;\\n    border-radius:50%;\\n}\\n.ares.tparrows:hover {\\n}\\n.ares.tparrows:before {\\n\\tfont-family: \\\\\\"revicons\\\\\\";\\n\\tfont-size:25px;\\n\\tcolor:#aaa;\\n\\tdisplay:block;\\n\\tline-height: 60px;\\n\\ttext-align: center;\\n    -webkit-transition: color 0.3s;\\n    -moz-transition: color 0.3s;\\n    transition: color 0.3s;\\n    z-index:2;\\n    position:relative;\\n}\\n.ares.tparrows.tp-leftarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e81f\\\\\\";\\n}\\n.ares.tparrows.tp-rightarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e81e\\\\\\";\\n}\\n.ares.tparrows:hover:before {\\n color:#000;\\n      }\\n.tp-title-wrap { \\n  position:absolute;\\n  z-index:1;\\n  display:inline-block;\\n  background:#fff;\\n  min-height:60px;\\n  line-height:60px;\\n  top:0px;\\n  margin-left:30px;\\n  border-radius:0px 30px 30px 0px;\\n  overflow:hidden;\\n  -webkit-transition: -webkit-transform 0.3s;\\n  transition: transform 0.3s;\\n  transform:scaleX(0);  \\n  -webkit-transform:scaleX(0);  \\n  transform-origin:0% 50%; \\n   -webkit-transform-origin:0% 50%;\\n}\\n .ares.tp-rightarrow .tp-title-wrap { \\n   right:0px;\\n   margin-right:30px;margin-left:0px;\\n   -webkit-transform-origin:100% 50%;\\nborder-radius:30px 0px 0px 30px;\\n }\\n.ares.tparrows:hover .tp-title-wrap {\\n\\ttransform:scaleX(1) scaleY(1);\\n  \\t-webkit-transform:scaleX(1) scaleY(1);\\n}\\n.ares .tp-arr-titleholder {\\n  position:relative;\\n  -webkit-transition: -webkit-transform 0.3s;\\n  transition: transform 0.3s;\\n  transform:translateX(200px);  \\n  text-transform:uppercase;\\n  color:#000;\\n  font-weight:400;\\n  font-size:14px;\\n  line-height:60px;\\n  white-space:nowrap;\\n  padding:0px 20px;\\n  margin-left:10px;\\n  opacity:0;\\n}\\n\\n.ares.tp-rightarrow .tp-arr-titleholder {\\n   transform:translateX(-200px); \\n   margin-left:0px; margin-right:10px;\\n      }\\n\\n.ares.tparrows:hover .tp-arr-titleholder {\\n   transform:translateX(0px);\\n   -webkit-transform:translateX(0px);\\n  transition-delay: 0.1s;\\n  opacity:1;\\n}",

											"bullets":".ares.tp-bullets {\\n}\\n.ares.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground:transparent;\\n\\tpadding:10px;\\n\\tmargin-left:-10px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n}\\n.ares .tp-bullet {\\n\\twidth:13px;\\n\\theight:13px;\\n\\tposition:absolute;\\n\\tbackground:#e5e5e5;\\n\\tborder-radius:50%;\\n\\tcursor: pointer;\\n\\tbox-sizing:content-box;\\n}\\n.ares .tp-bullet:hover,\\n.ares .tp-bullet.selected {\\n\\tbackground:#fff;\\n}\\n.ares .tp-bullet-title {\\n  position:absolute;\\n  color:#888;\\n  font-size:12px;\\n  padding:0px 10px;\\n  font-weight:600;\\n  right:27px;\\n  top:-4px;\\n  background:#fff;\\n  background:rgba(255,255,255,0.75);\\n  visibility:hidden;\\n  transform:translateX(-20px);\\n  -webkit-transform:translateX(-20px);\\n  transition:transform 0.3s;\\n  -webkit-transition:transform 0.3s;\\n  line-height:20px;\\n  white-space:nowrap;\\n}     \\n\\n.ares .tp-bullet-title:after {\\n    width: 0px;\\n\\theight: 0px;\\n\\tborder-style: solid;\\n\\tborder-width: 10px 0 10px 10px;\\n\\tborder-color: transparent transparent transparent rgba(255,255,255,0.75);\\n\\tcontent:\\\\\\" \\\\\\";\\n    position:absolute;\\n    right:-10px;\\n\\ttop:0px;\\n}\\n    \\n.ares .tp-bullet:hover .tp-bullet-title{\\n  visibility:visible;\\n   transform:translateX(0px);\\n  -webkit-transform:translateX(0px);\\n}\\n\\n.ares .tp-bullet.selected:hover .tp-bullet-title {\\n    background:#fff;\\n        }\\n.ares .tp-bullet.selected:hover .tp-bullet-title:after {\\n  border-color:transparent transparent transparent #fff;\\n}\\n.ares.tp-bullets:hover .tp-bullet-title {\\n        visibility:hidden;\\n}\\n.ares.tp-bullets:hover .tp-bullet:hover .tp-bullet-title {\\n    visibility:visible;\\n      }",

											"tabs":".ares .tp-tab { \\n  opacity:1;      \\n  padding:10px;\\n  box-sizing:border-box;\\n  font-family: \\\\\\"Roboto\\\\\\", sans-serif;\\n  border-bottom: 1px solid #e5e5e5;\\n }\\n.ares .tp-tab-image \\n{ \\n  width:60px;\\n  height:60px; max-height:100%; max-width:100%;\\n  position:relative;\\n  display:inline-block;\\n  float:left;\\n\\n}\\n.ares .tp-tab-content \\n{\\n    background:rgba(0,0,0,0); \\n    position:relative;\\n    padding:15px 15px 15px 85px;\\n left:0px;\\n overflow:hidden;\\n margin-top:-15px;\\n    box-sizing:border-box;\\n    color:#333;\\n    display: inline-block;\\n    width:100%;\\n    height:100%;\\n position:absolute; }\\n.ares .tp-tab-date\\n  {\\n  display:block;\\n  color: #aaa;\\n  font-weight:500;\\n  font-size:12px;\\n  margin-bottom:0px;\\n  }\\n.ares .tp-tab-title \\n{\\n    display:block;\\t\\n    text-align:left;\\n    color:#333;\\n    font-size:14px;\\n    font-weight:500;\\n    text-transform:none;\\n    line-height:17px;\\n}\\n.ares .tp-tab:hover,\\n.ares .tp-tab.selected {\\n\\tbackground:#eee; \\n}\\n\\n.ares .tp-tab-mask {\\n}\\n\\n\/* MEDIA QUERIES *\/\\n@media only screen and (max-width: 960px) {\\n\\n}\\n@media only screen and (max-width: 768px) {\\n\\n}"

											}',

								'settings' => '{"width":{"tabs":"250"},"height":{"tabs":"80"}}'

							);

		$navigations[] = array(

								'id' => 5004,

								'default' => true,

								'name' => 'Hebe',

								'handle' => 'preview3',

								'markup' => '{

											"arrows":"<div class=\\\\\\"tp-title-wrap\\\\\\">\\n\\t<span class=\\\\\\"tp-arr-titleholder\\\\\\">{{title}}<\\/span>\\n    <span class=\\\\\\"tp-arr-imgholder\\\\\\"><\\/span>\\n <\\/div>\\n",

											"bullets":"<span class=\\\\\\"tp-bullet-image\\\\\\"><\\/span>",

											"tabs":"<div class=\\\\\\"tp-tab-title\\\\\\">{{param1}}<\/div>\\n<div class=\\\\\\"tp-tab-desc\\\\\\">{{title}}<\/div>"

											}',

								'css' => '{

											"arrows":".hebe.tparrows {\\n  cursor:pointer;\\n  background:#fff;\\n  min-width:70px;\\n    min-height:70px;\\n  position:absolute;\\n  display:block;\\n  z-index:100;\\n}\\n.hebe.tparrows:hover {\\n}\\n.hebe.tparrows:before {\\n  font-family: \\\\\\"revicons\\\\\\";\\n  font-size:30px;\\n  color:#aaa;\\n  display:block;\\n  line-height: 70px;\\n  text-align: center;\\n  -webkit-transition: color 0.3s;\\n  -moz-transition: color 0.3s;\\n  transition: color 0.3s;\\n  z-index:2;\\n  position:relative;\\n   background:#fff;\\n  min-width:70px;\\n    min-height:70px;\\n}\\n.hebe.tparrows.tp-leftarrow:before {\\n  content: \\\\\\"\\\\\\\\e824\\\\\\";\\n}\\n.hebe.tparrows.tp-rightarrow:before {\\n  content: \\\\\\"\\\\\\\\e825\\\\\\";\\n}\\n.hebe.tparrows:hover:before {\\n color:#000;\\n      }\\n.tp-title-wrap { \\n  position:absolute;\\n  z-index:0;\\n  display:inline-block;\\n  background:#000;\\n  background:rgba(0,0,0,0.75);\\n  min-height:60px;\\n  line-height:60px;\\n  top:-10px;\\n  margin-left:0px;\\n  -webkit-transition: -webkit-transform 0.3s;\\n  transition: transform 0.3s;\\n  transform:scaleX(0);  \\n  -webkit-transform:scaleX(0);  \\n  transform-origin:0% 50%; \\n   -webkit-transform-origin:0% 50%;\\n}\\n .hebe.tp-rightarrow .tp-title-wrap { \\n   right:0px;\\n   -webkit-transform-origin:100% 50%;\\n }\\n.hebe.tparrows:hover .tp-title-wrap {\\n  transform:scaleX(1);\\n  -webkit-transform:scaleX(1);\\n}\\n.hebe .tp-arr-titleholder {\\n  position:relative;\\n  text-transform:uppercase;\\n  color:#fff;\\n  font-weight:600;\\n  font-size:12px;\\n  line-height:90px;\\n  white-space:nowrap;\\n  padding:0px 20px 0px 90px;\\n}\\n\\n.hebe.tp-rightarrow .tp-arr-titleholder {\\n   margin-left:0px; \\n   padding:0px 90px 0px 20px;\\n }\\n\\n.hebe.tparrows:hover .tp-arr-titleholder {\\n   transform:translateX(0px);\\n   -webkit-transform:translateX(0px);\\n  transition-delay: 0.1s;\\n  opacity:1;\\n}\\n\\n.hebe .tp-arr-imgholder{\\n      width:90px;\\n      height:90px;\\n      position:absolute;\\n      left:100%;\\n      display:block;\\n      background-size:cover;\\n      background-position:center center;\\n  \\t top:0px; right:-90px;\\n    }\\n.hebe.tp-rightarrow .tp-arr-imgholder{\\n        right:auto;left:-90px;\\n      }",

											"bullets":".hebe.tp-bullets {\\n}\\n.hebe.tp-bullets:before {\\n  content:\\\\\\" \\\\\\";\\n  position:absolute;\\n  width:100%;\\n  height:100%;\\n  background:transparent;\\n  padding:10px;\\n  margin-left:-10px;margin-top:-10px;\\n  box-sizing:content-box;\\n}\\n\\n.hebe .tp-bullet {\\n  width:3px;\\n  height:3px;\\n  position:absolute;\\n  background:#fff;  \\n  cursor: pointer;\\n  border:5px solid #222;\\n  border-radius:50%;\\n  box-sizing:content-box;\\n  -webkit-perspective:400;\\n  perspective:400;\\n  -webkit-transform:translateZ(0.01px);\\n  transform:translateZ(0.01px);\\n   transition:all 0.3s;\\n}\\n.hebe .tp-bullet:hover,\\n.hebe .tp-bullet.selected {\\n  background:#222;\\n  border-color:#fff;\\n}\\n\\n.hebe .tp-bullet-image {\\n  position:absolute;\\n  top:-90px; left:-40px;\\n  width:70px;\\n  height:70px;\\n  background-position:center center;\\n  background-size:cover;\\n  visibility:hidden;\\n  opacity:0;\\n  transition:all 0.3s;\\n  -webkit-transform-style:flat;\\n  transform-style:flat;\\n  perspective:600;\\n  -webkit-perspective:600;\\n  transform: scale(0);\\n  -webkit-transform: scale(0);\\n  transform-origin:50% 100%;\\n  -webkit-transform-origin:50% 100%;\\nborder-radius:6px;\\n  \\n  \\n}\\n.hebe .tp-bullet:hover .tp-bullet-image {\\n  display:block;\\n  opacity:1;\\n  transform: scale(1);\\n  -webkit-transform: scale(1);\\n  visibility:visible;\\n    }\\n.hebe .tp-bullet-title {\\n}\\n",

											"tabs":".hebe .tp-tab-title {\\n    color:#a8d8ee;\\n    font-size:13px;\\n    font-weight:700;\\n    text-transform:uppercase;\\n    font-family:\\\\\\"Roboto Slab\\\\\\"\\n    margin-bottom:5px;\\n}\\n\\n.hebe .tp-tab-desc {\\n\\tfont-size:18px;\\n    font-weight:400;\\n    color:#fff;\\n    line-height:25px;\\n\\tfont-family:\\\\\\"Roboto Slab\\\\\\";\\n}\\n"

										}',

								'settings' => '""');



		$navigations[] = array('id' => 5005,'default' => true,'name' => 'Hermes','handle' => 'preview4','markup' => '{"arrows":"<div class=\\\\\\"tp-arr-allwrapper\\\\\\">\\n\\t<div class=\\\\\\"tp-arr-imgholder\\\\\\"><\\/div>\\n\\t<div class=\\\\\\"tp-arr-titleholder\\\\\\">{{title}}<\\/div>\\t\\n<\\/div>","bullets":"","tabs":"<span class=\\\\\\"tp-tab-image\\\\\\"><\\/span>\\n<span class=\\\\\\"tp-tab-content\\\\\\">\\n\\t<span class=\\\\\\"tp-tab-date\\\\\\">{{param1}}<\\/span>\\n\\t<span class=\\\\\\"tp-tab-title\\\\\\">{{param2}}<\\/span>\\n<\\/span>"}','css' => '{"arrows":".hermes.tparrows {\\n\\tcursor:pointer;\\n\\tbackground:#000;\\n\\tbackground:rgba(0,0,0,0.5);\\n\\twidth:30px;\\n\\theight:110px;\\n\\tposition:absolute;\\n\\tdisplay:block;\\n\\tz-index:100;\\n}\\n\\n.hermes.tparrows:before {\\n\\tfont-family: \\\\\\"revicons\\\\\\";\\n\\tfont-size:15px;\\n\\tcolor:#fff;\\n\\tdisplay:block;\\n\\tline-height: 110px;\\n\\ttext-align: center;\\n    transform:translateX(0px);\\n    -webkit-transform:translateX(0px);\\n    transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n}\\n.hermes.tparrows.tp-leftarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e824\\\\\\";\\n}\\n.hermes.tparrows.tp-rightarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e825\\\\\\";\\n}\\n.hermes.tparrows.tp-leftarrow:hover:before {\\n    transform:translateX(-20px);\\n    -webkit-transform:translateX(-20px);\\n     opacity:0;\\n}\\n.hermes.tparrows.tp-rightarrow:hover:before {\\n    transform:translateX(20px);\\n    -webkit-transform:translateX(20px);\\n     opacity:0;\\n}\\n\\n.hermes .tp-arr-allwrapper {\\n    overflow:hidden;\\n    position:absolute;\\n\\twidth:180px;\\n    height:140px;\\n    top:0px;\\n    left:0px;\\n    visibility:hidden;\\n      -webkit-transition: -webkit-transform 0.3s 0.3s;\\n  transition: transform 0.3s 0.3s;\\n  -webkit-perspective: 1000px;\\n  perspective: 1000px;\\n    }\\n.hermes.tp-rightarrow .tp-arr-allwrapper {\\n   right:0px;left:auto;\\n      }\\n.hermes.tparrows:hover .tp-arr-allwrapper {\\n   visibility:visible;\\n          }\\n.hermes .tp-arr-imgholder {\\n  width:180px;position:absolute;\\n  left:0px;top:0px;height:110px;\\n  transform:translateX(-180px);\\n  -webkit-transform:translateX(-180px);\\n  transition:all 0.3s;\\n  transition-delay:0.3s;\\n}\\n.hermes.tp-rightarrow .tp-arr-imgholder{\\n    transform:translateX(180px);\\n  -webkit-transform:translateX(180px);\\n      }\\n  \\n.hermes.tparrows:hover .tp-arr-imgholder {\\n   transform:translateX(0px);\\n   -webkit-transform:translateX(0px);            \\n}\\n.hermes .tp-arr-titleholder {\\n  top:110px;\\n  width:180px;\\n  text-align:left; \\n  display:block;\\n  padding:0px 10px;\\n  line-height:30px; background:#000;\\n  background:rgba(0,0,0,0.75);color:#fff;\\n  font-weight:600; position:absolute;\\n  font-size:12px;\\n  white-space:nowrap;\\n  letter-spacing:1px;\\n  -webkit-transition: all 0.3s;\\n  transition: all 0.3s;\\n  -webkit-transform: rotateX(-90deg);\\n  transform: rotateX(-90deg);\\n  -webkit-transform-origin: 50% 0;\\n  transform-origin: 50% 0;\\n  box-sizing:border-box;\\n\\n}\\n.hermes.tparrows:hover .tp-arr-titleholder {\\n    -webkit-transition-delay: 0.6s;\\n  transition-delay: 0.6s;\\n  -webkit-transform: rotateX(0deg);\\n  transform: rotateX(0deg);\\n}\\n","bullets":".hermes.tp-bullets {\\n}\\n\\n.hermes .tp-bullet {\\n    overflow:hidden;\\n    border-radius:50%;\\n    width:16px;\\n    height:16px;\\n    background-color: rgba(0, 0, 0, 0);\\n    box-shadow: inset 0 0 0 2px #FFF;\\n    -webkit-transition: background 0.3s ease;\\n    transition: background 0.3s ease;\\n    position:absolute;\\n}\\n\\n.hermes .tp-bullet:hover {\\n\\t  background-color: rgba(0, 0, 0, 0.2);\\n}\\n.hermes .tp-bullet:after {\\n  content: \\\\\' \\\\\';\\n  position: absolute;\\n  bottom: 0;\\n  height: 0;\\n  left: 0;\\n  width: 100%;\\n  background-color: #FFF;\\n  box-shadow: 0 0 1px #FFF;\\n  -webkit-transition: height 0.3s ease;\\n  transition: height 0.3s ease;\\n}\\n.hermes .tp-bullet.selected:after {\\n  height:100%;\\n}\\n","tabs":".hermes .tp-tab { \\n  opacity:1;  \\n  padding-right:10px;\\n  box-sizing:border-box;\\n }\\n.hermes .tp-tab-image \\n{ \\n  width:100%;\\n  height:60%;\\n  position:relative;\\n}\\n.hermes .tp-tab-content \\n{\\n    background:rgb(54,54,54); \\n    position:absolute;\\n    padding:20px 20px 20px 30px;\\n    box-sizing:border-box;\\n    color:#fff;\\n  display:block;\\n  width:100%;\\n  min-height:40%;\\n  bottom:0px;\\n  left:-10px;\\n  }\\n.hermes .tp-tab-date\\n  {\\n  display:block;\\n  color:#888;\\n  font-weight:600;\\n  font-size:12px;\\n  margin-bottom:10px;\\n  }\\n.hermes .tp-tab-title \\n{\\n    display:block;\\t\\n    color:#fff;\\n    font-size:16px;\\n    font-weight:800;\\n    text-transform:uppercase;\\n   line-height:19px;\\n}\\n\\n.hermes .tp-tab.selected .tp-tab-title:after {\\n    width: 0px;\\n\\theight: 0px;\\n\\tborder-style: solid;\\n\\tborder-width: 30px 0 30px 10px;\\n\\tborder-color: transparent transparent transparent rgb(54,54,54);\\n\\tcontent:\\\\\\" \\\\\\";\\n    position:absolute;\\n    right:-9px;\\n    bottom:50%;\\n    margin-bottom:-30px;\\n}\\n.hermes .tp-tab-mask {\\n     padding-right:10px !important;\\n          }\\n\\n\\/* MEDIA QUERIES *\\/\\n@media only screen and (max-width: 960px) {\\n  .hermes .tp-tab .tp-tab-title {font-size:14px;line-height:16px;}\\n  .hermes .tp-tab-date { font-size:11px; line-height:13px;margin-bottom:10px;}\\n  .hermes .tp-tab-content { padding:15px 15px 15px 25px;}\\n}\\n@media only screen and (max-width: 768px) {\\n  .hermes .tp-tab .tp-tab-title {font-size:12px;line-height:14px;}\\n  .hermes .tp-tab-date {font-size:10px; line-height:12px;margin-bottom:5px;}\\n  .hermes .tp-tab-content {padding:10px 10px 10px 20px;}\\n}"}','settings' => '{"width":{"thumbs":"160","arrows":"160","bullets":"160","tabs":"240"},"height":{"thumbs":"160","arrows":"160","bullets":"160","tabs":"260"}}');

		$navigations[] = array('id' => 5006,'default' => true,'name' => 'Custom','handle' => 'custom','markup' => '{"thumbs":"<span class=\\\\\\"tp-thumb-image\\\\\\"><\\/span>","arrows":"","bullets":"","tabs":"<span class=\\\\\\"tp-tab-image\\\\\\"><\\/span>"}','css' => '{"thumbs":"","arrows":".custom.tparrows {\\n\\tcursor:pointer;\\n\\tbackground:#000;\\n\\tbackground:rgba(0,0,0,0.5);\\n\\twidth:40px;\\n\\theight:40px;\\n\\tposition:absolute;\\n\\tdisplay:block;\\n\\tz-index:100;\\n}\\n.custom.tparrows:hover {\\n\\tbackground:#000;\\n}\\n.custom.tparrows:before {\\n\\tfont-family: \\\\\\"revicons\\\\\\";\\n\\tfont-size:15px;\\n\\tcolor:#fff;\\n\\tdisplay:block;\\n\\tline-height: 40px;\\n\\ttext-align: center;\\n}\\n.custom.tparrows.tp-leftarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e824\\\\\\";\\n}\\n.custom.tparrows.tp-rightarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e825\\\\\\";\\n}\\n\\n","bullets":".custom.tp-bullets {\\n}\\n.custom.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground:transparent;\\n\\tpadding:10px;\\n\\tmargin-left:-10px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n}\\n.custom .tp-bullet {\\n\\twidth:12px;\\n\\theight:12px;\\n\\tposition:absolute;\\n\\tbackground:#aaa;\\n    background:rgba(125,125,125,0.5);\\n\\tcursor: pointer;\\n\\tbox-sizing:content-box;\\n}\\n.custom .tp-bullet:hover,\\n.custom .tp-bullet.selected {\\n\\tbackground:rgb(125,125,125);\\n}\\n.custom .tp-bullet-image {\\n}\\n.custom .tp-bullet-title {\\n}\\n","tabs":""}','settings' => '""');

		$navigations[] = array('id' => 5007,'default' => true,'name' => 'Hephaistos','handle' => 'round-old','markup' => '{"arrows":"","bullets":""}','css' => '{"arrows":".hephaistos.tparrows {\\n\\tcursor:pointer;\\n\\tbackground:#000;\\n\\tbackground:rgba(0,0,0,0.5);\\n\\twidth:40px;\\n\\theight:40px;\\n\\tposition:absolute;\\n\\tdisplay:block;\\n\\tz-index:100;\\n    border-radius:50%;\\n}\\n.hephaistos.tparrows:hover {\\n\\tbackground:#000;\\n}\\n.hephaistos.tparrows:before {\\n\\tfont-family: \\\\\\"revicons\\\\\\";\\n\\tfont-size:18px;\\n\\tcolor:#fff;\\n\\tdisplay:block;\\n\\tline-height: 40px;\\n\\ttext-align: center;\\n}\\n.hephaistos.tparrows.tp-leftarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e82c\\\\\\";\\n  margin-left:-2px;\\n  \\n}\\n.hephaistos.tparrows.tp-rightarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e82d\\\\\\";\\n   margin-right:-2px;\\n}\\n\\n","bullets":".hephaistos.tp-bullets {\\n}\\n.hephaistos.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground:transparent;\\n\\tpadding:10px;\\n\\tmargin-left:-10px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n}\\n.hephaistos .tp-bullet {\\n\\twidth:12px;\\n\\theight:12px;\\n\\tposition:absolute;\\n\\tbackground:#999;\\n\\tborder:3px solid #f5f5f5;\\n\\tborder-radius:50%;\\n\\tcursor: pointer;\\n\\tbox-sizing:content-box;\\n  box-shadow: 0px 0px 2px 1px rgba(130,130,130, 0.3);\\n\\n}\\n.hephaistos .tp-bullet:hover,\\n.hephaistos .tp-bullet.selected {\\n\\tbackground:#fff;\\n    border-color:#000;\\n}\\n.hephaistos .tp-bullet-image {\\n}\\n.hephaistos .tp-bullet-title {\\n}\\n"}','settings' => '{"width":{"thumbs":"160","arrows":"160","bullets":"161","tabs":"160"},"height":{"thumbs":"160","arrows":"160","bullets":"159","tabs":"160"}}');

		$navigations[] = array('id' => 5008,'default' => true,'name' => 'Persephone','handle' => 'square-old','markup' => '{"arrows":"","bullets":""}','css' => '{"arrows":".persephone.tparrows {\\n\\tcursor:pointer;\\n\\tbackground:#aaa;\\n\\tbackground:rgba(200,200,200,0.5);\\n\\twidth:40px;\\n\\theight:40px;\\n\\tposition:absolute;\\n\\tdisplay:block;\\n\\tz-index:100;\\n  border:1px solid #f5f5f5;\\n}\\n.persephone.tparrows:hover {\\n\\tbackground:#333;\\n}\\n.persephone.tparrows:before {\\n\\tfont-family: \\\\\\"revicons\\\\\\";\\n\\tfont-size:15px;\\n\\tcolor:#fff;\\n\\tdisplay:block;\\n\\tline-height: 40px;\\n\\ttext-align: center;\\n}\\n.persephone.tparrows.tp-leftarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e824\\\\\\";\\n}\\n.persephone.tparrows.tp-rightarrow:before {\\n\\tcontent: \\\\\\"\\\\\\\\e825\\\\\\";\\n}\\n\\n","bullets":".persephone.tp-bullets {\\n}\\n.persephone.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground:#transparent;\\n\\tpadding:10px;\\n\\tmargin-left:-10px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n}\\n.persephone .tp-bullet {\\n\\twidth:12px;\\n\\theight:12px;\\n\\tposition:absolute;\\n\\tbackground:#aaa;\\n\\tborder:1px solid #e5e5e5;\\t\\n\\tcursor: pointer;\\n\\tbox-sizing:content-box;\\n}\\n.persephone .tp-bullet:hover,\\n.persephone .tp-bullet.selected {\\n\\tbackground:#222;\\n}\\n.persephone .tp-bullet-image {\\n}\\n.persephone .tp-bullet-title {\\n}\\n"}','settings' => '""');

		

		$navigations[] = array(

								'id' => 5009,

								'default' => true,

								'name' => 'Erinyen',

								'handle' => 'navbar-old',

								'markup' => '{

											"arrows":"<div class=\\\\\\"tp-title-wrap\\\\\\">\\n  \\t<div class=\\\\\\"tp-arr-imgholder\\\\\\"><\/div>\\n    <div class=\\\\\\"tp-arr-img-over\\\\\\"><\/div>\\n\\t<span class=\\\\\\"tp-arr-titleholder\\\\\\">{{title}}<\/span>\\n <\/div>\\n\\n",

											"bullets":"",

											"tabs":"<div class=\\\\\\"tp-tab-title\\\\\\">{{title}}<\/div>\\n<div class=\\\\\\"tp-tab-desc\\\\\\">{{description}}<\/div>",

											"thumbs":"<span class=\\\\\\"tp-thumb-over\\\\\\"><\/span>\\n<span class=\\\\\\"tp-thumb-image\\\\\\"><\/span>\\n<span class=\\\\\\"tp-thumb-title\\\\\\">{{title}}<\/span>\\n<span class=\\\\\\"tp-thumb-more\\\\\\"><\/span>"

											}',

								'css' => '{

											"arrows":".erinyen.tparrows {\\n  cursor:pointer;\\n  background:#000;\\n  background:rgba(0,0,0,0.5);\\n  min-width:70px;\\n  min-height:70px;\\n  position:absolute;\\n  display:block;\\n  z-index:100;\\n  border-radius:35px;   \\n}\\n\\n.erinyen.tparrows:before {\\n  font-family: \\\\\\"revicons\\\\\\";\\n  font-size:20px;\\n  color:#fff;\\n  display:block;\\n  line-height: 70px;\\n  text-align: center;    \\n  z-index:2;\\n  position:relative;\\n}\\n.erinyen.tparrows.tp-leftarrow:before {\\n  content: \\\\\\"\\\\\\\e824\\\\\\";\\n}\\n.erinyen.tparrows.tp-rightarrow:before {\\n  content: \\\\\\"\\\\\\\e825\\\\\\";\\n}\\n\\n.erinyen .tp-title-wrap { \\n  position:absolute;\\n  z-index:1;\\n  display:inline-block;\\n  background:#000;\\n  background:rgba(0,0,0,0.5);\\n  min-height:70px;\\n  line-height:70px;\\n  top:0px;\\n  margin-left:0px;\\n  border-radius:35px;\\n  overflow:hidden; \\n  transition: opacity 0.3s;\\n  -webkit-transition:opacity 0.3s;\\n  -moz-transition:opacity 0.3s;\\n  -webkit-transform: scale(0);\\n  -moz-transform: scale(0);\\n  transform: scale(0);  \\n  visibility:hidden;\\n  opacity:0;\\n}\\n\\n.erinyen.tparrows:hover .tp-title-wrap{\\n  -webkit-transform: scale(1);\\n  -moz-transform: scale(1);\\n  transform: scale(1);\\n  opacity:1;\\n  visibility:visible;\\n}\\n        \\n .erinyen.tp-rightarrow .tp-title-wrap { \\n   right:0px;\\n   margin-right:0px;margin-left:0px;\\n   -webkit-transform-origin:100% 50%;\\n  border-radius:35px;\\n  padding-right:20px;\\n  padding-left:10px;\\n }\\n\\n\\n.erinyen.tp-leftarrow .tp-title-wrap { \\n   padding-left:20px;\\n  padding-right:10px;\\n}\\n\\n.erinyen .tp-arr-titleholder {\\n  letter-spacing: 3px;\\n   position:relative;\\n  -webkit-transition: -webkit-transform 0.3s;\\n  transition: transform 0.3s;\\n  transform:translateX(200px);  \\n  text-transform:uppercase;\\n  color:#fff;\\n  font-weight:600;\\n  font-size:13px;\\n  line-height:70px;\\n  white-space:nowrap;\\n  padding:0px 20px;\\n  margin-left:11px;\\n  opacity:0;  \\n}\\n\\n.erinyen .tp-arr-imgholder {\\n  width:100%;\\n  height:100%;\\n  position:absolute;\\n  top:0px;\\n  left:0px;\\n  background-position:center center;\\n  background-size:cover;\\n    }\\n .erinyen .tp-arr-img-over {\\n   width:100%;\\n  height:100%;\\n  position:absolute;\\n  top:0px;\\n  left:0px;\\n   background:#000;\\n   background:rgba(0,0,0,0.5);\\n        }\\n.erinyen.tp-rightarrow .tp-arr-titleholder {\\n   transform:translateX(-200px); \\n   margin-left:0px; margin-right:11px;\\n      }\\n\\n.erinyen.tparrows:hover .tp-arr-titleholder {\\n   transform:translateX(0px);\\n   -webkit-transform:translateX(0px);\\n  transition-delay: 0.1s;\\n  opacity:1;\\n}",

											"bullets":".erinyen.tp-bullets {\\n}\\n.erinyen.tp-bullets:before {\\n\\tcontent:\\\\\\" \\\\\\";\\n\\tposition:absolute;\\n\\twidth:100%;\\n\\theight:100%;\\n\\tbackground: #555555; \/* old browsers *\/\\n    background: -moz-linear-gradient(top,  #555555 0%, #222222 100%); \/* ff3.6+ *\/\\n    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#555555), color-stop(100%,#222222)); \/* chrome,safari4+ *\/\\n    background: -webkit-linear-gradient(top,  #555555 0%,#222222 100%); \/* chrome10+,safari5.1+ *\/\\n    background: -o-linear-gradient(top,  #555555 0%,#222222 100%); \/* opera 11.10+ *\/\\n    background: -ms-linear-gradient(top,  #555555 0%,#222222 100%); \/* ie10+ *\/\\n    background: linear-gradient(to bottom,  #555555 0%,#222222 100%); \/* w3c *\/\\n    filter: progid:dximagetransform.microsoft.gradient( startcolorstr=\\\\\\"#555555\\\\\\", endcolorstr=\\\\\\"#222222\\\\\\",gradienttype=0 ); \/* ie6-9 *\/\\n\\tpadding:10px 15px;\\n\\tmargin-left:-15px;margin-top:-10px;\\n\\tbox-sizing:content-box;\\n   border-radius:10px;\\n   box-shadow:0px 0px 2px 1px rgba(33,33,33,0.3);\\n}\\n.erinyen .tp-bullet {\\n\\twidth:13px;\\n\\theight:13px;\\n\\tposition:absolute;\\n\\tbackground:#111;\\t\\n\\tborder-radius:50%;\\n\\tcursor: pointer;\\n\\tbox-sizing:content-box;\\n}\\n.erinyen .tp-bullet:hover,\\n.erinyen .tp-bullet.selected {\\n\\tbackground: #e5e5e5; \/* old browsers *\/\\nbackground: -moz-linear-gradient(top,  #e5e5e5 0%, #999999 100%); \/* ff3.6+ *\/\\nbackground: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#e5e5e5), color-stop(100%,#999999)); \/* chrome,safari4+ *\/\\nbackground: -webkit-linear-gradient(top,  #e5e5e5 0%,#999999 100%); \/* chrome10+,safari5.1+ *\/\\nbackground: -o-linear-gradient(top,  #e5e5e5 0%,#999999 100%); \/* opera 11.10+ *\/\\nbackground: -ms-linear-gradient(top,  #e5e5e5 0%,#999999 100%); \/* ie10+ *\/\\nbackground: linear-gradient(to bottom,  #e5e5e5 0%,#999999 100%); \/* w3c *\/\\nfilter: progid:dximagetransform.microsoft.gradient( startcolorstr=\\\\\\"#e5e5e5\\\\\\", endcolorstr=\\\\\\"#999999\\\\\\",gradienttype=0 ); \/* ie6-9 *\/\\n  border:1px solid #555;\\n  width:12px;height:12px;\\n}\\n.erinyen .tp-bullet-image {\\n}\\n.erinyen .tp-bullet-title {\\n}\\n",

											"tabs":".erinyen .tp-tab-title {\\n    color:#a8d8ee;\\n    font-size:13px;\\n    font-weight:700;\\n    text-transform:uppercase;\\n    font-family:\\\\\\"Roboto Slab\\\\\\"\\n    margin-bottom:5px;\\n}\\n\\n.erinyen .tp-tab-desc {\\n\\tfont-size:18px;\\n    font-weight:400;\\n    color:#fff;\\n    line-height:25px;\\n\\tfont-family:\\\\\\"Roboto Slab\\\\\\";\\n}\\n      ",

											"thumbs":".erinyen .tp-thumb {\\nopacity:1\\n}\\n\\n.erinyen .tp-thumb-over {\\n  background:#000;\\n  background:rgba(0,0,0,0.25);\\n  width:100%;\\n  height:100%;\\n  position:absolute;\\n  top:0px;\\n  left:0px;\\n  z-index:1;\\n  -webkit-transition:all 0.3s;\\n  transition:all 0.3s;\\n}\\n\\n.erinyen .tp-thumb-more:before {\\n  font-family: \\\\\\"revicons\\\\\\";\\n  font-size:12px;\\n  color:#aaa;\\n  color:rgba(255,255,255,0.75);\\n  display:block;\\n  line-height: 12px;\\n  text-align: left;    \\n  z-index:2;\\n  position:absolute;\\n  top:20px;\\n  right:20px;\\n  z-index:2;\\n}\\n.erinyen .tp-thumb-more:before {\\n  content: \\\\\\"\\\\\\\e825\\\\\\";\\n}\\n\\n.erinyen .tp-thumb-title {\\n  font-family:\\\\\\"Raleway\\\\\\";\\n  letter-spacing:1px;\\n  font-size:12px;\\n  color:#fff;\\n  display:block;\\n  line-height: 15px;\\n  text-align: left;    \\n  z-index:2;\\n  position:absolute;\\n  top:0px;\\n  left:0px;\\n  z-index:2;\\n  padding:20px 35px 20px 20px;\\n  width:100%;\\n  height:100%;\\n  box-sizing:border-box;\\n  transition:all 0.3s;\\n  -webkit-transition:all 0.3s;\\n  font-weight:500;\\n}\\n\\n.erinyen .tp-thumb.selected .tp-thumb-more:before,\\n.erinyen .tp-thumb:hover .tp-thumb-more:before {\\n color:#aaa;\\n\\n}\\n\\n.erinyen .tp-thumb.selected .tp-thumb-over,\\n.erinyen .tp-thumb:hover .tp-thumb-over {\\n background:#fff;\\n}\\n.erinyen .tp-thumb.selected .tp-thumb-title,\\n.erinyen .tp-thumb:hover .tp-thumb-title {\\n  color:#000;\\n\\n}\\n"

										}',

								'settings' => '{"width":{"thumbs":"200","arrows":"160","bullets":"160","tabs":"160"},"height":{"thumbs":"130","arrows":"160","bullets":"160","tabs":"160"}}');

		

		$navigations[] = array('id' => 5010,'default' => true,'name' => 'Zeus','handle' => 'zeus','markup' => '{"bullets":"<span class=\\\\\\"tp-bullet-image\\\\\\"><\/span>\\n<span class=\\\\\\"tp-bullet-imageoverlay\\\\\\"><\/span>\\n<span class=\\\\\\"tp-bullet-title\\\\\\">{{title}}<\/span>","arrows":"<div class=\\\\\\"tp-title-wrap\\\\\\">\\n  \\t<div class=\\\\\\"tp-arr-imgholder\\\\\\"><\/div>\\n <\/div>\\n","thumbs":"<span class=\\\\\\"tp-thumb-over\\\\\\"><\/span>\\n<span class=\\\\\\"tp-thumb-image\\\\\\"><\/span>\\n<span class=\\\\\\"tp-thumb-title\\\\\\">{{title}}<\/span>\\n<span class=\\\\\\"tp-thumb-more\\\\\\"><\/span>","tabs":"<span class=\\\\\\"tp-tab-title\\\\\\">{{title}}<\/span>"}','css' => '{"bullets":".zeus .tp-bullet {\\n     box-sizing:content-box; -webkit-box-sizing:content-box; border-radius:50%;\\n      background-color: rgba(0, 0, 0, 0);\\n      -webkit-transition: opacity 0.3s ease;\\n      transition: opacity 0.3s ease;\\n\\t  width:13px;height:13px;\\n\\t  border:2px solid #fff;\\n }\\n.zeus .tp-bullet:after {\\n  content: \\"\\";\\n  position: absolute;\\n  width: 100%;\\n  height: 100%;\\n  left: 0;\\n  border-radius: 50%;\\n  background-color: #FFF;\\n  -webkit-transform: scale(0);\\n  transform: scale(0);\\n  -webkit-transform-origin: 50% 50%;\\n  transform-origin: 50% 50%;\\n  -webkit-transition: -webkit-transform 0.3s ease;\\n  transition: transform 0.3s ease;\\n}\\n.zeus .tp-bullet:hover:after,\\n.zeus .tp-bullet.selected:after{\\n    -webkit-transform: scale(1.2);\\n  transform: scale(1.2);\\n}\\n  \\n .zeus .tp-bullet-image,\\n .zeus .tp-bullet-imageoverlay{\\n        width:135px;\\n        height:60px;\\n        position:absolute;\\n        background:#000;\\n        background:rgba(0,0,0,0.5);\\n        bottom:25px;\\n        left:50%;\\n        margin-left:-65px;\\n        box-sizing:border-box;\\n        background-size:cover;\\n        background-position:center center;\\n        visibility:hidden;\\n        opacity:0;\\n         -webkit-backface-visibility: hidden; \\n      \\tbackface-visibility: hidden;\\n        -webkit-transform-origin: 50% 50%;\\n\\t\\ttransform-origin: 50% 50%;\\n  \\t\\t-webkit-transition: all 0.3s ease;\\n  \\t\\ttransition: all 0.3s ease;\\n        border-radius:4px;\\n\\n}\\n          \\n\\n.zeus .tp-bullet-title,\\n.zeus .tp-bullet-imageoverlay {\\n        z-index:2;\\n        -webkit-transition: all 0.5s ease;\\n\\t  \\ttransition: all 0.5s ease;\\n}     \\n.zeus .tp-bullet-title { \\n        color:#fff;\\n        text-align:center;\\n        line-height:15px;\\n        font-size:13px;\\n        font-weight:600;  \\n        z-index:3;\\n         visibility:hidden;\\n        opacity:0;\\n         -webkit-backface-visibility: hidden; \\n      \\tbackface-visibility: hidden;\\n        -webkit-transform-origin: 50% 50%;\\n\\t\\ttransform-origin: 50% 50%;\\n  \\t\\t-webkit-transition: all 0.3s ease;\\n  \\t\\ttransition: all 0.3s ease;\\n        position:absolute;\\n        bottom:45px;\\n        width:135px;\\n    \\tvertical-align:middle;\\n        left:-57px;\\n}\\n      \\n.zeus .tp-bullet:hover .tp-bullet-title,\\n.zeus .tp-bullet:hover .tp-bullet-image,\\n.zeus .tp-bullet:hover .tp-bullet-imageoverlay{\\n      opacity:1;\\n      visibility:visible;\\n\\t  -webkit-transform:translateY(0px);\\n      transform:translateY(0px);         \\n    }","arrows":".zeus.tparrows {\\n  cursor:pointer;\\n  min-width:70px;\\n  min-height:70px;\\n  position:absolute;\\n  display:block;\\n  z-index:100;\\n  border-radius:35px;   \\n  overflow:hidden;\\n  background:rgba(0,0,0,0.10);\\n}\\n\\n.zeus.tparrows:before {\\n  font-family: \\\\\\"revicons\\\\\\";\\n  font-size:20px;\\n  color:#fff;\\n  display:block;\\n  line-height: 70px;\\n  text-align: center;    \\n  z-index:2;\\n  position:relative;\\n}\\n.zeus.tparrows.tp-leftarrow:before {\\n  content: \\\\\\"\\\\\\\e824\\\\\\";\\n}\\n.zeus.tparrows.tp-rightarrow:before {\\n  content: \\\\\\"\\\\\\\e825\\\\\\";\\n}\\n\\n.zeus .tp-title-wrap {\\n  background:#000;\\n  background:rgba(0,0,0,0.5);\\n  width:100%;\\n  height:100%;\\n  top:0px;\\n  left:0px;\\n  position:absolute;\\n  opacity:0;\\n  transform:scale(0);\\n  -webkit-transform:scale(0);\\n   transition: all 0.3s;\\n  -webkit-transition:all 0.3s;\\n  -moz-transition:all 0.3s;\\n   border-radius:50%;\\n }\\n.zeus .tp-arr-imgholder {\\n  width:100%;\\n  height:100%;\\n  position:absolute;\\n  top:0px;\\n  left:0px;\\n  background-position:center center;\\n  background-size:cover;\\n  border-radius:50%;\\n  transform:translateX(-100%);\\n  -webkit-transform:translateX(-100%);\\n   transition: all 0.3s;\\n  -webkit-transition:all 0.3s;\\n  -moz-transition:all 0.3s;\\n\\n }\\n.zeus.tp-rightarrow .tp-arr-imgholder {\\n    transform:translateX(100%);\\n  -webkit-transform:translateX(100%);\\n      }\\n.zeus.tparrows:hover .tp-arr-imgholder {\\n  transform:translateX(0);\\n  -webkit-transform:translateX(0);\\n  opacity:1;\\n}\\n      \\n.zeus.tparrows:hover .tp-title-wrap {\\n  transform:scale(1);\\n  -webkit-transform:scale(1);\\n  opacity:1;\\n}\\n ","thumbs":".zeus .tp-thumb {\\nopacity:1\\n}\\n\\n.zeus .tp-thumb-over {\\n  background:#000;\\n  background:rgba(0,0,0,0.25);\\n  width:100%;\\n  height:100%;\\n  position:absolute;\\n  top:0px;\\n  left:0px;\\n  z-index:1;\\n  -webkit-transition:all 0.3s;\\n  transition:all 0.3s;\\n}\\n\\n.zeus .tp-thumb-more:before {\\n  font-family: \\\\\\"revicons\\\\\\";\\n  font-size:12px;\\n  color:#aaa;\\n  color:rgba(255,255,255,0.75);\\n  display:block;\\n  line-height: 12px;\\n  text-align: left;    \\n  z-index:2;\\n  position:absolute;\\n  top:20px;\\n  right:20px;\\n  z-index:2;\\n}\\n.zeus .tp-thumb-more:before {\\n  content: \\\\\\"\\\\\\\e825\\\\\\";\\n}\\n\\n.zeus .tp-thumb-title {\\n  font-family:\\\\\\"Raleway\\\\\\";\\n  letter-spacing:1px;\\n  font-size:12px;\\n  color:#fff;\\n  display:block;\\n  line-height: 15px;\\n  text-align: left;    \\n  z-index:2;\\n  position:absolute;\\n  top:0px;\\n  left:0px;\\n  z-index:2;\\n  padding:20px 35px 20px 20px;\\n  width:100%;\\n  height:100%;\\n  box-sizing:border-box;\\n  transition:all 0.3s;\\n  -webkit-transition:all 0.3s;\\n  font-weight:500;\\n}\\n\\n.zeus .tp-thumb.selected .tp-thumb-more:before,\\n.zeus .tp-thumb:hover .tp-thumb-more:before {\\n color:#aaa;\\n\\n}\\n\\n.zeus .tp-thumb.selected .tp-thumb-over,\\n.zeus .tp-thumb:hover .tp-thumb-over {\\n background:#000;\\n}\\n.zeus .tp-thumb.selected .tp-thumb-title,\\n.zeus .tp-thumb:hover .tp-thumb-title {\\n  color:#fff;\\n\\n}\\n","tabs":".zeus .tp-tab { \\n  opacity:1;      \\n  box-sizing:border-box;\\n}\\n\\n.zeus .tp-tab-title { \\ndisplay: block;\\ntext-align: center;\\nbackground: rgba(0,0,0,0.25);\\nfont-family: \\\\\\"Roboto Slab\\\\\\", serif; \\nfont-weight: 700; \\nfont-size: 13px; \\nline-height: 13px;\\ncolor: #fff; \\npadding: 9px 10px; }\\n\\n.zeus .tp-tab:hover .tp-tab-title,\\n.zeus .tp-tab.selected .tp-tab-title {\\n color: #000;\\n  background:rgba(255,255,255,1); \\n}"}','settings' => '{"width":{"thumbs":"200","arrows":"160","bullets":"160","tabs":"160"},"height":{"thumbs":"130","arrows":"160","bullets":"160","tabs":"31"}}');

		$navigations[] = array(

								'id' => 5011,

								'default' => true,

								'name' => 'Metis',

								'handle' => 'metis',

								'markup' => '{

												"bullets":"<span class=\\\\\\"tp-bullet-img-wrap\\\\\\">\\n  <span class=\\\\\\"tp-bullet-image\\\\\\"><\/span>\\n<\/span>\\n<span class=\\\\\\"tp-bullet-title\\\\\\">{{title}}<\/span>",

												"arrows":"",

												"tabs":"<div class=\\\\\\"tp-tab-wrapper\\\\\\">\\n<div class=\\\\\\"tp-tab-number\\\\\\">{{param1}}</div>\\n<div class=\\\\\\"tp-tab-divider\\\\\\"></div>\\n<div class=\\\\\\"tp-tab-title-mask\\\\\\">\\n<div class=\\\\\\"tp-tab-title\\\\\\">{{title}}</div>\\n</div>\\n</div>"

											}',

								'css' => 	'{

												"bullets":".metis .tp-bullet { \\n    opacity:1;\\n    width:50px;\\n    height:50px;    \\n    padding:3px;\\n    background:#000;\\n    background-color:rgba(0,0,0,0.25);\\n    margin:0px;\\n    box-sizing:border-box;\\n    transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n    border-radius:50%;\\n  }\\n\\n.metis .tp-bullet-image {\\n\\n   border-radius:50%;\\n   display:block;\\n   box-sizing:border-box;\\n   position:relative;\\n    -webkit-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  -moz-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  width:44px;\\n  height:44px;\\n  background-size:cover;\\n  background-position:center center;\\n }  \\n.metis .tp-bullet-title { \\n     position:absolute; \\n\t bottom:65px;\\n     display:inline-block;\\n     left:50%;\\n     background:#000;\\n     background:rgba(0,0,0,0.75);\\n     color:#fff;\\n     padding:10px 30px;\\n     border-radius:4px;\\n\t -webkit-border-radius:4px;\\n     opacity:0;\\n      transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n    transform: translateZ(0.001px) translateX(-50%) translateY(14px);\\n    transform-origin:50% 100%;\\n    -webkit-transform: translateZ(0.001px) translateX(-50%) translateY(14px);\\n    -webkit-transform-origin:50% 100%;\\n    opacity:0;\\n    white-space:nowrap;\\n }\\n\\n.metis .tp-bullet:hover .tp-bullet-title {\\n  \t transform:rotateX(0deg) translateX(-50%);\\n    -webkit-transform:rotateX(0deg) translateX(-50%);\\n    opacity:1;\\n}\\n\\n.metis .tp-bullet.selected,\\n.metis .tp-bullet:hover  {\\n  \\n   background: rgba(255,255,255,1);\\n  background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,1)), color-stop(100%, rgba(119,119,119,1)));\\n  background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -o-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: linear-gradient(to bottom, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\\\\\\"#ffffff\\\\\\", endColorstr=\\\\\\"#777777\\\\\\", GradientType=0 );\\n \\n      }\\n.metis .tp-bullet-title:after {\\n        content:\\\\\\" \\\\\\";\\n        position:absolute;\\n        left:50%;\\n        margin-left:-8px;\\n        width: 0;\\n\t\theight: 0;\\n\t\tborder-style: solid;\\n\t\tborder-width: 8px 8px 0 8px;\\n\t\tborder-color: rgba(0,0,0,0.75) transparent transparent transparent;\\n        bottom:-8px;\\n   }\\n",

												"arrows":".metis.tparrows {\\n  background:#fff;\\n  padding:10px;\\n  transition:all 0.3s;\\n  -webkit-transition:all 0.3s;\\n  width:60px;\\n  height:60px;\\n  box-sizing:border-box;\\n }\\n \\n .metis.tparrows:hover {\\n   background:#fff;\\n   background:rgba(255,255,255,0.75);\\n }\\n \\n .metis.tparrows:before {\\n  color:#000;  \\n   transition:all 0.3s;\\n  -webkit-transition:all 0.3s;\\n }\\n \\n .metis.tparrows:hover:before {\\n   transform:scale(1.5);\\n  }\\n ",

												"tabs":".metis .tp-tab-number {\\n    color:#fff;\\n    font-size:40px;\\n    line-height:30px;\\n    font-weight:400;\\n    font-family: \\\\\\"Playfair Display\\\\\\";\\n    width: 50px;\\n    margin-right: 17px;\\n    display: inline-block;\\n    float: left;\\n}\\n\\n\\n.metis .tp-tab-mask {\\n   padding-left:20px;\\n   left:0px;\\n   max-width:90px !important;\\n   transition:0.4s padding-left, 0.4s left, 0.4s max-width;\\n}\\n\\n.metis:hover .tp-tab-mask{\\n   padding-left:0px;\\n   left:50px;\\n   max-width:500px !important;\\n}\\n\\n.metis .tp-tab-divider { \\n\\tborder-right: 1px solid transparent;\\n    height: 30px;\\n    width: 1px;\\n    margin-top: 5px;\\n    display: inline-block;\\n    float: left;\\n}\\n\\n.metis .tp-tab-title {\\n    color:#fff;\\n    font-size:20px;\\n    line-height:20px;\\n    font-weight:400;\\n    font-family: \\\\\\"Playfair Display\\\\\\";\\n    position:relative;\\n    padding-top: 10px;\\n    padding-left: 30px;\\n    display: inline-block;\\n    transform:translateX(-100%);\\n    transition:0.4s all;\\n}\\n\\n.metis .tp-tab-title-mask {\\n   position:absolute;\\n   overflow:hidden;\\n   left:67px; \\n}\\n\\n.metis:hover .tp-tab-title {\\n   transform:translateX(0);\\n }\\n\\n.metis .tp-tab { \\n\\topacity: 0.15;\\n    transition:0.4s all;\\n}\\n\\n.metis .tp-tab:hover,\\n.metis .tp-tab.selected {\\n    opacity: 1; \\n}\\n\\n.metis .tp-tab.selected .tp-tab-divider {\\n\\tborder-right: 1px solid #cdb083;\\n}\\n\\n.metis.tp-tabs {\\n   max-width:118px !important;\\n   padding-left:50px;\\n}\\n  \\n.metis.tp-tabs:before {\\n content:\\\\\\" \\\\\\";\\n height:100%;\\n width:88px; \\n background:rgba(0,0,0,0.15);\\n border-right:1px solid rgba(255,255,255,0.10);\\n left:0px;\\n top:0px;\\n position:absolute;\\n transition:0.4s all;\\n }\\n \\n .metis.tp-tabs:hover:before{\\n  width:118px;\\n }\\n     \\n @media (max-width:499px){\\n .metis.tp-tabs:before {\\n background:rgba(0,0,0,0.75);\\n }\\n }"

											}',

								'settings' => '{"width":{"thumbs":"200","arrows":"160","bullets":"160","tabs":"300"},"height":{"thumbs":"130","arrows":"160","bullets":"160","tabs":"40"}}');		

		$navigations[] = array('id' => 5012,'default' => true,'name' => 'Dione','handle' => 'dione','markup' => '{"bullets":"<span class=\\\\\\"tp-bullet-img-wrap\\\\\\">\\n  <span class=\\\\\\"tp-bullet-image\\\\\\"><\/span>\\n<\/span>\\n<span class=\\\\\\"tp-bullet-title\\\\\\">{{title}}<\/span>","arrows":"<div class=\\\\\\"tp-arr-imgwrapper\\\\\\">\\n<div class=\\\\\\"tp-arr-imgholder\\\\\\"><\/div>\\n<\/div>"}','css' => '{"bullets":".dione .tp-bullet { \\n    opacity:1;\\n    width:50px;\\n    height:50px;    \\n    padding:3px;\\n    background:#000;\\n    background-color:rgba(0,0,0,0.25);\\n    margin:0px;\\n    box-sizing:border-box;\\n    transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n\\n  }\\n\\n.dione .tp-bullet-image {\\n   display:block;\\n   box-sizing:border-box;\\n   position:relative;\\n    -webkit-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  -moz-box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  box-shadow: inset 5px 5px 10px 0px rgba(0,0,0,0.25);\\n  width:44px;\\n  height:44px;\\n  background-size:cover;\\n  background-position:center center;\\n }  \\n.dione .tp-bullet-title { \\n     position:absolute; \\n   bottom:65px;\\n     display:inline-block;\\n     left:50%;\\n     background:#000;\\n     background:rgba(0,0,0,0.75);\\n     color:#fff;\\n     padding:10px 30px;\\n     border-radius:4px;\\n   -webkit-border-radius:4px;\\n     opacity:0;\\n      transition:all 0.3s;\\n    -webkit-transition:all 0.3s;\\n    transform: translateZ(0.001px) translateX(-50%) translateY(14px);\\n    transform-origin:50% 100%;\\n    -webkit-transform: translateZ(0.001px) translateX(-50%) translateY(14px);\\n    -webkit-transform-origin:50% 100%;\\n    opacity:0;\\n    white-space:nowrap;\\n }\\n\\n.dione .tp-bullet:hover .tp-bullet-title {\\n     transform:rotateX(0deg) translateX(-50%);\\n    -webkit-transform:rotateX(0deg) translateX(-50%);\\n    opacity:1;\\n}\\n\\n.dione .tp-bullet.selected,\\n.dione .tp-bullet:hover  {\\n  \\n   background: rgba(255,255,255,1);\\n  background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,1)), color-stop(100%, rgba(119,119,119,1)));\\n  background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -o-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  background: linear-gradient(to bottom, rgba(255,255,255,1) 0%, rgba(119,119,119,1) 100%);\\n  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\\\\\\"#ffffff\\\\\\", endColorstr=\\\\\\"#777777\\\\\\", GradientType=0 );\\n \\n      }\\n.dione .tp-bullet-title:after {\\n        content:\\\\\\" \\\\\\";\\n        position:absolute;\\n        left:50%;\\n        margin-left:-8px;\\n        width: 0;\\n    height: 0;\\n    border-style: solid;\\n    border-width: 8px 8px 0 8px;\\n    border-color: rgba(0,0,0,0.75) transparent transparent transparent;\\n        bottom:-8px;\\n   }\\n","arrows":".dione.tparrows {\\n  height:100%;\\n  width:100px;\\n  background:transparent;\\n  background:rgba(0,0,0,0);\\n  line-height:100%;\\n  transition:all 0.3s;\\n-webkit-transition:all 0.3s;\\n}\\n\\n.dione.tparrows:hover {\\n background:rgba(0,0,0,0.45);\\n }\\n.dione .tp-arr-imgwrapper {\\n width:100px;\\n left:0px;\\n position:absolute;\\n height:100%;\\n top:0px;\\n overflow:hidden;\\n }\\n.dione.tp-rightarrow .tp-arr-imgwrapper {\\nleft:auto;\\nright:0px;\\n}\\n\\n.dione .tp-arr-imgholder {\\nbackground-position:center center;\\nbackground-size:cover;\\nwidth:100px;\\nheight:100%;\\ntop:0px;\\nvisibility:hidden;\\ntransform:translateX(-50px);\\n-webkit-transform:translateX(-50px);\\ntransition:all 0.3s;\\n-webkit-transition:all 0.3s;\\nopacity:0;\\nleft:0px;\\n}\\n\\n.dione.tparrows.tp-rightarrow .tp-arr-imgholder {\\n  right:0px;\\n  left:auto;\\n  transform:translateX(50px);\\n -webkit-transform:translateX(50px);\\n}\\n\\n.dione.tparrows:before {\\nposition:absolute;\\nline-height:30px;\\nmargin-left:-22px;\\ntop:50%;\\nleft:50%;\\nfont-size:30px;\\nmargin-top:-15px;\\ntransition:all 0.3s;\\n-webkit-transition:all 0.3s;\\n}\\n\\n.dione.tparrows.tp-rightarrow:before {\\nmargin-left:6px;\\n}\\n\\n.dione.tparrows:hover:before {\\n  transform:translateX(-20px);\\n-webkit-transform:translateX(-20px);\\nopacity:0;\\n}\\n\\n.dione.tparrows.tp-rightarrow:hover:before {\\n  transform:translateX(20px);\\n-webkit-transform:translateX(20px);\\n}\\n\\n.dione.tparrows:hover .tp-arr-imgholder {\\n transform:translateX(0px);\\n-webkit-transform:translateX(0px);\\nopacity:1;\\nvisibility:visible;\\n}\\n\\n"}','settings' => '{"width":{"thumbs":"200","arrows":"160","bullets":"160","tabs":"160"},"height":{"thumbs":"130","arrows":"160","bullets":"160","tabs":"31"}}');

		$navigations[] = array('id' => 5013,'default' => true,'name' => 'Uranus','handle' => 'uranus','markup' => '{"arrows":"","bullets":"<span class=\\\\\\"tp-bullet-inner\\\\\\"><\/span>"}','css' => '{"arrows":".uranus.tparrows {\\n  width:50px;\\n  height:50px;\\n  background:transparent;\\n }\\n .uranus.tparrows:before {\\n width:50px;\\n height:50px;\\n line-height:50px;\\n font-size:40px;\\n transition:all 0.3s;\\n-webkit-transition:all 0.3s;\\n }\\n \\n  .uranus.tparrows:hover:before {\\n    opacity:0.75;\\n  }","bullets":".uranus .tp-bullet{\\n\\tborder-radius: 50%;\\n  box-shadow: 0 0 0 2px rgba(255, 255, 255, 0);\\n  -webkit-transition: box-shadow 0.3s ease;\\n  transition: box-shadow 0.3s ease;\\n  background:transparent;\\n}\\n.uranus .tp-bullet.selected,\\n.uranus .tp-bullet:hover {\\n  box-shadow: 0 0 0 2px #FFF;\\n  border:none;\\n  border-radius: 50%;\\n\\n   background:transparent;\\n}\\n\\n\\n\\n.uranus .tp-bullet-inner {\\n  background-color: rgba(255, 255, 255, 0.7);\\n  -webkit-transition: background-color 0.3s ease, -webkit-transform 0.3s ease;\\n  transition: background-color 0.3s ease, transform 0.3s ease;\\n  top: 0;\\n  left: 0;\\n  width: 100%;\\n  height: 100%;\\n  outline: none;\\n  border-radius: 50%;\\n  background-color: #FFF;\\n  background-color: rgba(255, 255, 255, 0.3);\\n  text-indent: -999em;\\n  cursor: pointer;\\n  position: absolute;\\n}\\n\\n.uranus .tp-bullet.selected .tp-bullet-inner,\\n.uranus .tp-bullet:hover .tp-bullet-inner{\\n transform: scale(0.4);\\n -webkit-transform: scale(0.4);\\n background-color:#fff;\\n}"}','settings' => '{"width":{"thumbs":"200","arrows":"160","bullets":"160","tabs":"160"},"height":{"thumbs":"130","arrows":"160","bullets":"160","tabs":"31"}}');

		

		return apply_filters('revslider_mod_default_navigations', $navigations);

	}

	

	

	/**

	 * Translate Navigation for backwards compatibility

	 * @since: 5.0

	 **/

	public static function translate_navigation($handle){

		switch($handle){

			case 'round':

				$handle = 'hesperiden';

			break;

			case 'navbar':

				$handle = 'gyges';

			break;

			case 'preview1':

				$handle = 'hades';

			break;

			case 'preview2':

				$handle = 'ares';

			break;

			case 'preview3':

				$handle = 'hebe';

			break;

			case 'preview4':

				$handle = 'hermes';

			break;

			case 'custom':

				$handle = 'custom';

			break;

			case 'round-old':

				$handle = 'hephaistos';

			break;

			case 'square-old':

				$handle = 'persephone';

			break;

			case 'navbar-old':

				$handle = 'erinyen';

			break;

		}

		

		return $handle;

	}

	

	

	/**

	 * Check if given Navigation is custom, if yes, export it

	 * @since: 5.1.1

	 **/

	public static function export_navigation($nav_handle){

		$navs = self::get_all_navigations(false, true);

		

		if(!is_array($nav_handle)) $nav_handle = array($nav_handle => true);

		

		

		$entries = array();

		if(!empty($nav_handle) && !empty($navs)){

			foreach($nav_handle as $handle => $u){

				foreach($navs as $n => $v){

					if($v['handle'] == $handle){

						$entries[$handle] = $navs[$n];

						break;

					}

				}

			}

			if(!empty($entries)) return $entries;

		}

		

		return false;

	}

}



?>