<?php
namespace Jideola\Traits;
use Jideola\Middlewares\RouteMiddleware;

trait Request{
    public $request;
    public $http_origin;

    public function __construct(){
        
        $requestType = RouteMiddleware::requestType();
        if(in_array($_SERVER['REQUEST_METHOD'] ,$requestType) && $_SERVER['REQUEST_METHOD']  == 'GET'){
            $this->cleanGetRequest();
        }elseif (in_array($_SERVER['REQUEST_METHOD'] ,$requestType) && $_SERVER['REQUEST_METHOD']  == 'POST'){
            $this->cleanPostRequest();
        }elseif($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
            http_response_code(200);
            die();
        }else{
            http_response_code(405); /* Method not allowed */
            die();
        }
        
    }

    private function cleanGetRequest(){
        global $jideola;
        /* Skip the first two get parameters p and v1 */
        $x = 1;
        foreach ($_GET as $index=>$value){
            if($x > 2) {
                $this->request[$index] = $jideola->antiHacking($value);
            }
            $x++;
        }
    }

    /**
     * function supports both form data and json post requests
     */
    private function cleanPostRequest(){
        global $jideola;

        $incomingJson = json_decode(file_get_contents('php://input'), true);
        if(!empty($incomingJson)) $_POST = $incomingJson;

        if(!empty($_POST['req'])) {

            $data = hex2bin($_POST['req']);
            $data = json_decode($data,true);

            /* To support multidimentional array */
            $clean_data=[];
            foreach ($data as $key=> $info) {
                if(is_array($info)) $clean_data[trim($key)] = $this->clean_array($info);
                else $clean_data[trim($key)] = $jideola->antiHacking($info);
            }
            $this->request = $clean_data;
        }

    }

    private function clean_array($array)
    {
        global $jideola;
        $cleaned=[];
        foreach ($array as $key=> $info)
        {
            if(is_array($info)) $cleaned[trim($key)] = $this->clean_array($info);
            else $cleaned[trim($key)] = $jideola->antiHacking($info);
        }

        return $cleaned;
    }

}