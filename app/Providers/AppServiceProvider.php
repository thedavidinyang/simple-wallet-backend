<?php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;
use App\Repositories\UserRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerApiResponseMacro();

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(200)->by($request->user()?->id ?: $request->ip());
        });

    }


      /**
     * Creates a Response macro for API json responses having a standard format;
     */

     public function registerApiResponseMacro(): void
     {
        
        Response::macro('api', function (string $message = '', $data = [], $status = 'success', $code = 200, array $headers = []) {
             return response()->json([
                 'status'  => $status == "success" ? true : false,
                 'message' => $message,
                 'data'    => $data
             ], $code, $headers);
         });

     }
}
