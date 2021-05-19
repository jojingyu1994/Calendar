<?php

namespace App\Models;

use DB;
use SimpleXmlElement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
	use HasFactory;
	
	public function checkCalendar() {
		$result = DB::table('calendars')->orderBy('sol_date','desc')->first();

		if (empty($result)) {
			$date = '1950-01-01';
			Calendar::insertSolLunData($date);
		} else if ($result->sol_date < '2050-12-31') {
			$str_date = strtotime($result->sol_date."+1 day");
            $date = date('Y-m-d',$str_date);

			Calendar::insertSolLunData($date);
		}

	}

	public function getCalendar($lunDate)
	{
		$calendar = array();
		try {
			$calendar = DB::table('calendars')->select('sol_date')->where('lun_date',$lunDate)->first();
		} catch (\Exception $e) {
			var_dump($e->getMessage());
		}

		return $calendar->sol_date;
	}
	private function insertSolLunData($date = '')
	{
		while($date <= '2050-12-31') {
			$dataExplode = explode('-',$date);
			$year        = $dataExplode[0];
			$month       = $dataExplode[1];
			$day         = $dataExplode[2];

			$ch = curl_init();
			$url = 'http://apis.data.go.kr/B090041/openapi/service/LrsrCldInfoService/getLunCalInfo'; 
			$queryParams = '?' . urlencode('ServiceKey') . '=UF8E8W%2Fa6SiQ9BtD4JUnRLgPAPOW8MuyekA0502DzO4YSYpaX4Nfs3k%2BoQmfU683JEzDkbyPPawcyL%2FR%2FBGZ0g%3D%3D'; 
			$queryParams .= '&' . urlencode('solYear') . '=' . urlencode($year); 
			$queryParams .= '&' . urlencode('solMonth') . '=' . urlencode($month);
			$queryParams .= '&' . urlencode('solDay') . '=' . urlencode($day);

			curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$response = curl_exec($ch);
			curl_close($ch);

			if (!empty($response)) {
				$lunData = array();
				$lunYear = '';
				$lunMonth = '';
				$lunDay = '';

				// get Xml Data and change array
				$solXml    = new SimpleXMLElement($response);
				$lunYear   = $solXml->body->items->item->lunYear;
				$lunMonth  = $solXml->body->items->item->lunMonth;
				$lunDay    = $solXml->body->items->item->lunDay;
				$lunDate   = $lunYear.'-'.$lunMonth.'-'.$lunDay;

				try {
					DB::table('calendars')->insertOrIgnore(
						array(
								'sol_date' => $date,
								'lun_date' => date("Y-m-d",strtotime($lunDate))
							 )
					);
				} catch (\Exception $e) {
					return false;
				}
			}
			// delay loop 0.05sec
			usleep(50000);

			$str_date = strtotime($date."+1 day");
			$date = date('Y-m-d',$str_date);
		}

		return true;
	}
}
