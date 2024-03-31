<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class getTrimester extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (!function_exists('getTrimester')) {
            function getTrimester($month)
            {
                switch ($month) {
                    case 1:
                    case 2:
                    case 3:
                        return "04";
                        break;
                    case 4:
                    case 5:
                    case 6:
                        return "01";
                        break;
                    case 7:
                    case 8:
                    case 9:
                        return "02";
                        break;
                    case 10:
                    case 11:
                    case 12:
                        return "03";
                        break;
                    default:
                        return "Bulan tidak valid";
                }
                // switch ($month) {
                //     case 1:
                //     case 2:
                //     case 3:
                //         return "01";
                //         break;
                //     case 4:
                //     case 5:
                //     case 6:
                //         return "02";
                //         break;
                //     case 7:
                //     case 8:
                //     case 9:
                //         return "03";
                //         break;
                //     case 10:
                //     case 11:
                //     case 12:
                //         return "04";
                //         break;
                //     default:
                //         return "Bulan tidak valid";
                // }
            }
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
