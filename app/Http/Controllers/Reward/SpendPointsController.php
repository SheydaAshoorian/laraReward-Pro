<?php

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Actions\DeductPointsAction;
use App\Http\Requests\Reward\SpendPointsRequest;
use Illuminate\Http\JsonResponse;

class SpendPointsController extends Controller
{
    
    public function __invoke(SpendPointsRequest $request, DeductPointsAction $deductAction): JsonResponse
    {

    $result = $deductAction->execute(
            $request->user(),
            $request->validated()['points'],
            $request->validated()['item_name']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'امتیاز با موفقیت کسر شد و کد تخفیف شما صادر گردید.',
            'data' => $result
        ], 200);
    }
}