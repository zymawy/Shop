<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel;

use Antvel\Antvel;
use Illuminate\Support\ServiceProvider;
use Antvel\Exceptions\UserModelDoesnotExistException;

class AntvelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // dd(Antvel::doesntHaveUserModel());
        // if (Antvel::doesntHaveUserModel()) {
        //    throw new UserModelDoesnotExistException;
        // }

         $this->loadTranslationsFrom(
            realpath(__DIR__ . '/../resources/lang')
        , 'antvel');

        if ($this->app->runningInConsole()) {
            $this->publishAntvel();
        }
    }

    /**
     * Publish the antvel configuration files.
     *
     * @return void
     */
    protected function publishAntvel()
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        $this->publishes([
            __DIR__ . '/../config/' => config_path()
        ], 'antvel-config');

         $this->publishes([
            __DIR__ . '/../database/seeds' => database_path('seeds')
        ], 'antvel-seeds');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/antvel'),
        ], 'antvel-trans');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
