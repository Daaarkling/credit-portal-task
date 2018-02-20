<?php
declare(strict_types=1);


namespace App\Model\Helpers;


class SizeUploadHelper
{

	/**
	 * @param bool $pretty
	 * @return string
	 */
	public static function getFileUploadMaxSize(bool $pretty = false) {

		if (!$pretty) {
			$maxSize = self::parseSize(ini_get('post_max_size'));
			$uploadMax = self::parseSize(ini_get('upload_max_filesize'));
			if ($uploadMax > 0 && $uploadMax < $maxSize) {
				$maxSize = $uploadMax;
			}
			return $maxSize;
		}

		$maxSize = ini_get('post_max_size');
		$uploadMax = ini_get('upload_max_filesize');
		if ($uploadMax > 0 && $uploadMax < $maxSize) {
			$maxSize = $uploadMax;
		}
		return $maxSize;
	}


	/**
	 * @param int $size
	 * @return mixed
	 */
	public static function parseSize($size) {

		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
		$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
		if ($unit) {
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		} else {
			return round($size);
		}
	}
}