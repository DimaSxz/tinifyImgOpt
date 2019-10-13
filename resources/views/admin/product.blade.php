<div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="d-flex w-100 justify-content-between">
                    <h2>Управление <b>товарами</b></h2>
                    <a href="#productModal" class="btn btn-primary" data-toggle="modal">
                        <span>Добавить</span>
                    </a>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <a href="#productModal" class="btn btn-warning" data-product="{{ $product }}" data-toggle="modal">
                            <i class="material-icons warning" data-toggle="tooltip" title="Edit">&#xE254;</i>
                        </a>
                        <button class="btn btn-danger" data-toggle="modal" onclick="confirm('Удалить {{ $product->name }}?') ? location.href='/delproduct/{{ $product->id }}' : ''">
                            <i class="material-icons danger" data-toggle="tooltip" title="Delete">&#xE872;</i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Edit Modal HTML -->
<div id="productModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="productForm" method="post" action="/product/">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Новый товар</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Название</label>
                        <input type="text" id="productName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Описание</label>
                        <textarea id="productDescription" name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Цена</label>
                        <input type="number" step="0.01" id="productPrice" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Категории</label>
                        <select name="categories[]" id="productCategories" class="form-control" multiple>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <a id="lfmProduct" data-input="productThumbnail" data-preview="productHolder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Выбрать
                            </a>
                            </span>
                            <input id="productThumbnail" class="form-control" type="text" name="filepath">
                        </div>
                        <img id="productHolder" style="margin-top:15px;max-height:100px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Сохранить">
                </div>
            </form>
        </div>
    </div>
</div>

