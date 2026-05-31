<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class DeductPointsAction
{


    public function execute(User $user, int $points, string $reason): void
    {

    DB::transaction(function () use ($user, $points, $reason) {
            

    $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();


    if ($lockedUser->points_balance < $points) {
                throw new Exception('امتیاز شما کافی نیست.');
            }


            $lockedUser->decrement('points_balance', $points);


            $lockedUser->pointLogs()->create([
                'points' => -$points,
                'reason' => $reason,
            ]);
        });
    }
}