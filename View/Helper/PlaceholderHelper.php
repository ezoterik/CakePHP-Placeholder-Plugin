<?php
/**
 * CakePHP Placeholder Plugin
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author  ezoterik <ezoterik.h@gmail.com>
 * @author  ohguma
 * @link https://github.com/ezoterik/CakePHP-Placeholder-Plugin
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 1.0.0
 */

App::uses('AppHelper', 'View/Helper');

/**
 * Class PlaceholderHelper
 *
 * @property HtmlHelper Html
 */
class PlaceholderHelper extends AppHelper {

	public $helpers = array('Html');

/**
 * PNG Placeholder
 *
 * @param string $size    ex) 100, 240x120
 * @param string $color   ex) ff0000(red)
 * @param string $bgcolor ex) 0000ff(blue)
 * @param string $text    text in image
 * @return string
 */
	public function png($size = null, $color = null, $bgcolor = null, $text = null) {
		return $this->image('png', $size, $color, $bgcolor, $text);
	}

/**
 * JPEG Placeholder
 *
 * @param string $size    ex) 100, 240x120
 * @param string $color   ex) ff0000(red)
 * @param string $bgcolor ex) 0000ff(blue)
 * @param string $text    text in image
 * @return string
 */
	public function jpg($size = null, $color = null, $bgcolor = null, $text = null) {
		return $this->image('jpg', $size, $color, $bgcolor, $text);
	}

/**
 * GIF Placeholder
 *
 * @param string $size    ex) 100, 240x120
 * @param string $color   ex) ff0000(red)
 * @param string $bgcolor ex) 0000ff(blue)
 * @param string $text    text in image
 * @return string
 */
	public function gif($size = null, $color = null, $bgcolor = null, $text = null) {
		return $this->image('png', $size, $color, $bgcolor, $text);
	}

/**
 * Creates a formatted IMG element for Placeholder
 *
 * @param string $format  image format(png|jpg|gif)
 * @param string $size    ex) 100, 240x120
 * @param string $color   ex) ff0000(red)
 * @param string $bgcolor ex) 0000ff(blue)
 * @param string $text    text in image
 * @return string
 */
	public function image($format = 'png', $size = null, $color = null, $bgcolor = null, $text = null) {
		if (is_array($size)) {
			extract($size);
		}
		$attr = array();
		$url = '/placeholder/image/' . strtolower($format);
		if (!empty($size)) {
			$url .= '/' . $size;
			if (is_numeric($size)) {
				$attr['width'] = $attr['height'] = $size;
			} elseif (preg_match('/^(\d+)\D(\d+)$/', $size, $ma)) {
				$attr['width'] = $ma[1];
				$attr['height'] = $ma[2];
			}
		}
		if (!empty($color)) {
			$url .= '/' . $color;
		}
		if (!empty($bgcolor)) {
			$url .= '/' . $bgcolor;
		}
		if (!empty($text)) {
			$url .= '?text=' . $text;
			$attr['alt'] = $text;
		}
		return $this->Html->image($url, $attr);
	}
}