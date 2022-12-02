<?php

/**
 * Created by PhpStorm.
 * User: Momen
 * Date: 11/23/16
 * Time: 5:56 PM
 */

namespace App\Helpers;

use App\Helpers\Messages;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


enum ProcessType
{
    case CREATE;
    case READ;
    case UPDATE;
    case DELETE;
}
class ControllersService
{
    public
    static function generateResponse($responseArray, $statusCode)
    {
        return response()->json($responseArray, $statusCode);
    }

    // public
    // static function generateProcessResponse(bool $processStatus, String $processCode = null, int $statusCode = null, String $details = null)
    // {
    //     if ($details == null) {
    //         $responseArray = array("status" => $processStatus, "message" => Messages::getMessage($processCode));
    //     } else {
    //         $responseArray = array("status" => $processStatus, "message" => Messages::getMessage($processCode), 'details' => $details);
    //     }

    //     if ($statusCode == null) {
    //         $statusCode = $processStatus ? Response::HTTP_OK :  Response::HTTP_BAD_REQUEST;
    //     }

    //     return self::generateResponse($responseArray, $statusCode);
    // }

    public
    static function generateProcessResponse(bool $processStatus, String $processType)
    {
        $results = array();
        $results['success_message'] = Messages::getMessage($processType . "_SUCCESS");
        $results['failure_message'] = Messages::getMessage($processType . "_FAILED");
        $results['success_code'] = $processType == 'CREATE' ? Response::HTTP_CREATED : Response::HTTP_OK;

        return response()->json(
            ['status' => $processStatus, 'message' => $processStatus ? $results['success_message'] : $results['failure_message']],
            $processStatus ? $results['success_code'] : Response::HTTP_BAD_REQUEST
        );
    }


    public
    static function generateObjectSuccessResponse($model, $message, $key = "object")
    {
        return response()->json(array(
            'status' => true,
            'message' => $message,
            $key => $model
        ), 200);
    }

    public
    static function generateArraySuccessResponse($objectsArray, $message)
    {
        return response()->json(array(
            'status' => true,
            'message' => $message,
            'list' => $objectsArray
        ), Response::HTTP_OK);
    }

    public static function generateValidationErrorMessage($message)
    {
        return response()->json(array(
            'status' => false,
            'message' => $message,
        ), Response::HTTP_BAD_REQUEST);
    }

    public
    static function isApiRoute(Request $request)
    {
        $route = $request->route()->getPrefix();
        if (str_contains($route, 'api')) {
            return true;
        } else {
            return false;
        }
    }

    public static function generateRandomNumber()
    {
        $number = mt_rand(1000, 9999);
        return $number;
    }

    public static function debug_to_console($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }

    protected static function getClientIp(): string
    {
        $ip = \request()->ip();
        return $ip == '127.0.0.1' ? '66.102.0.0' : $ip;
    }

    public static function checkAuthForPermission($authName, $permissionName)
    {
        if (auth($authName)->check()) {
            if (auth($authName)->user()->hasPermissionTo($permissionName)) {
                return true;
            }
        }
        return false;
    }
}
