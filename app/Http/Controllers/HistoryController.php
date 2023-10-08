<?php

namespace App\Http\Controllers;

use App\Models\UserPointHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function get() {
        $history = UserPointHistory::all();
        return $history;
    }

}
