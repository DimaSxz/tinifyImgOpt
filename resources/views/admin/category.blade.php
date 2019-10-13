<div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="d-flex w-100 justify-content-between">
                    <h2>Управление <b>категориями</b></h2>
                    <a href="#categoryModal" class="btn btn-primary" data-toggle="modal">
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
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="#categoryModal" class="btn btn-warning" data-category="{{ $category }}" data-toggle="modal">
                                <i class="material-icons warning" data-toggle="tooltip" title="Edit">&#xE254;</i>
                            </a>
                            <button class="btn btn-danger" data-toggle="modal" onclick="confirm('Удалить {{ $category->name }}?') ? location.href='/delcategory/{{ $category->id }}' : ''">
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
<div id="categoryModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="categoryForm" method="post" action="/category/">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Новая категория</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Название</label>
                        <input type="text" id="categoryName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Описание</label>
                        <textarea id="categoryDescription" name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <a id="lfmCategory" data-input="categoryThumbnail" data-preview="categoryHolder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Выбрать
                            </a>
                            </span>
                            <input id="categoryThumbnail" class="form-control" type="text" name="filepath">
                        </div>
                        <img id="categoryHolder" style="margin-top:15px;max-height:100px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Сохранить">
                </div>
            </form>
        </div>
    </div>
</div>

