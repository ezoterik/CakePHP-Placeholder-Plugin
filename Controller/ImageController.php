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

/**
 * Class ImageController
 */
class ImageController extends AppController {

	public $uses = array();

	public $autoRender = false;

	public $components = array('RequestHandler');

	public $image = null;

/**
 * @throws NotFoundException
 */
	public function beforeFilter() {
		if (Configure::read('debug') == 0) {
			throw new NotFoundException();
		}

		if (!in_array($this->request->params['action'], array('png', 'gif', 'jpg'))) {
			die("Cannot Suport format");
		}

		$this->response->expires('+30 days');

		//check size
		if (isset($this->request->pass[0])) {
			$size = $this->request->pass[0];
		} else {
			$size = 100;
		}
		$width = 100;
		$height = 100;
		if (is_numeric($size)) {
			$width = $height = $size;
		} elseif (preg_match('/^(\d+)\D(\d+)$/', $size, $matches)) {
			$width = $matches[1];
			$height = $matches[2];
		}
		$this->image = imagecreate($width, $height) or die("Cannot Initialize new GD image stream");

		//check background color
		if (isset($this->request->pass[2]) && preg_match('/^([\da-f]{2})([\da-f]{2})([\da-f]{2})/i', $this->request->pass[2], $matches)) {
			$red = hexdec($matches[1]);
			$green = hexdec($matches[2]);
			$blue = hexdec($matches[3]);
		} else {
			$red = $green = $blue = 240;
		}
		$bgcolor = imagecolorallocate($this->image, $red, $green, $blue);

		//check text color
		if (isset($this->request->pass[1]) && preg_match('/^([\da-f]{2})([\da-f]{2})([\da-f]{2})/i', $this->request->pass[1], $matches)) {
			$red = hexdec($matches[1]);
			$green = hexdec($matches[2]);
			$blue = hexdec($matches[3]);
		} else {
			$red = $green = $blue = 160;
		}
		$color = imagecolorallocate($this->image, $red, $green, $blue);

		//check text
		if (isset($this->request->query['text'])) {
			$text = $this->request->query['text'];
		} else {
			$text = $width . ' x ' . $height;
		}
		$len = strlen($text);
		$fontWidth = 0;
		$fontHeight = 0;
		for ($fontsize = 5; $fontsize > 0; $fontsize--) {
			$fontWidth = imagefontwidth($fontsize) * $len;
			$fontHeight = imagefontheight($fontsize);
			if ($fontWidth <= $width && $fontHeight <= $height) {
				break;
			}
		}
		imagestring($this->image, $fontsize, ($width - $fontWidth) / 2, ($height - $fontHeight) / 2, $text, $color);

		ob_start();

		parent::beforeFilter();
	}

	public function afterFilter() {
		header("Content-Length: ", ob_get_length());
		ob_end_flush();

		imagedestroy($this->image);
	}

	public function png() {
		$this->RequestHandler->respondAs('image/png');
		imagepng($this->image);
	}

	public function jpg() {
		$this->RequestHandler->respondAs('image/jpeg');
		imagejpeg($this->image);
	}

	public function gif() {
		$this->RequestHandler->respondAs('image/gif');
		imagegif($this->image);
	}
}