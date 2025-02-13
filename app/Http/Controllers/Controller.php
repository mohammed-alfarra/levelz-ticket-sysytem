<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Return a success response.
     *
     * @param  string|null  $message  Optional success message.
     * @param  mixed|null  $data  Optional data to include in the response.
     * @return JsonResponse Success JSON response.
     */
    public function responseSuccess(?string $message = null, mixed $data = null): JsonResponse
    {
        return new JsonResponse([
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    /**
     * Return a created response.
     *
     * @param  string|null  $message  Optional created message.
     * @param  mixed|null  $data  Optional data to include in the response.
     * @return JsonResponse Created JSON response.
     */
    public function responseCreated(?string $message = 'Record created successfully', mixed $data = null): JsonResponse
    {
        return new JsonResponse([
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_CREATED);
    }

    /**
     * Return a deleted response.
     *
     * @return JsonResponse Deleted JSON response.
     */
    public function responseDeleted(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Return a conflict error response.
     *
     * @param  string  $details  Optional error details.
     * @param  string  $message  Optional error message.
     * @return JsonResponse Conflict error JSON response.
     */
    public function responseConflictError(
        string $details = 'conflict',
        string $message = 'Conflict!'
    ): JsonResponse {
        return $this->APIError(Response::HTTP_CONFLICT, $message, $details);
    }

    /**
     * Return an unprocessable entity error response.
     *
     * @param  mixed|null  $details  Optional error details.
     * @param  string|null  $message  Optional error message.
     * @return JsonResponse Unprocessable entity JSON response.
     */
    public function responseUnprocessable(mixed $details = null, ?string $message = null): JsonResponse
    {
        return $this->APIError(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $details);
    }

    /**
     * Create a JSON response for API errors.
     *
     * @param  int  $code  The HTTP status code for the error.
     * @param  string|null  $title  A brief error description (default: generic message).
     * @param  mixed|null  $details  Additional details about the error (default: null).
     * @return JsonResponse A JSON response containing the error information.
     */
    private function APIError(int $code, ?string $title, mixed $details = null): JsonResponse
    {
        // If no title is provided, use a generic error message.
        $formattedTitle = $title ?? 'Oops. Something went wrong. Please try again or contact support';

        // Create the JSON response with error information.
        $responseData = [
            'errors' => [
                [
                    'status' => $code,
                    'title' => $formattedTitle,
                    'detail' => $details,
                ],
            ],
        ];

        // Set the Content-Type header to specify JSON problem format.
        $headers = [
            'Content-Type' => 'application/problem+json',
        ];

        return new JsonResponse($responseData, $code, $headers);
    }
}
