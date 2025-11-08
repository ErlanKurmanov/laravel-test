<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Interfaces\TicketServiceInterface;
use Illuminate\Http\JsonResponse;
use Exception;

class TicketController extends Controller
{
    public function __construct(protected TicketServiceInterface $ticketService)
    {
    }

    /**
     * @OA\Post(
     * path="/api/tickets",
     * summary="Create new query",
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * type="object",
     * required={"customer_name", "customer_email", "customer_phone", "subject", "body"},
     * @OA\Property(property="customer_name", type="string", example="Иван Иванов"),
     * @OA\Property(property="customer_email", type="string", format="email", example="ivan@test.com"),
     * @OA\Property(property="customer_phone", type="string", example="+79001234567"),
     * @OA\Property(property="subject", type="string", example="Проблема с заказом"),
     * @OA\Property(property="body", type="string", example="Не могу войти..."),
     * @OA\Property(property="files[]", type="string", format="binary")
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Success",
     * @OA\JsonContent(ref="#/components/schemas/TicketResource")
     * ),
     * @OA\Response(response=422, description="Error validation")
     * )
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        try {
            $ticket = $this->ticketService->createFromWidget($request->validated());

            return (new TicketResource($ticket))
                ->response()
                ->setStatusCode(201);

        } catch (Exception $e) {
            report($e);
            return response()->json(['message' => 'Internal Server Error.'], 500);
        }
    }
}
