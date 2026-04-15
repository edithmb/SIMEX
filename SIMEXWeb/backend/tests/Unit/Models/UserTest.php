<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use App\Models\LoginSession;
use App\Models\Document;
test('user has correct fillable attributes', function () {
    $fillable = (new User())->getFillable();

    expect($fillable)->toContain('role_id', 'client_id', 'first_name', 'last_name', 'email', 'password_hash', 'phone_number', 'is_active');
});

test('user has correct hidden attributes', function () {
    $hidden = (new User())->getHidden();

    expect($hidden)->toContain('password_hash', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token');
});

test('user uses soft deletes', function () {
    $user = User::factory()->create();
    $user->delete();

    expect($user->trashed())->toBeTrue();
    expect(User::withTrashed()->find($user->id))->not->toBeNull();
});

test('getAuthPassword returns password_hash', function () {
    $user = User::factory()->create(['password_hash' => bcrypt('test')]);

    expect($user->getAuthPassword())->toBe($user->password_hash);
});

test('is_active is cast to boolean', function () {
    $user = User::factory()->create(['is_active' => 1]);

    expect($user->is_active)->toBeBool()->toBeTrue();
});

test('scopeActive filters inactive users', function () {
    User::factory()->create(['is_active' => true]);
    User::factory()->create(['is_active' => false]);

    expect(User::active()->count())->toBe(1);
});

test('user belongs to role', function () {
    $role = Role::factory()->create(['name' => 'admin']);
    $user = User::factory()->create(['role_id' => $role->id]);

    expect($user->role)->toBeInstanceOf(Role::class);
    expect($user->role->name)->toBe('admin');
});

test('user belongs to client', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create(['client_id' => $client->id]);

    expect($user->client)->toBeInstanceOf(Client::class);
    expect($user->client->id)->toBe($client->id);
});

test('user has many login sessions', function () {
    $user = User::factory()->create();
    LoginSession::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->loginSessions)->toHaveCount(2);
});

test('user has many documents', function () {
    $user = User::factory()->create();
    Document::factory()->count(3)->create(['uploaded_by' => $user->id]);

    expect($user->documents)->toHaveCount(3);
});

test('user implements jwt subject interface', function () {
    $user = User::factory()->create();

    expect($user->getJWTIdentifier())->toBe($user->id);
    expect($user->getJWTCustomClaims())->toBe([]);
});
