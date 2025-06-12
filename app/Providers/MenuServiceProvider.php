<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    View::composer('*', function ($view) {
      $verticalMenuFile = 'resources/menu/verticalMenu.json'; // Default menu

      if (Auth::check()) {
        if (Auth::user()->hasRole('artist')) {
          $verticalMenuFile = 'resources/menu/artistVerticalMenu.json';
        } elseif (Auth::user()->hasRole('researcher')) {
          $verticalMenuFile = 'resources/menu/researcherMenu.json';
        }
      }

      $verticalMenuData = json_decode(file_get_contents(base_path($verticalMenuFile)));
      $horizontalMenuData = json_decode(file_get_contents(base_path('resources/menu/horizontalMenu.json')));

      $view->with('menuData', [$verticalMenuData, $horizontalMenuData]);
    });
  }
}
