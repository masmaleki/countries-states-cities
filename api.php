<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});



header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

include_once __DIR__ .'/classes/location.php';

$loc = new Location();

try {
    $type = $_GET['type'] ?? '';
    switch ($type) {
        case 'getCountries':
            $data = $loc->getCountries();
            break;
        case 'getStates':
            $countryId = $_GET['countryId'] ?? '';
            if (empty($countryId)) {
                throw new Exception("Country Id is not set.");
            }
            $data = $loc->getStates($countryId);
            break;
        case 'getCities':
            $stateId = $_GET['stateId'] ?? '';
            if (empty($stateId)) {
                throw new Exception("State Id is not set.");
            }
            $data = $loc->getCities($stateId);
            break;
        default:
            throw new Exception("Type is not set.");
    }
} catch (Throwable $e) {
    $data = ['status' => 'error', 'tp' => 0, 'msg' => $e->getMessage()];
} finally {
    echo json_encode($data ?? []);
}
