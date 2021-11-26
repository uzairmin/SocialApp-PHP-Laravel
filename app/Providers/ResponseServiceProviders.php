<?php

namespace App\Providers;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProviders extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success',function(){
            return response()->json([
                'success' => true,
                'message' => "Done",
            ],200);

        });
        Response::macro('error',function($data, $status_code){
            return response()->json([
                'success' => false,
                'message' => $data['message'],
                'error'   => $data['error'],
            ],$status_code);
        });
    }
}
