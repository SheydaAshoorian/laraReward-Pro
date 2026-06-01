<?php

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Actions\DeductPointsAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class SpendPointsController extends Controller
{
    public function __invoke(Request $request, DeductPointsAction $deductAction): JsonResponse
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'item_name' => 'required|string'
        ]);

        try {
          
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $deductAction->execute($user, (int) $request->points, "Purchased: " . $request->item_name);

            return response()->json([
                'message' => 'امتیاز با موفقیت کسر شد و جایزه خریداری شد.',
                'current_points' => $user->fresh()->points_balance
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}