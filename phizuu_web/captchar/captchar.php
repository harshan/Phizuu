<?php
/**
 * Captchar - a CAPTCHA implementation in PHP
 * By Richard Ye - http://www.yerich.net/
 *
 * Version 1.2.1 - May 26, 2010
 *
 * The MIT License
 *
 * Copyright (c) 2010 Richard Ye (http://www.yerich.net/)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

//Remove the next two lines if already done.
session_start();
putenv('GDFONTPATH=' . realpath('.'));

class Captchar {
	function error($errorText) {
		$image = imagecreate(300, 30);
		$fontSize = 15;
		$code = $this->generateCode();
		$background_color = imagecolorallocate($image, 0, 0, 0);
		$foreground_color = imagecolorallocate($image, 255, 255, 255);
		header('Content-Type: image/jpeg, Expires: Thu, 1 Jan 1990 00:00:00 GMT');
		imagettftext($image, $fontSize, 0, 2, 25, $foreground_color, "fonta.ttf", $code);
		imagettftext($image, 6, 0, 2, 8, $foreground_color, "font.ttf", 'Error: '.$errorText);
		imagejpeg($image, null, 75);
		imagedestroy($image);
		$_SESSION['captcha'] = $code;
		die();
	}

	function generateCode($characters = 10, $method = "bigram") {
		if($method == "random") {
			/* list all possible characters, similar looking characters and vowels have been removed */
			$possible = '2345678bcdfghjkmnprsvwyz';
			$code = '';
			$i = 0;
			while ($i < $characters) {
				$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
				$i++;
			}
			return $code;
		}
		elseif($method == "wordlist") {
			//Open up the wordlist file
			if (!$wordlist = @file_get_contents("wordlist.txt")) {
				//No wordlist file present
				return $this->oldGenerateCode($characters);
			}
			$wordlist = explode("\n", $wordlist);
			$validWord = false;
			$i = 0;
			while($validWord == false) {
				$word = $wordlist[array_rand($wordlist)];
				$word = trim($word);
				$i++;
				if(substr($word, 0, 1) == "#")
					$validWord = false;
				if((strlen($word) != $characters) && $characters != 0)
					$validWord = false;
				if($i > 200)
					$validWord = true;
			}
			$possible = 'bcdfhkmnprsvwyz';
			//$word .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			return strtoupper($word);
		}
		elseif($method = "bigram") {
			//Bigram analysis-based word generation
			$bigramFreq = array(
				array(0, 2, 3, 5, 0, 1, 1, 0, 6, 0, 1, 7, 2, 21, 0, 1, 0, 9, 10, 14, 1, 1, 1, 0, 3, 0),
				array(9, 0, 0, 0, 25, 0, 0, 0, 3, 0, 0, 11, 0, 0, 13, 0, 0, 7, 1, 0, 12, 0, 0, 0, 12, 0),
				array(15, 0, 1, 0, 16, 0, 0, 15, 5, 0, 9, 3, 0, 0, 18, 0, 0, 4, 0, 5, 2, 0, 0, 0, 0, 0),
				array(13, 0, 0, 3, 26, 0, 0, 0, 13, 0, 0, 1, 0, 4, 16, 0, 0, 7, 3, 0, 2, 0, 0, 0, 3, 0),
				array(6, 0, 2, 16, 4, 1, 1, 0, 1, 0, 0, 5, 2, 12, 0, 1, 0, 21, 8, 4, 0, 2, 1, 1, 2, 0),
				array(10, 0, 0, 0, 9, 6, 0, 0, 12, 0, 0, 4, 0, 0, 25, 0, 0, 13, 1, 9, 5, 0, 0, 0, 0, 0),
				array(19, 0, 0, 0, 17, 0, 2, 21, 6, 0, 0, 5, 0, 1, 13, 0, 0, 7, 2, 0, 2, 0, 0, 0, 0, 0),
				array(17, 0, 0, 0, 51, 0, 0, 0, 15, 0, 0, 0, 0, 0, 7, 0, 0, 1, 0, 4, 1, 0, 0, 0, 0, 0),
				array(1, 0, 4, 5, 3, 2, 3, 0, 0, 0, 1, 5, 5, 30, 3, 0, 0, 3, 12, 13, 0, 2, 0, 0, 0, 0),
				array(11, 0, 0, 0, 12, 0, 0, 0, 2, 0, 0, 0, 0, 0, 31, 0, 0, 0, 0, 0, 41, 0, 0, 0, 0, 0),
				array(1, 0, 0, 0, 54, 0, 0, 0, 16, 0, 0, 3, 0, 15, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0, 1, 0),
				array(8, 0, 0, 6, 19, 2, 0, 0, 13, 0, 1, 15, 0, 0, 11, 0, 0, 0, 2, 1, 2, 0, 0, 0, 11, 0),
				array(14, 2, 0, 0, 36, 0, 0, 0, 10, 0, 0, 0, 2, 0, 13, 4, 0, 2, 2, 0, 3, 0, 0, 0, 6, 0),
				array(2, 0, 3, 24, 12, 0, 18, 0, 4, 0, 1, 1, 0, 1, 6, 0, 0, 0, 3, 13, 0, 0, 0, 0, 1, 0),
				array(0, 0, 1, 1, 0, 8, 0, 0, 1, 0, 2, 4, 8, 15, 5, 1, 0, 12, 2, 4, 16, 2, 6, 0, 0, 0),
				array(12, 0, 0, 0, 22, 0, 0, 3, 5, 0, 0, 10, 0, 0, 13, 8, 0, 10, 3, 4, 3, 0, 0, 0, 0, 0),
				array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0),
				array(8, 0, 1, 5, 29, 0, 1, 0, 9, 0, 1, 1, 1, 3, 13, 0, 0, 2, 5, 5, 2, 0, 0, 0, 3, 0),
				array(6, 3, 1, 0, 14, 0, 0, 11, 7, 0, 1, 2, 1, 1, 9, 3, 0, 0, 6, 19, 4, 0, 1, 0, 2, 0),
				array(4, 0, 0, 0, 12, 0, 0, 41, 7, 0, 0, 2, 0, 0, 15, 0, 0, 3, 3, 2, 1, 0, 1, 0, 1, 0),
				array(1, 1, 3, 2, 2, 0, 6, 0, 2, 0, 0, 8, 2, 13, 0, 6, 0, 13, 15, 18, 0, 0, 0, 0, 0, 0),
				array(5, 0, 0, 0, 74, 0, 0, 0, 13, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
				array(30, 0, 0, 0, 18, 0, 0, 17, 16, 0, 0, 0, 0, 5, 7, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
				array(3, 0, 23, 0, 6, 5, 0, 1, 11, 0, 0, 0, 0, 0, 0, 23, 0, 0, 0, 21, 1, 0, 0, 0, 0, 0),
				array(1, 1, 0, 0, 21, 0, 0, 0, 4, 0, 0, 0, 0, 0, 51, 0, 0, 1, 9, 4, 0, 0, 0, 0, 0, 0),
				array(10, 0, 0, 0, 51, 0, 0, 0, 14, 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 13, 7),
				);

			$letfreq = array(10,2,2,2,10,1,2,7,8,0,0,4,2,6,8,1,0,5,5,8,3,1,3,0,0,0);
			$retstr = "";
			$rand_val = mt_rand(0, 99);
			//$retstr = chr(mt_rand(0, 25) + 65);
			$retstr_last;

			while(!$retstr) {
				for($i = 0; $i < 26; $i++) {
					$rand_val -= $letfreq[$i];

					if($rand_val < 0) {
						$retstr = chr($i + 65);
						$retstr_last = $i;
						break;
					}
				}
			}

			for($i = 1; $i < $characters; $i++) {
				while(!isset($retstr[$i])) {
					$rand_val = mt_rand(0, 99);
					for($j = 0; $j < 26; $j++) {
						$rand_val -= intval($bigramFreq[$retstr_last][$j]);
						if($rand_val < 0) {
							$retstr_last = $j;
							$retstr.= chr($j + 65);

							break;
						}
					}
				}
			}

			return $retstr;
		}
		return "ERROR";
	}

	function generateImage($width=320, $height=75, $characters=8) {
		//configuration is here
		//Sorry if this isn't the "proper" way to do things, but I'm in a rush to get this configurable at all.
		$distort_enabled = true;	//distort the final image by shifting pixels
		$distort_offset = 4;

		$letter_spacing = 20;	//Distance between letters (arbitrary value, not in pixels)
		$letter_angle_variation = 30;	//Maximum angle that the letters can be rotated
		$letter_angle_correction = 5;	//How much to change angle rotation for every letter
		$letter_angle_correction_invariant = 20;	//What the angle should be reset to should it exceed $letter_angle_variation
		$letter_spacing_size_offset = 35;	//How much to adjust letter spacing in terms of size
		$letter_spacing_angle_coefficent = 3;	//How much to adjust letter spacing for angle
		$letter_boldness = 4;	//How many times the letters should be drawn over each other, each time shifting right 1px
		$font_size = $height * 0.60;	//Should be self-explainatory

		$code_generator = "bigram";	//either "worldist" or "random" for pre-defined words or random characters

		$color_red_min = 60;	//Color generation values. Goes from 0-255.
		$color_red_max = 90;	//Color values cannot exceed 255.
		$color_green_min = 60;
		$color_green_max = 90;
		$color_blue_min = 60;
		$color_blue_max = 90;
		$color_bg_offset_start = 60;	//How much brighter the gradient should start at
		$color_bg_offset_end = 160;		//...and end at (set the last two equal for no gradient)
		$color_text_offset = 40;	//Brightness of the text (lower is darker)
		$color_noise_offset = 40;	//Brightness of the noise (lower is darker)

		$noise_longlines_num = 1;	//Recommended 1 or 2, if any
		$noise_shortlines_num = 3;
		$noise_shortlines_maxlength = 30;

		$code = $this->generateCode($characters, $code_generator);

		$image = imagecreatetruecolor($width, $height) or $this->error('Cannot initialize new GD image stream');
		if(function_exists("imageantialias"))
			imageantialias ($image, true) or $this->error('Cannot turn on anti-ailasing');

		//Generate some random colors!
		$c1 = mt_rand($color_red_min, $color_red_max);
		$c2 = mt_rand($color_green_min, $color_green_max);
		$c3 = mt_rand($color_blue_min, $color_blue_max);

		/* set the colours */
		$background_1_color = array($c1+$color_bg_offset_start, $c2+$color_bg_offset_start, $c3+$color_bg_offset_start);
		$background_2_color = array($c1+$color_bg_offset_end, $c2+$color_bg_offset_end, $c3+$color_bg_offset_end);
		$background_color = imagecolorallocate($image, $c1+$color_bg_offset_end, $c2+$color_bg_offset_end, $c3+$color_bg_offset_end);
		$text_color = imagecolorallocate($image, $c1+$color_text_offset, $c2+$color_text_offset, $c3+$color_text_offset);
		$noise_color = imagecolorallocate($image, $c1+$color_noise_offset, $c2+$color_noise_offset, $c3+$color_noise_offset);
		$highlight_color = imagecolorallocate($image, 255, 0, 0);

		//Draw a gradient background fill
		imagefilledrectangle($image, 0, 0, $width, $height, $background_color);
		for($i=1; $i<=$width; $i++) {
			$linecolors[0] = ($background_1_color[0]+(($background_2_color[0] - $background_1_color[0]) / $width * $i));
			$linecolors[1] = ($background_1_color[1]+(($background_2_color[1] - $background_1_color[1]) / $width * $i));
			$linecolors[2] = ($background_1_color[2]+(($background_2_color[2] - $background_1_color[2]) / $width * $i));
			$linecolor = imagecolorallocate($image, $linecolors[0], $linecolors[1], $linecolors[2]);
			imageline($image, $i, 0, $i, $height, $linecolor);
			imagecolordeallocate($image, $linecolor);
		}

		//Long thick lines
		for($w=0; $w<$noise_longlines_num; $w++) {
			$w1 = mt_rand(10,20) + ($w - 1)*50;
			$w2 = mt_rand($width/1.3,$width)+20;
			$h1 = mt_rand(10, $height/2-10) + $w*$height/2;
			$h2 = mt_rand(1, $height) + (1-$w)*$height/2;
			for( $i=0; $i<30; $i++) {
				$j = $i/2;
				imageline($image, $w1+$j, $h1, $w2+$j, $h2, $noise_color)
					or $this->error("Error creating think line.");
			}
		}
		//Small thick lines
		for($w=0; $w<$noise_shortlines_num; $w++) {
			$w1 = mt_rand(0, $width);
			$w2 = $w1 + rand(-$noise_shortlines_maxlength, $noise_shortlines_maxlength);
			$h1 = mt_rand(0, $height);
			$h2 = $h1 + rand(-$noise_shortlines_maxlength, $noise_shortlines_maxlength);
			for( $i=0; $i<10; $i++) {
				$j = $i/2;
				imageline($image, $w1+$j, $h1, $w2+$j, $h2, $noise_color)
					or $this->error("Error creating think line.");
			}
		}


		/* create textbox and add text */
		$textbox = @imagettfbbox($font_size, 0, "fonta.ttf", $code)
			or $this->error('Error in imagettfbbox function');
		$y = ($height - $textbox[5])/2.3;
		$x = -10;
		$i = 0;

		$xoffset = 0;
		foreach (str_split($code) as $key => $value) {
			//This bit of code draws the letters onto the image at random angles and at distances
			//so that the letters are smothered together (harder for computers to read)
			$letterfont = "font.ttf";
			$angle = isset($angle)?$angle:0;
			$prevangle = $angle;
			$angle = $angle + rand(-$letter_angle_variation, $letter_angle_variation);

			if($angle < -$letter_angle_variation)
				$angle = -$letter_angle_correction_invariant - rand(-$letter_angle_correction, $letter_angle_correction);	//Normalise angles
			if($angle > $letter_angle_variation)
				$angle = $letter_angle_correction_invariant - rand(-$letter_angle_correction, $letter_angle_correction);

			$xoffset += $letter_spacing*($font_size/$letter_spacing_size_offset) - ($prevangle - $angle) / $letter_spacing_angle_coefficent;
			//Keep on drawing the letters over and over again for bold
			for($j = 0; $j<$letter_boldness; $j++) {
				@imagettftext($image, $font_size, $angle, $x + $xoffset + $j, $y, $text_color,
					$letterfont, $value) or $this->error('Error in imagettftext function');
			}
			$i++;
		}
		//die();

		//Now distort the image
		if($distort_enabled) {
			$distort_interval = mt_rand(15, 20);
			for($i = 0; $i < $height; $i++) {
				$offset = round(2 * sin(50 * $i)) + $distort_offset;
				//echo $offset . "<br />";
				for($j = 1; $j < $width; $j++) {
					if($offset > 6)
						$mode = -1;
					if($offset < -6)
						$mode = 1;
					if($j % $distort_interval == 0) {
						$offset += (isset($mode)?$mode:0);
					}
					if($j + $offset >= $width)
						$currdotcolor = imagecolorat($image, $width - 2, $i);
					else
						$currdotcolor = imagecolorat($image, $j + $offset, $i);
					$dotcolor = imagecolorallocate($image, ($currdotcolor >> 16) & 0xFF,
						($currdotcolor >> 8) & 0xFF, $currdotcolor & 0xFF);

					imageline($image, $j, $i, $j, $i, $dotcolor);
					imagecolordeallocate($image, $dotcolor);
				}
			}
		}

		//Make sure to have the browser not cache the image
		header('Content-Type: image/jpeg, Expires: Thu, 1 Jan 1990 00:00:00 GMT');
		imagejpeg($image, null, 100);
		imagedestroy($image);
		$_SESSION['captcha'] = $code;
	}

}

$captcha = new Captchar();
$captcha->generateImage(320, 75, 10);
