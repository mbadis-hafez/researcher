<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LocaleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(LocaleMiddleware::class);
  })
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'role' => \App\Http\Middleware\RoleMiddleware::class,
      'permission' => \App\Http\Middleware\PermissionMiddleware::class,
      'language' => \App\Http\Middleware\LanguageManager::class,
      'log.activity' => \App\Http\Middleware\LogUserActivity::class,

    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();