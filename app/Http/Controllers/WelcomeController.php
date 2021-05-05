<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Goutte\Client as GoutteClient;
use App\Models\Constellation;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
