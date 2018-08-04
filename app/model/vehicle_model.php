<?php
namespace App\Model;

use Requests;

class VehicleModel
{
    private $group_name1 = "AZUCARERA INGENIO VALDEZ";
    private $group_name2 = "BOMBAS DE DRENAJE";
    private $token = '01EC469EB5F64D8DA878042400D3CBA2';

    public function getAll()
    {
        $url = 'http://dts.location-world.com/api/fleet/onlinedevicesinfo?token='.$this->token.'&time_zone_offset=-5&culture=es';
        $request = Requests::get($url, array('Accept' => 'application/json'));

        $jsonObj = json_decode($request->body, true)["0"];

        $vehicles = [];
        foreach ($jsonObj as $vehicle) {
            if ($vehicle['group_name'] === $this->group_name1
                || $vehicle['group_name'] === $this->group_name2) {
                $vehicles[count($vehicles)] = $vehicle;
            }
        }

        return [
            'data' => $vehicles,
            'total' => count($vehicles)
        ];
    }

    public function get($imei)
    {
        $url = 'http://dts.location-world.com/api/fleet/onlinedevicesinfo?token='.$this->token.'&time_zone_offset=-5&culture=es';
        $request = Requests::get($url, array('Accept' => 'application/json'));

        $jsonObj = json_decode($request->body, true)["0"];

        $vehicles = [];
        foreach ($jsonObj as $vehicle) {
            if ($vehicle['group_name'] === $this->group_name1
                || $vehicle['group_name'] === $this->group_name2) {
                $vehicles[count($vehicles)] = $vehicle;
            }
        }

        foreach ($vehicles as $vehicle) {
            if ($vehicle['imei'] === $imei) {
                return $vehicle;
            }
        }

        return false;
    }

    public function dailyHistory($imei, $year = false, $month = false, $day = false)
    {
        $timestamp = time()-(5*60*60);
        if (is_bool($year) && !$year) {
            $year = gmdate("Y", $timestamp);
        }
        if (is_bool($month) && !$month) {
            $month = gmdate("m", $timestamp);
        }
        if (is_bool($day) && !$day) {
            $day = gmdate("d", $timestamp);
        }
        $url = 'http://dts.location-world.com/api/Fleet/dailyhistory?token='.$this->token.'&imei='.$imei.'&year='.$year.'&month='.$month.'&day='.$day.'&timezoneoffset=-5&culture=es';
        $request = Requests::get($url, array('Accept' => 'application/json'));

        $jsonObj = json_decode($request->body, true)["0"];

        return $jsonObj;
    }
}