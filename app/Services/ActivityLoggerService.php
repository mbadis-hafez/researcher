<?php

namespace App\Services;

use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Request;
use Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect as Crawler;
use Stevebauman\Location\Facades\Location;

class ActivityLoggerService
{
    public static function log($action, $details = null, $user = null)
    {
        // Skip if crawler
        if (Crawler::isCrawler()) {
            return;
        }

        $user = $user ?? auth()->user();
        $ip = Request::ip();

        // Get location data
        $location = Location::get($ip);
        
        // Parse user agent
        $userAgent = Request::userAgent();
        $browser = self::getBrowser($userAgent);
        $platform = self::getPlatform($userAgent);
        $device = self::getDevice($userAgent);

        UserActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
            'country' => $location->countryName ?? null,
            'city' => $location->cityName ?? null,
            'action' => $action,
            'details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }

    private static function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'MSIE') !== false) return 'Internet Explorer';
        if (strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';
        if (strpos($userAgent, 'Firefox') !== false) return 'Mozilla Firefox';
        if (strpos($userAgent, 'Chrome') !== false) return 'Google Chrome';
        if (strpos($userAgent, 'Safari') !== false) return 'Apple Safari';
        if (strpos($userAgent, 'Opera') !== false) return 'Opera';
        return 'Unknown Browser';
    }

    private static function getPlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'Mac';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iPhone') !== false) return 'iOS';
        return 'Unknown Platform';
    }

    private static function getDevice($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) return 'Mobile';
        if (strpos($userAgent, 'Tablet') !== false) return 'Tablet';
        return 'Desktop';
    }
}