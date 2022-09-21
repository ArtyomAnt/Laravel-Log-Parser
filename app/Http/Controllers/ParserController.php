<?php

namespace App\Http\Controllers;

use App\Services\LogReader;

class ParserController extends Controller
{
    public function index()
    {
        $test = new LogReader('/logs/data.log');

        $test->readLogAndDispatchMessages();
    }
}
