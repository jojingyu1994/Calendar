<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class calendarController extends Controller
{
    public function index()
    {
        $ch = curl_init();
        $url = 'http://apis.data.go.kr/B090041/openapi/service/LrsrCldInfoService/getLunCalInfo'; /*URL*/
        $queryParams = '?' . urlencode('ServiceKey') . '=UF8E8W%2Fa6SiQ9BtD4JUnRLgPAPOW8MuyekA0502DzO4YSYpaX4Nfs3k%2BoQmfU683JEzDkbyPPawcyL%2FR%2FBGZ0g%3D%3D'; /*Service Key*/
        $queryParams .= '&' . urlencode('solYear') . '=' . urlencode('2015'); /**/
        $queryParams .= '&' . urlencode('solMonth') . '=' . urlencode('09'); /**/
        $queryParams .= '&' . urlencode('solDay') . '=' . urlencode('22'); /**/

        curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $response = curl_exec($ch);
        curl_close($ch);

        $xml_response = simplexml_load_string($response,"SimpleXmlElement",LIBXML_NOCDATA);
        $json_encode = json_encode($xml_response);
        $response = json_decode($json_encode,TRUE);

        return view('index',['response' => $response]);
    }    
}
