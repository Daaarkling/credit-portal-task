<?php
declare(strict_types=1);


namespace App\Model\Helpers;


/**
 * 
 * @author Jan Vaňura
 */
class CzechDateHelper {
	
	
	/**
	 * vrátí pole českých měsíců - 0 => leden, ...
	 * @return array pole českých měsíců
	 */
	public static function getCzechMonths(): array
	{
		return ['leden', 'únor', 'březen', 'duben', 'květen', 'červen', 'červenec', 'srpen', 'září', 'říjen', 'listopad', 'prosinec'];
	}
	
	
	/**
	 * vrátí pole českých měsíců - 0 => leden, ...
	 * @return array pole českých měsíců
	 */
	public static function getCzechDays(): array
	{
		return ['pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota', 'neděle'];
	}


	/**
	 * vrátí pole obsahující české názvy měsíců s aktuálním rokem - 2013 => 'leden',... - 2014 => 'leden', ...
	 * @return array
	 */
	public static function getMonthsForSelect(): array
	{
		$months = CzechDateHelper::getCzechMonths();
		
		$array = [];
		for($i = 1; $i <= 12; $i++){
			$array[$i] = $months[$i-1];
		}
		
		return $array;		
	}
	
	
	/**
	 * vrátí pole roků s rozsahem ($range) od aktuálního roku: 2010 - 2015
	 * př: [2013] => 2013, ...
	 * @param int|NULL 
	 * @return array
	 */
	public static function getYearsForSelect(int $range = 0): array
	{
		if($range <= 0){
			$range = 2;
		}
		
		$year = date('Y');
		$years = [];
		
		for($i = 1; $i <= $range; $i++){
			$years[$year-$i] = $year-$i;
			$years[$year+$i] = $year+$i;
		}
		$years[$year] = $year-0;		
		asort($years);
		
		return $years;
	}


	/**
	 * vrátí dvourozměrné pole nejbližších českých měsíců a roků k aktuálnímu datu
	 * @param int $rangeTop
	 * @param int $rangeBot
	 * @param string $sort
	 * @return array
	 */
	public static function getRangedDate(int $rangeTop = 2, int $rangeBot = 6, string $sort = 'desc'): array
	{
		$czechMonths = CzechDateHelper::getCzechMonths();
		
		$actualMonth = date('n');
		$actualYear = date('Y');
	
		$array = [];
		
		// horni rozsah vcetne aktualniho data proto $rangeTop+1
		$tempMonth = $actualMonth;
		$tempYear = $actualYear;
		for($i = 0; $i <= $rangeTop; $i++){
			if ($tempMonth > 12){
				$tempYear++;
				$tempMonth = 1;				
			}
			$array[$tempYear][$tempMonth] = $czechMonths[$tempMonth-1];
			$tempMonth++;
		}
		
		// dolni rozsah
		$tempMonth = $actualMonth;
		$tempYear = $actualYear;		
		for($i = 1; $i <= $rangeBot; $i++){
			if($tempMonth < 1){
				$tempYear--;
				$tempMonth = 12;
			}
			$array[$tempYear][$tempMonth] = $czechMonths[$tempMonth-1];
			$tempMonth--;
		}
		
		// serazeni pole
		if($sort === 'desc'){
			krsort($array);
			$result = [];
			foreach ($array as $key => $value) {
				krsort($value);
				$result[$key] = $value;
			}
		} else {
			ksort($array);
			
			$result = [];
			foreach ($array as $key => $value) {
				ksort($value);
				$result[$key] = $value;
			}
		}
		return $result;
	}
}
