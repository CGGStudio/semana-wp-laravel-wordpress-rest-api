@extends('layouts.app')

@section('head_title', $settings->title)
@section('wordpress_title', $settings->title)
@section('site_heading_title', $settings->title)
@section('site_subheading_title', $settings->description)
@section('header_background_image', 'img/home-bg.jpg')

@section('main-content')
    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                @foreach($posts as $post)
                    <div class="post-preview">
                        <a href="{{ url($post->slug) }}">
                            <h2 class="post-title">
                                {{ $post->title->rendered }}
                            </h2>
                        </a>
                        <p class="post-meta">Publicado
                            @if (isset($post->_embedded->author))
                                por {{ $post->_embedded->author[0]->name }}
                            @endif
                            el {{ \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $post->date_gmt)->setTimezone('Europe/Madrid')->format('d/m/Y') }}
                        </p>
                        <p>
                            {!! $post->content->rendered !!}
                        </p>
                    </div>
                    <hr>
            @endforeach
            <!-- Pager -->
                <div class="clearfix" id="pagination">
                    @for($i = 1; $i <= $numeroDePaginas; $i++)
                        @if ($i == $paginaActual)
                            <span class="active"> {{  $i  }}</span>
                        @else
                            <a href="/?page={{$i}}">{{$i}}</a>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <hr>
    </div>
@endsection
