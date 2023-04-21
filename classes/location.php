<?php
/*
* Author: Mohammad Sadegh Maleki
* Website: webco.ge
* Version: 0.0.1
* Date: 25-04-2015
* App Name: Php+ajax country state city dropdown
* Description: A simple oops based php and ajax country state city dropdown list
*/
require_once("dbconfig.php");
class location extends dbconfig {
   
   public static $data;

   function __construct() {
     parent::__construct();
   }
 
 // Fetch all countries list
// Fetch all countries list
    public static function getCountries() {
        try {
            $query = "SELECT id, name FROM countries";
            $stmt = dbconfig::$con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            if(!$result) {
                throw new exception("Country not found.");
            }
            $res = array();
            while($resultSet = $result->fetch_assoc()) {
                $res[$resultSet['id']] = $resultSet['name'];
            }
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$res);
            return $data;
        } catch (Exception $e) {
            $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
            return $data;
        }
    }



// Fetch all states list by country name
    public static function getStates($countryId) {
        try {
            $stateQuery = "SELECT id, name FROM states WHERE country_id = ?";
            $stmt = dbconfig::$con->prepare($stateQuery);
            $stmt->bind_param("i", $countryId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows < 1) {
                throw new Exception("State not found.");
            }
            $res = array();
            while($resultSet = $result->fetch_assoc()) {
                $res[$resultSet['id']] = $resultSet['name'];
            }
            $data = array('status'=>'success', 'c'=>$countryId, 'tp'=>1, 'msg'=>"States fetched successfully.", 'result'=>$res);
        } catch (Exception $e) {
            $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        } finally {
            return $data;
        }
    }

// Fetch all cities list by state name
    public static function getCities($stateId) {
        try {
            $query = "SELECT cities.id, cities.name FROM cities INNER JOIN states ON cities.state_id = states.id WHERE states.id = ?";
            $stmt = dbconfig::$con->prepare($query);
            $stmt->bind_param("s", $stateId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows < 1) {
                throw new Exception("City not found.");
            }
            $res = array();
            while($cityResultSet = $result->fetch_assoc()) {
                $res[$cityResultSet['id']] = $cityResultSet['name'];
            }
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Cities fetched successfully.", 'result'=>$res);
        } catch (Exception $e) {
            $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        } finally {
            return $data;
        }
    }



}
