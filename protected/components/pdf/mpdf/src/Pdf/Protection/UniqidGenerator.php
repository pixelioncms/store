<?php

Yii::import('app.pdf.mpdf.src.Protection.Protection');
Yii::import('app.pdf.mpdf.src.MpdfException');
class UniqidGenerator
{

	public function __construct()
	{
		if (!function_exists('random_int') || !function_exists('random_bytes')) {
			throw new MpdfException(
				'Unable to set PDF file protection, CSPRNG Functions are not available. '
				. 'Use paragonie/random_compat polyfill or upgrade to PHP 7.'
			);
		}
	}

	/**
	 * @return string
	 */
	public function generate()
	{
		$chars = 'ABCDEF1234567890';
		$id = '';

		for ($i = 0; $i < 32; $i++) {
			$id .= $chars[random_int(0, 15)];
		}

		return md5($id);
	}
}
