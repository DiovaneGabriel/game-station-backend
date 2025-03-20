<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

abstract class Controller extends BaseController
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    use AuthorizesRequests, ValidatesRequests;

    protected function return($status, $code, $message = null, $content = null)
    {

        $return = [];
        $return['status'] = $status;
        $message ? $return['message'] = $message : null;
        $content || $content === [] ? $return['content'] = $content : null;

        return response()->json($return, $code);
    }
    protected function returnSuccess($content = null)
    {
        return $this->return(self::STATUS_SUCCESS, 200, null, $content);
    }

    protected function returnCreated($row = null, $message = 'Success')
    {
        return $this->return(self::STATUS_SUCCESS, 200, $message, $row);
    }

    protected function returnValidationError(ValidationException $e)
    {
        return $this->return(self::STATUS_ERROR, 422, 'Unprocessable Entity', $e->errors());
    }

    protected function return400($message = 'Bad Request')
    {
        return $this->return(self::STATUS_ERROR, 400, $message);
    }

    protected function return401($message = 'Unauthorized')
    {
        return $this->return(self::STATUS_ERROR, 401, $message);
    }

    protected function return403($message = 'Forbidden')
    {
        return $this->return(self::STATUS_ERROR, 403, $message);
    }

    protected function return404($message = 'Not Found')
    {
        return $this->return(self::STATUS_ERROR, 404, $message);
    }

    protected function return408($message = 'Request Timeout')
    {
        return $this->return(self::STATUS_ERROR, 408, $message);
    }

    protected function return409($message = 'Conflit')
    {
        return $this->return(self::STATUS_ERROR, 409, $message);
    }

    protected function return424($message = "Failed Dependency")
    {
        return $this->return(self::STATUS_ERROR, 424, $message);
    }

    protected function return500($message = "Internal Server Error")
    {
        return $this->return(self::STATUS_ERROR, 500, $message);
    }
}
