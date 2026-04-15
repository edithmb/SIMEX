<?php

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use Illuminate\Validation\Rules\Password;

// Helper classes to expose protected methods for testing
class TestPasswordRules
{
    use PasswordValidationRules;

    public function getPasswordRules(): array
    {
        return $this->passwordRules();
    }

    public function getCurrentPasswordRules(): array
    {
        return $this->currentPasswordRules();
    }
}

class TestProfileRules
{
    use ProfileValidationRules;

    public function getNameRules(): array
    {
        return $this->nameRules();
    }

    public function getEmailRules(?int $userId = null): array
    {
        return $this->emailRules($userId);
    }
}

test('passwordRules returns required confirmed rules', function () {
    $obj = new TestPasswordRules();
    $rules = $obj->getPasswordRules();

    expect($rules)->toBeArray();
    expect($rules[0])->toBe('required');
    expect($rules[1])->toBe('string');
    expect($rules[2])->toBeInstanceOf(Password::class);
    expect($rules[3])->toBe('confirmed');
});

test('currentPasswordRules returns required current_password rules', function () {
    $obj = new TestPasswordRules();
    $rules = $obj->getCurrentPasswordRules();

    expect($rules)->toContain('required', 'string', 'current_password');
});

test('nameRules returns required string max 255', function () {
    $obj = new TestProfileRules();
    $rules = $obj->getNameRules();

    expect($rules)->toContain('required', 'string', 'max:255');
});

test('emailRules returns email validation rules', function () {
    $obj = new TestProfileRules();
    $rules = $obj->getEmailRules();

    expect($rules)->toBeArray();
    expect($rules)->toContain('required', 'string', 'email', 'max:255');
});
