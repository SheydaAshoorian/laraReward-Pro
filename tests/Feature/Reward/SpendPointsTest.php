<?php

use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('deducts points successfully when user has enough balance', function () {
    // ۱. ساخت یک کاربر فرضی که ۱۰۰ امتیاز دارد
    $user = User::factory()->create([
        'points_balance' => 100
    ]);


    $response = postJson('/api/spend-points', [
        'points' => 80,
        'item_name' => 'Lucky Wheel Ticket'
    ]);


    $response->assertStatus(200)
        ->assertJsonFragment([
            'current_points' => 20
        ]);


        assertDatabaseHas('users', [
        'id' => $user->id,
        'points_balance' => 20
    ]);


    assertDatabaseHas('point_logs', [
        'user_id' => $user->id,
        'points' => -80,
        'reason' => 'Purchased: Lucky Wheel Ticket'
    ]);
});

it('prevents deducting points when user balance is insufficient', function () {

$user = User::factory()->create([
        'points_balance' => 50
    ]);


    $response = postJson('/api/spend-points', [
        'points' => 80,
        'item_name' => 'Premium Reward'
    ]);


    $response->assertStatus(422)
        ->assertJsonFragment([
            'error' => 'امتیاز شما کافی نیست.'
        ]);


        expect($user->fresh()->points_balance)->toBe(50);
});