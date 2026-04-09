<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminSessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        $timeoutSeconds = 30 * 60;
        $lastActivity = (int) $request->session()->get('admin_last_activity', 0);

        if ($lastActivity > 0 && (time() - $lastActivity) > $timeoutSeconds) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Sesi admin berakhir karena tidak ada aktivitas selama 30 menit. Silakan login kembali.']);
        }

        $request->session()->put('admin_last_activity', time());

        return $next($request);
    }
}
