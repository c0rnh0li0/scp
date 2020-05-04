<?php

namespace App\Http\Controllers;

use App\Ticket;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public function monthlytickets() {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        /*$sql = "SELECT SUM(`amount`) AS `amount`,
                       `created_at` 
                FROM `tickets` 
                WHERE `created_at` BETWEEN '$start' AND '$end'
                and `tickets`.`deleted_at` is null 
                group by CAST(`created_at` AS DATE)";*/

        $allTicketsData = DB::table('tickets')
            ->select(DB::raw('SUM(`amount`) AS `amt`, CAST(`created_at` AS DATE) AS `date`, 0 AS `used`'))
            ->whereBetween('created_at', ["$start", "$end"])
            ->groupBy(DB::raw('CAST(`created_at` AS DATE), `used`'))
            ->get();

        $usedTicketsData = DB::table('tickets')
            ->select(DB::raw('SUM(`amount`) AS `amt`, CAST(`created_at` AS DATE) AS `date`, `used`'))
            ->where('used', '=',  1)
            ->whereBetween('created_at', ["$start", "$end"])
            ->groupBy(DB::raw('CAST(`created_at` AS DATE), `used`'))
            ->get();

        //DB::enableQueryLog();
        //dd(DB::getQueryLog());

        $data = [
            'labels' => [],
            'datasets' => [
                'all' => [
                    'data' => []
                ],
                'used' => [
                    'data' => []
                ]
            ]
        ];

        foreach($allTicketsData as $t){
            array_push($data['labels'], Carbon::parse($t->date)->format('d F'));
            array_push($data['datasets']['all']['data'], $t->amt);
        }

        foreach($usedTicketsData as $t){
            array_push($data['datasets']['used']['data'], $t->amt);
        }

        return response()->json([
            'success' => true,
            'chartdata' => $data
        ]);
    }

    public function yearlytickets() {
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now();

        //DB::enableQueryLog();

        $allTicketsData = DB::table('tickets')
            ->select(DB::raw('SUM(`amount`) AS `amt`, MONTH(`created_at`) AS `month`'))
            ->whereBetween('created_at', ["$start", "$end"])
            ->groupBy(DB::raw('MONTH(`created_at`)'))
            ->get();

        $usedTicketsData = DB::table('tickets')
            ->select(DB::raw('SUM(`amount`) AS `amt`, MONTH(`created_at`) AS `month`'))
            ->where('used', '=',  1)
            ->whereBetween('created_at', ["$start", "$end"])
            ->groupBy(DB::raw('MONTH(`created_at`)'))
            ->get();


        //dd(DB::getQueryLog());

        $period = CarbonPeriod::create($start, $end)->month();

        $months = collect($period)->map(function (Carbon $date) {
            return [
                'month' => $date->month,
                'name' => $date->monthName,
                'days' => $date->daysInMonth,
            ];
        });

        $data = [
            'labels' => [],
            'datasets' => [
                'all' => [
                    'data' => []
                ],
                'used' => [
                    'data' => []
                ]
            ]
        ];

        foreach($months as $m) {
            array_push($data['labels'], $m['name']);

            $allVal = $allTicketsData->firstWhere('month', $m['month']);
            array_push($data['datasets']['all']['data'], $allVal ? $allVal->amt : 0);

            $usedVal = $usedTicketsData->firstWhere('month', $m['month']);
            array_push($data['datasets']['used']['data'], $usedVal ? $usedVal->amt : 0);
        }

        return response()->json([
            'success' => true,
            'chartdata' => $data
        ]);
    }

    public function yearlyvisitors() {
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now();

        //DB::enableQueryLog();

        $yearlyVisitsData = DB::table('tickets')
            ->select(DB::raw('COUNT(`user_id`) AS `amt`, MONTH(`used_at`) AS `month`'))
            ->where('used', '=',  1)
            ->whereBetween('created_at', ["$start", "$end"])
            ->groupBy(DB::raw('MONTH(`used_at`)'))
            ->get();

        //dd($yearlyVisitsData);

        //dd(DB::getQueryLog());

        $period = CarbonPeriod::create($start, $end)->month();

        $months = collect($period)->map(function (Carbon $date) {
            return [
                'month' => $date->month,
                'name' => $date->monthName,
                'days' => $date->daysInMonth,
            ];
        });

        $data = [
            'labels' => [],
            'datasets' => [
                'all' => [
                    'data' => []
                ],
                'used' => [
                    'data' => []
                ]
            ]
        ];

        foreach($months as $m) {
            array_push($data['labels'], $m['name']);

            $allVal = $yearlyVisitsData->firstWhere('month', $m['month']);
            array_push($data['datasets']['all']['data'], $allVal ? $allVal->amt : 0);
        }

        return response()->json([
            'success' => true,
            'chartdata' => $data
        ]);
    }

    public function dailyticketsdata()
    {
        $todayStart = Carbon::now()->startOfDay();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $todayEnd = Carbon::now()->endOfDay();

        //DB::enableQueryLog();

        $todayTickets = DB::table('tickets')
            ->select(DB::raw('SUM(`amount`) AS `amt`'))
            ->whereBetween('created_at', ["$todayStart", "$todayEnd"])
            ->get();

        $weekTickets = DB::table('tickets')
            ->select(DB::raw('SUM(`amount`) AS `amt`'))
            ->whereBetween('created_at', ["$weekStart", "$todayEnd"])
            ->get();

        $monthTickets = DB::table('tickets')
            ->select(DB::raw('SUM(`amount`) AS `amt`'))
            ->whereBetween('created_at', ["$monthStart", "$todayEnd"])
            ->get();

        //dd(DB::getQueryLog());

        return response()->json([
            'success' => true,
            'chartdata' => [
                'today' => $todayTickets[0]->amt ? (int)$todayTickets[0]->amt : 0,
                'week' => $weekTickets[0]->amt ? (int)$weekTickets[0]->amt : 0,
                'month' => $monthTickets[0]->amt ? (int)$monthTickets[0]->amt : 0,
            ]
        ]);
    }

    public function dailyvisitorsdata() {
        $todayStart = Carbon::now()->startOfDay();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $todayEnd = Carbon::now()->endOfDay();

        //DB::enableQueryLog();

        $todayVisitors = DB::table('logins')
            ->select(DB::raw('COUNT(`user_id`) AS `amt`'))
            ->whereBetween('created_at', ["$todayStart", "$todayEnd"])
            ->get();

        $weekVisitors = DB::table('tickets')
            ->select(DB::raw('COUNT(`user_id`) AS `amt`'))
            ->whereBetween('created_at', ["$weekStart", "$todayEnd"])
            ->get();

        $monthVisitors = DB::table('tickets')
            ->select(DB::raw('COUNT(`user_id`) AS `amt`'))
            ->whereBetween('created_at', ["$monthStart", "$todayEnd"])
            ->get();

        //dd(DB::getQueryLog());

        return response()->json([
            'success' => true,
            'chartdata' => [
                'today' => $todayVisitors[0]->amt ? (int) $todayVisitors[0]->amt : 0,
                'week' => $weekVisitors[0]->amt ? (int) $weekVisitors[0]->amt : 0,
                'month' => $monthVisitors[0]->amt ? (int) $monthVisitors[0]->amt : 0,
            ]
        ]);
    }

    public function dailytouristsdata() {
        $todayStart = Carbon::now()->startOfDay();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $todayEnd = Carbon::now()->endOfDay();

        //DB::enableQueryLog();

        $todayUsers = DB::table('users')
            ->select(DB::raw('COUNT(`id`) AS `amt`'))
            ->whereBetween('created_at', ["$todayStart", "$todayEnd"])
            ->get();

        $weekUsers = DB::table('users')
            ->select(DB::raw('COUNT(`id`) AS `amt`'))
            ->whereBetween('created_at', ["$weekStart", "$todayEnd"])
            ->get();

        $monthUsers = DB::table('users')
            ->select(DB::raw('COUNT(`id`) AS `amt`'))
            ->whereBetween('created_at', ["$monthStart", "$todayEnd"])
            ->get();

        //dd(DB::getQueryLog());

        return response()->json([
            'success' => true,
            'chartdata' => [
                'today' => $todayUsers[0]->amt ? (int) $todayUsers[0]->amt : 0,
                'week' => $weekUsers[0]->amt ? (int) $weekUsers[0]->amt : 0,
                'month' => $monthUsers[0]->amt ? (int) $monthUsers[0]->amt : 0,
            ]
        ]);
    }

    public function dailyvisitsdata() {
        $todayStart = Carbon::now()->startOfDay();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $todayEnd = Carbon::now()->endOfDay();

        //DB::enableQueryLog();

        $todayVisits = DB::table('tickets')
            ->join('offers', 'offers.id', '=', 'tickets.offer_id')
            ->join('users', 'users.id', '=', 'offers.owner_id')
            ->select(DB::raw('COUNT(`users`.`id`) AS `places`, `users`.`name`'))
            ->where('tickets.used', '=', 1)
            ->whereBetween('used_at', ["$todayStart", "$todayEnd"])
            ->groupByRaw('`users`.`id`')
            ->get();
        //dd(DB::getQueryLog());

        $weekVisits = DB::table('tickets')
            ->join('offers', 'offers.id', '=', 'tickets.offer_id')
            ->join('users', 'users.id', '=', 'offers.owner_id')
            ->select(DB::raw('COUNT(`users`.`id`) AS `places`, `users`.`name`'))
            ->where('tickets.used', '=', 1)
            ->whereBetween('used_at', ["$weekStart", "$todayEnd"])
            ->groupByRaw('`users`.`id`')
            ->get();

        $monthVisits = DB::table('tickets')
            ->join('offers', 'offers.id', '=', 'tickets.offer_id')
            ->join('users', 'users.id', '=', 'offers.owner_id')
            ->select(DB::raw('COUNT(`users`.`id`) AS `places`, `users`.`name`'))
            ->where('tickets.used', '=', 1)
            ->whereBetween('used_at', ["$monthStart", "$todayEnd"])
            ->groupByRaw('`users`.`id`')
            ->get();

        //dd(DB::getQueryLog());

        return response()->json([
            'success' => true,
            'chartdata' => [
                'today' => $todayVisits->count(),
                'week' => $weekVisits->count(),
                'month' => $monthVisits->count(),
            ]
        ]);
    }

    public function mostvisitedplaces() {
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfDay();

        $mostVisitedPlaces = DB::table('tickets')
            ->join('offers', 'offers.id', '=', 'tickets.offer_id')
            ->join('users', 'users.id', '=', 'offers.owner_id')
            ->select(DB::raw('COUNT(`users`.`id`) AS `visits`, `users`.`name`'))
            ->where('tickets.used', '=', 1)
            ->whereBetween('used_at', ["$start", "$end"])
            ->groupByRaw('`users`.`id`')
            ->get();

        //DB::enableQueryLog();
        //dd(DB::getQueryLog());

        $data = [];

        foreach($mostVisitedPlaces as $t) {
            array_push($data, [
                'name' => $t->name,
                'visits' => $t->visits
            ]);
        }

        return response()->json([
            'success' => true,
            'chartdata' => $data
        ]);
    }
}
