<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestPaymobController extends Controller
{
    public function callBack(Request $request)
    {
        return $request->all();
    }
}
