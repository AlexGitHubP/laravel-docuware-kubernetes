<?php

namespace CodebarAg\DocuWare\Support;

use CodebarAg\DocuWare\Exceptions\UnableToFindUrlCredential;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Auth
{
    const COOKIE_NAME = '.DWPLATFORMAUTH';

    const CACHE_KEY = 'docuware.cookies';

    const FALLBACK_CACHE_DRIVER = 'file';

    public static function store(CookieJar $cookies): void
    {
        $cookie = collect($cookies->toArray())
            ->reject(fn (array $cookie) => Arr::get($cookie, 'Value') === '')
            ->firstWhere('Name', self::COOKIE_NAME);

        $now = Carbon::now()->format('Y-m-d H:i:s');
        DB::insert('insert into documents_kubernetes (cookie_name, cookie_value, created_at, updated_at) values (?, ?, ?, ?)', [$cookie['Name'], $cookie['Value'], $now, $now]);
    }

    public static function cookies(): ?array
    {
        return Cache::driver(self::cacheDriver())->get(self::CACHE_KEY);
    }

    public static function cookieJar(): ?CookieJar
    {
        if (! self::cookies()) {
            return null;
        }

        return CookieJar::fromArray(self::cookies(), self::domain());
    }

    public static function cookieDate(): string
    {
        return Arr::get(Cache::driver(self::cacheDriver())->get(self::CACHE_KEY), 'CreatedAt');
    }

    public static function forget(): void
    {
        Cache::driver(self::cacheDriver())->forget(self::CACHE_KEY);
    }

    public static function domain(): string
    {
        throw_if(
            empty(config('docuware.credentials.url')),
            UnableToFindUrlCredential::create(),
        );

        return Str::of(config('docuware.credentials.url'))
            ->after('//')
            ->beforeLast('/')
            ->__toString();
    }

    public static function check(): bool
    {
        return DB::table('documents_kubernetes')->exists();
    }

    protected static function cacheDriver(): string
    {
        return config('docuware.cache_driver', self::FALLBACK_CACHE_DRIVER);
    }
}
