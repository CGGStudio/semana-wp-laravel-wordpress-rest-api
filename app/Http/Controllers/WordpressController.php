<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class WordpressController extends Controller
{
    private $url = 'http://wordpress.test/wp-json/wp/v2/';

    public function index() {
        $cliente = new Client();
        $response = $cliente->get($this->url . 'menu');
        $menu = json_decode($response->getBody());
        return view('index', compact('menu'));
    }
}
