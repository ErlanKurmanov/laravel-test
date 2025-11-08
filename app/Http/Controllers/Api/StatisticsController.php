<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/tickets/statistics",
     * summary="Get statistics",
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="daily", type="integer", example=5),
     * @OA\Property(property="weekly", type="integer", example=25),
     * @OA\Property(property="monthly", type="integer", example=110)
     * )
     * )
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $stats = [
            'daily' => Ticket::createdThisDay()->count(),
            'weekly' => Ticket::createdThisWeek()->count(),
            'monthly' => Ticket::createdThisMonth()->count(),
        ];

        return response()->json(['data' => $stats]);
    }
}
