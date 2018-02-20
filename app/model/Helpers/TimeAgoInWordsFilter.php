<?php
declare(strict_types=1);

namespace App\Model\Helpers;


class TimeAgoInWordsFilter
{
	public function __invoke($date)
	{
		return TimeAgoInWords::timeAgoInWords($date);
	}


}