<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil cookie visitor
        $token = $request->cookie('visitor_token');

        if ($token) {

            $visitor = Visitor::where('visitor_token', $token)->first();

            if ($visitor) {

                // Cooldown 15 menit
                if ($visitor->last_visit->diffInMinutes(now()) >= 15) {

                    $visitor->increment('visit_count');

                    $visitor->update([
                        'last_visit' => now()
                    ]);
                }
            }
        }

        return $next($request);
    }
}
