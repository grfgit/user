<?php

namespace Core;

use Exception;

/**
 * Base controller
 */
abstract class Controller {

    /**
     * @var array
     */
    protected $route_params = [];

    /**
     * Controller constructor
     *
     * @param $route_params
     */
    public function __construct($route_params) {
        $this->route_params = $route_params;
    }

    /**
     * @param $name
     * @param $args
     * @return void
     * @throws Exception
     */
    public function __call($name, $args) {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * @return void
     */
    protected function before() {

    }

    /**
     * @return void
     */
    protected function after() {

    }

    /**
     * @return string
     */
    public function getClassName(): string {
        return __CLASS__;
    }

    /**
     * @param array $data
     * @param int $http_status_code
     * @return void
     */
    public static function response(array $data = [], int $http_status_code = 200) {
        header('Content-Type: application/json; charset=utf-8');

        $response = [
            'http_status_code' => $http_status_code,
            'http_status' => self::getHttpStatusDescription($http_status_code),
            'data' => $data
        ];

        echo json_encode($response);
        die;
    }

    /**
     * @param string $massage
     * @param int $http_status_code
     * @return void
     */
    public static function error(string $massage = '', int $http_status_code = 400) {
        header('Content-Type: application/json; charset=utf-8');

        $response = [
            'http_status_code' => $http_status_code,
            'http_status' => self::getHttpStatusDescription($http_status_code),
            'error' => $massage
        ];

        echo json_encode($response);
        die;
    }

    /**
     * @param int $code
     * @return string
     */
    private static function getHttpStatusDescription(int $code = 200): string {
        switch ($code) {
            case 100:
                $description = 'Continue';
                break;
            case 101:
                $description = 'Switching Protocols';
                break;
            case 200:
                $description = 'OK';
                break;
            case 201:
                $description = 'Created';
                break;
            case 202:
                $description = 'Accepted';
                break;
            case 203:
                $description = 'Non-Authoritative Information';
                break;
            case 204:
                $description = 'No Content';
                break;
            case 205:
                $description = 'Reset Content';
                break;
            case 206:
                $description = 'Partial Content';
                break;
            case 300:
                $description = 'Multiple Choices';
                break;
            case 301:
                $description = 'Moved Permanently';
                break;
            case 302:
                $description = 'Moved Temporarily';
                break;
            case 303:
                $description = 'See Other';
                break;
            case 304:
                $description = 'Not Modified';
                break;
            case 305:
                $description = 'Use Proxy';
                break;
            case 400:
                $description = 'Bad Request';
                break;
            case 401:
                $description = 'Unauthorized';
                break;
            case 402:
                $description = 'Payment Required';
                break;
            case 403:
                $description = 'Forbidden';
                break;
            case 404:
                $description = 'Not Found';
                break;
            case 405:
                $description = 'Method Not Allowed';
                break;
            case 406:
                $description = 'Not Acceptable';
                break;
            case 407:
                $description = 'Proxy Authentication Required';
                break;
            case 408:
                $description = 'Request Time-out';
                break;
            case 409:
                $description = 'Conflict';
                break;
            case 410:
                $description = 'Gone';
                break;
            case 411:
                $description = 'Length Required';
                break;
            case 412:
                $description = 'Precondition Failed';
                break;
            case 413:
                $description = 'Request Entity Too Large';
                break;
            case 414:
                $description = 'Request-URI Too Large';
                break;
            case 415:
                $description = 'Unsupported Media Type';
                break;
            case 422:
                $description = 'Unprocessable Entity';
                break;
            case 500:
                $description = 'Internal Server Error';
                break;
            case 501:
                $description = 'Not Implemented';
                break;
            case 502:
                $description = 'Bad Gateway';
                break;
            case 503:
                $description = 'Service Unavailable';
                break;
            case 504:
                $description = 'Gateway Time-out';
                break;
            case 505:
                $description = 'HTTP Version not supported';
                break;
            default:
                $description = 'Unknown http status code';
                break;
        }
        return $description;
    }
}
