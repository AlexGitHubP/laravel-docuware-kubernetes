<?php

namespace CodebarAg\DocuWare\Tests\Feature;

use CodebarAg\DocuWare\Exceptions\UnableToLogout;
use CodebarAg\DocuWare\Facades\DocuWare;
use CodebarAg\DocuWare\Support\Auth;
use CodebarAg\DocuWare\Support\EnsureValidCookie;

uses()->group('authorization');

beforeEach(function () {
    Auth::forget();
    expect(Auth::cookies())->toBeNull();
});

it('can authenticate with a cookie', function () {
    if (config('docuware.cookies') === '') {
        $this->markTestSkipped('No cookie configured');
    }

    EnsureValidCookie::check();

    expect(Auth::cookies())->toHaveKey(Auth::COOKIE_NAME);
    expect(Auth::cookies()[Auth::COOKIE_NAME])->toBe(config('docuware.cookies'));
})->group('authorization');

it('can authenticate with no cookie', function () {
    config(['docuware.cookies' => '']);

    EnsureValidCookie::check();

    expect(Auth::cookies())->toHaveKey(Auth::COOKIE_NAME);
    expect(Auth::cookies()[Auth::COOKIE_NAME])->not->toBeNull();
    expect(Auth::cookies()[Auth::COOKIE_NAME])->not->toBe('foo');
})->group('authorization');

it('can not logout with a cookie', function () {
    if (config('docuware.cookies') === '') {
        $this->markTestSkipped('No cookie configured');
    }

    DocuWare::logout();
})->throws(UnableToLogout::class);

it('can logout with a cookie', function () {
    config(['docuware.cookies' => '']);

    DocuWare::logout();
})->doesNotPerformAssertions();
