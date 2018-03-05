<?php

namespace App\Http\Controllers\Brokers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BrokerController extends Controller
{
    /**
     * Display the dashboard for the broker
     *
     * @return Response
     */
    public function index()
    {
        return view('broker.dashboard');
    }

}
