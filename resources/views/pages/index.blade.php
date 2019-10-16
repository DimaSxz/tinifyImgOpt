@extends('layouts.app')

@section('content')
<section class="jumbotron text-center">
    <h1 class="jumbotron-heading">{{ env('APP_NAME') }}</h1>
</section>
<div class="container">
    <div class="row">
        @foreach ($categories as $category)
            <div class="col-md-4">
                <div class="card mb-4 box-shadow">
                    @if ($category->img_path)
                        <img class="card-img-top"
                             width="{{ env('IMG_THUMB_WIDTH') }}"
                             height="{{ env('IMG_THUMB_HEIGHT') }}"
                             src="{{ imageHelper('category_thumb', $category->img_path) }}" alt="{{ $category->name }}">
                    @endif
                    <div class="card-body">
                        <h4 class="card-title">{{ $category->name }}</h4>
                        <p class="card-text">{{ $category->description }}</p>
                    </div>
                    <div class="card-body">
                        <a href="/category/{{ $category->id }}">Подробнее</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
