<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userEmail = $request->session()->get('user_email');

        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();

            if ($user && $user->role === 'admin') {
                return $next($request);
            }
        }

        // Jika bukan admin, arahkan kembali ke home dengan pesan error
        return redirect('/home')->with('error', 'Anda tidak memiliki hak akses admin!');
    }
}
