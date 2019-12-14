<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class WordpressController extends Controller
{
    private $url = 'http://wordpress.test/wp-json/wp/v2/';
    protected $numeroDePaginas;
    protected $paginaActual;

    public function index(Request $request)
    {
        $menu = $this->getMenu();
        $settings = $this->getSettings();
        $posts = $this->getPosts($request);
        $numeroDePaginas = $this->numeroDePaginas;
        $paginaActual = $this->paginaActual;
        return view('index', compact('menu', 'settings', 'posts', 'numeroDePaginas', 'paginaActual'));
    }

    protected function getMenu()
    {
        $cliente = new Client();
        $response = $cliente->get($this->url . 'menu');
        return json_decode($response->getBody());
    }

    protected function getSettings()
    {
        $cliente = new Client([
            'auth' => [
                getenv('APPLICATION_PASSWORD_USERNAME'),
                getenv('APPLICATION_PASSWORD'),
            ]
        ]);
        $response = $cliente->get($this->url . 'settings');
        return json_decode($response->getBody());
    }

    protected function getPosts(Request $request)
    {
        if ($request->getRequestUri() === '/') {
            $this->paginaActual = 1;
        } else {
            $this->paginaActual = substr($request->getRequestUri(), 7);
        }
        $cliente = new Client();
        $response = $cliente->get($this->url . 'posts?_embed&per_page=5&page=' . $this->paginaActual);
        $this->numeroDePaginas = $response->getHeader('X-WP-TotalPages')[0];
        return json_decode($response->getBody());
    }
}
