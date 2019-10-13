@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-category-tab" data-toggle="tab" href="#nav-category"
                           role="tab" aria-controls="nav-category" aria-selected="false">Категории</a>
                        <a class="nav-item nav-link" id="nav-product-tab" data-toggle="tab" href="#nav-product"
                           role="tab" aria-controls="nav-product" aria-selected="false">Товары</a>
                        <a class="nav-item nav-link" id="nav-filemanager-tab" data-toggle="tab" href="#nav-filemanager"
                           role="tab" aria-controls="nav-filemanager" aria-selected="false">Файловый менеджер</a>
                    </div>
                </nav>
                <div class="card">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-category" role="tabpanel" aria-labelledby="nav-category-tab">
                            <div class="card-body">
                                @include ('admin.category')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-product" role="tabpanel" aria-labelledby="nav-product-tab">
                            <div class="card-body">

                                @include('admin.product')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-filemanager" role="tabpanel"
                             aria-labelledby="nav-filemanager-tab">
                            <div class="card-body">
                                <iframe src="/laravel-filemanager?type=image"
                                        style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @push('extended-js')

        <script defer src="/vendor/laravel-filemanager/js/lfm.js"></script>
        <script defer src="/js/admin.js"></script>

    @endpush
@endsection

