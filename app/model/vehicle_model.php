<?php
namespace App\Model;

use Exception;
use Requests;
use App\Lib\Response;

class VehicleModel
{
    private $group_name1 = "AZUCARERA INGENIO VALDEZ";
    private $group_name2 = "BOMBAS DE DRENAJE";
    private $token = '01EC469EB5F64D8DA878042400D3CBA2';
    private $IN = 1; // inside polygon
    private $OUT = 2; // outside polygon

    private $db;
    private $table = 'vehicle';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function getAll()
    {
//        $url = 'http://dts.location-world.com/api/fleet/onlinedevicesinfo?token='.$this->token.'&time_zone_offset=-5&culture=es';
//        $request = Requests::get($url, array('Accept' => 'application/json'));
//
//        $jsonObj = json_decode($request->body, true)["0"];
//
//        $vehicles = [];
//        foreach ($jsonObj as $vehicle) {
//            if ($vehicle['group_name'] === $this->group_name1
//                || $vehicle['group_name'] === $this->group_name2) {
//                $vehicles[count($vehicles)] = $vehicle;
//            }
//        }
        $vehicles = $this->getAllLocal();

        return [
            'data' => $vehicles,
            'total' => count($vehicles)
        ];
    }

    public function getVehicles()
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

        return $vehicles;
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

    public function getAllLocal()
    {
        $data = $this->db
            ->from($this->table)
            ->orderBy('id DESC')
            ->fetchAll();

        return $data;
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

    public function checkAlerts() {
        try {
            return $this->checkAll();
        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
        return $this->response->SetResponse(true, 'Is ok!');
    }

    public function checkAll() {
        $count = 0;
        $vehiclesExternal = $this->getVehicles();
        $vehiclesLocal = $this->getAllLocal();
        $boundsService = new BoundsModel($this->db);

//        $bounds = $boundsService->getAll();
//        foreach ($bounds['data'] as $bound) {
//            $associates_vehicles = $boundsService->getVehiclesBound($bound->id)['data'];
//            $bound->vehicles = $associates_vehicles;
//        }
        foreach ($vehiclesExternal as $external) {
            $exist = false;
            foreach ($vehiclesLocal as $local) {
                $local = (array) $local;
                if ($external['imei'] === $local['imei']) {
                    $exist = true;
                    if ($external['ignition_state'] !== $local['ignition_state']) {
                        if ($external['ignition_state'] === 1 && $local['ignition_state'] == 2) {
                            $this->alertIgnitionOn(
                                $local['imei'],
                                $local['alias'],
                                $local['latitude'],
                                $local['longitude']
                            );
                            $count++;
                        }
                        if ($external['ignition_state'] === 2 && $local['ignition_state'] == 1) {
                            $this->alertIgnitionOff(
                                $local['imei'],
                                $local['alias'],
                                $local['latitude'],
                                $local['longitude']
                            );
                            $count++;
                        }
                    }
                    if ($external['speed'] > 100 && $local['speed'] <= 100) {
                        $this->alertSpeed(
                            $local['imei'],
                            $local['alias'],
                            $local['latitude'],
                            $local['longitude']
                        );
                        $count++;
                    }
                    $external['in_polygon'] = 0;
                    $association = $boundsService->getVehiclesBoundByImei($external['imei']);
                    if (is_object($association)) {
                        $in_limit = $this->checkLimit($external, $association);
                        if ($in_limit) {
                            $external['in_polygon'] = $this->IN;
                        } else {
                            $external['in_polygon'] = $this->OUT;
                        }
                        if (((int) $local['in_polygon']) === $this->IN && $external['in_polygon'] === $this->OUT) {
                            $this->alertOut(
                                $local['imei'],
                                $local['alias'],
                                $local['latitude'],
                                $local['longitude'],
                                $association->bounds_points
                            );
                            $count++;
                        }
                        if (((int) $local['in_polygon']) === $this->OUT && $external['in_polygon'] === $this->IN) {
                            // alert vehicle is enter in polygon
                            $count++;
                        }
                    }
                    $this->db->update($this->table, $external, $local['id'])->execute();
                }
            }
            if (!$exist) {
                $this->db->insertInto($this->table, $external)->execute();
            }
        }
        return $count;
    }

    public function checkLimit($vehicle, $association) {
        $polygon = json_decode($association->bounds_points);
        $vertices_y = array();
        $vertices_x = array();
        foreach ($polygon as $point) {
            $vertices_y[] = $point->latitude;
            $vertices_x[] = $point->longitude;
        }
        $points_polygon = count($vertices_x) - 1;
        $latitude_y = $vehicle['latitude'];
        $longitude_x = $vehicle['longitude'];
        return $this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y);
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
            if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
                $c = !$c;
        }
        return $c;
    }

    public function alertOut($imei, $alias, $latitude, $longitude, $points) {
        $alert = [
            "imei" => $imei,
            "cause" => AlertModel::OUT_BOUNDS,
            "message" => "El vehiculo ".$alias." ha salido de la zona establecida",
            "extra" => $points,
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
        $alertService = new AlertModel($this->db);
        $alertService->registerGeneral($alert);
    }

    public function alertIgnitionOn($imei, $alias, $latitude, $longitude) {
        $alert = [
            "imei" => $imei,
            "cause" => AlertModel::GENERAL,
            "message" => "El vehiculo ".$alias." fue encendido",
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
        $alertService = new AlertModel($this->db);
        $alertService->registerGeneral($alert);
    }

    public function alertIgnitionOff($imei, $alias, $latitude, $longitude) {
        $alert = [
            "imei" => $imei,
            "cause" => AlertModel::GENERAL,
            "message" => "El vehiculo ".$alias." fue apagado",
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
        $alertService = new AlertModel($this->db);
        $alertService->registerGeneral($alert);
    }

    public function alertSpeed($imei, $alias, $latitude, $longitude) {
        $alert = [
            "imei" => $imei,
            "cause" => AlertModel::GENERAL,
            "message" => "El vehiculo ".$alias." sobre paso los 100KM/h",
            "latitude" => $latitude,
            "longitude" => $longitude,
        ];
        $alertService = new AlertModel($this->db);
        $alertService->registerGeneral($alert);
    }






}