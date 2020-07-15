<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware1
{
    private \Illuminate\Auth\AuthManager $authManager;
    private \Illuminate\Routing\Redirector $redirector;
    private \Illuminate\Routing\UrlGenerator $urlGenerator;
    public function __construct(\Illuminate\Contracts\Auth\Factory $authManager, \Illuminate\Routing\Redirector $redirector, \Illuminate\Contracts\Routing\UrlGenerator $urlGenerator)
    {
        $this->authManager = $authManager;
        $this->redirector = $redirector;
        $this->urlGenerator = $urlGenerator;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//    public function handle($request, Closure $next)
//    {
//        if (!Auth::guest() && Auth::user()->hasAdmin())
//        {
//            return $next($request);
//        }
//
//        return $this->redirector->back($this->urlGenerator->route('main'));
//    }

    public function handle($request, Closure $next)
    {
        if (!Auth::guest() && Auth::user()->hasAdmin())
        {
            return $next($request);
        }

        return $this->redirector->back($this->urlGenerator->route('main'));
    }
}
