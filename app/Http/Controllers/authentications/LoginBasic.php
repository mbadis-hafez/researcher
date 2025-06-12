<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\ActivityLoggerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): RedirectResponse
  {
    $request->authenticate();

        ActivityLoggerService::log('login');

    $request->session()->regenerate();

    // Custom redirect based on user role
    if ($request->user()->hasRole('superadmin')) {
      return redirect()->route('dashboard-analytics');
    } elseif ($request->user()->hasRole('researcher')) {
      return redirect()->route('researcher_board');
    } elseif ($request->user()->hasRole('user')) {
      return redirect()->route('users.users.index');
    }

    return redirect()->to(route('dashboard-analytics'));
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): RedirectResponse
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }
}
