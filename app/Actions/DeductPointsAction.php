<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InsufficientPointsException;

class DeductPointsAction
{


    public function execute(User $user, int $points, string $reason): array
    {

    return DB::transaction(function () use ($user, $points, $reason) {
            

    $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();


    if ($lockedUser->points_balance < $points) {
                throw new InsufficientPointsException();
            }


            $lockedUser->decrement('points_balance', $points);


            $lockedUser->pointLogs()->create([
                'points' => -$points,
                'reason' => $reason,
            ]);


  
        });
    }
}