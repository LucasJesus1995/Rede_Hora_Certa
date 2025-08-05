<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_date_range', function ($attribute, $value, $parameters, $validator) {

            $dateBeginning = Carbon::createFromFormat('d/m/Y', Input::get($parameters[0]));
            $dateEnd = Carbon::createFromFormat('d/m/Y', $value);

            return $dateBeginning->diffInMonths($dateEnd) <= $parameters[1];
        });

        Validator::replacer('valid_date_range', function($message, $attribute, $rule, $parameters){
            return str_replace(':periodo', $parameters[1] + 1, $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
