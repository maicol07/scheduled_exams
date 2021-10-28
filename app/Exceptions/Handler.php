<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    final public function register()
    {
        $this->reportable(static function (Throwable $e) {
            //
        });
    }

    /**
     * Fixes phpstorm:// protocol links Ignition uses, which don't work on Linux or Windows
     */
    final public function render($request, Throwable $exception): Response|JsonResponse
    {
        if ('local' === app()->environment()) {
            $project_path = base_path();
            echo "
                <!--suppress JSUnresolvedLibraryURL -->
                <script src=\"https://cdnjs.cloudflare.com/ajax/libs/cash/8.1.0/cash.min.js\"></script>
                <script>
                    $(document).ready(function () {
                        // Get all phpstorm:// protocol links
                        const links = $('a[href^=phpstorm]');

                        links.each(function (index) {
                            const link = $(this);
                            let href = link.attr('href');

                            // Drop the protocol, which doesnt work on linux or windows
                            href = href.replace('phpstorm://open?', 'http://localhost:63342/api/file?');

                            // Add the project path so PHPstorm knows which window to make active
                            href = href + '&project=$project_path';
                            link.attr('href', href);

                            // Send as an XHR request, so we don't redirect the user or have to open a new window
                            link.on('click', function(e) {
                                e.preventDefault();

                                fetch(href);
                            })
                        })
                    });
                </script>";

        }

        return parent::render($request, $exception);
    }
}
