<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * Возвращает json с успешным выполнением
     *
     * @param array $data
     * @param int $httpStatus
     * @param array $headers
     * @return JsonResponse
     */
    protected function responseSuccess(array $data, int $httpStatus = 200, array $headers = []): JsonResponse
    {
        return response()->json(['status' => 'ok', 'data' => $data], $httpStatus, $headers);
    }

    /**
     * Возвращает json с ошибкой
     *
     * @param string $message
     * @param int $httpStatus
     * @param array $headers
     * @return JsonResponse
     */
    protected function responseError(string $message, int $httpStatus, array $headers = []): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message], $httpStatus, $headers);
    }
}
