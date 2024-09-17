<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string | null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = config("app.locale");

        return [
             ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn() => [
                 ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'status' => fn() => $request->session()->get('status'),
                'message' => fn() => $request->session()->get('message'),
            ],
            "appName" => config("app.name"),
            "localeFile" => function () use ($locale) {
                $localeFile = base_path('lang/' . $locale . '/' . $locale . '.json');

                if (!file_exists($localeFile)) {
                    return [];
                }

                return json_decode(file_get_contents($localeFile), true);
            },
        ];
    }
}
