<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Adapter\Http;

class HttpHelper
{
    public static function ok($data): HttpResponse
    {
        return new HttpResponse(statusCode: 200, body: $data);
    }

    public static function created($data): HttpResponse
    {
        return new HttpResponse(statusCode: 201, body: $data);
    }

    public static function noContent(): HttpResponse
    {
        return new HttpResponse(statusCode: 204, body: null);
    }

    public static function badRequest($error): HttpResponse
    {
        return new HttpResponse(statusCode: 400, body: $error);
    }

    public static function unauthorized(): HttpResponse
    {
        return new HttpResponse(statusCode: 401, body: 'Unauthorized');
    }

    public static function forbiden($error): HttpResponse
    {
        return new HttpResponse(statusCode: 403, body: $error);
    }

    public static function notFound($error): HttpResponse
    {
        return new HttpResponse(statusCode: 404, body: $error);
    }

    public static function conflict($error): HttpResponse
    {
        return new HttpResponse(statusCode: 409, body: $error);
    }

    public static function unprocessable($errors): HttpResponse
    {
        return new HttpResponse(statusCode: 422, body: $errors);
    }

    public static function serverError(?\Exception $error = null): HttpResponse
    {
        $body = [
            'message' => $error->getMessage(),
            'trace' => $error->getTrace()[0],
        ];

        return new HttpResponse(statusCode: 500, body: $body);
    }

}
