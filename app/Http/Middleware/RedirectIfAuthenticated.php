public function handle(Request $request, Closure $next, string ...$guards): Response
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            return redirect('/admin/dashboard'); // ✅ Yeh sahi URL hai?
        }
    }

    return $next($request);
}