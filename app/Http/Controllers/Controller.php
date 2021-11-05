<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Locale;
use Nette\Utils\Json;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Changes the current language and returns to previous page
     * @param string $locale
     * @return RedirectResponse
     * @throws Exception
     */
    final public function changeLang(Request $request): Response
    {
        app()->setLocale(Locale::getPrimaryLanguage($request->input('language', app()->getLocale())));
        return response()->noContent();
    }

    /**
     * Change theme in session
     *
     * @param string $theme
     * @return Response
     */
    final public function changeTheme(Request $request): Response
    {
        /** @noinspection UnusedFunctionResultInspection */
        session(['theme' => $request->input('theme', session('theme'))]);
        return response()->noContent();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function getLibraries(Request $request): JsonResponse
    {
        $offset = $request->input('offset', 0);
        $length = $request->input('length');

        $libraries = cache('libraries', null);
        if (!$libraries) {
            $required = Arr::get(Json::decode(File::get(base_path('composer.json')), Json::FORCE_ARRAY), 'require');
            $installed = Json::decode(File::get(base_path('vendor/composer/installed.json')))->packages;

            $packages = collect($installed)
                ->reject(fn ($package) => !Str::is(array_merge(array_keys($required), ['ext-*', 'php']), $package->name))
                ->keyBy('name');

            $deps = collect(Json::decode(file_get_contents(base_path('package.json')))->dependencies)
                ->map(function ($version, $dependency) {
                    $dep = urlencode($dependency);
                    return Http::get("https://libraries.io/api/npm/$dep", [
                        'api_key' => config('api-keys.libraries_io')
                    ])->object();
                });

            $libraries = $packages->merge($deps)->sortBy('name');
            cache()->forever('libraries', $libraries);
        }

        return response()->json($libraries->slice($offset, $length));
    }
}
