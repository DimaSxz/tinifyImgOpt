$(() => {
    $('#lfmCategory').filemanager('image');
    $('#lfmProduct').filemanager('image');

    const categoryForm = $('#categoryForm');
    const productForm = $('#productForm');

    $('a[href="#categoryModal"]').on('click', function () {
        const category = $(this).data('category');
        if(category) {
            categoryForm.attr('action', `/category/${category.id}`);
            categoryForm.find('.modal-title').text('Редактирование категории');
            categoryForm.find('#categoryName').val(category.name);
            categoryForm.find('#categoryDescription').val(category.description);
            categoryForm.find('#categoryThumbnail').val(category['img_path']);
            categoryForm.find('#categoryHolder').attr('src', category['img_path']);
        } else {
            categoryForm.attr('action', '/category');
            categoryForm.find('.modal-title').text('Новая категория');
            categoryForm.find('#categoryName').val('');
            categoryForm.find('#categoryDescription').val('');
            categoryForm.find('#categoryThumbnail').val('');
            categoryForm.find('#categoryHolder').attr('src', '');
        }
    });

    $('a[href="#productModal"]').on('click', function () {
        const product = $(this).data('product');
        if(product) {
            productForm.attr('action', `/product/${product.id}`);
            productForm.find('.modal-title').text('Редактирование товара');
            productForm.find('#productName').val(product.name);
            productForm.find('#productDescription').val(product.description);
            productForm.find('#productPrice').val(product.price);
            productForm.find('#productCategories').val(product.categories);
            productForm.find('#productThumbnail').val(product['img_path']);
            productForm.find('#productHolder').attr('src', product['img_path']);
        } else {
            productForm.attr('action', '/product');
            productForm.find('.modal-title').text('Новый товар');
            productForm.find('#productName').val('');
            productForm.find('#productDescription').val('');
            productForm.find('#productPrice').val('');
            productForm.find('#productCategories').val('');
            productForm.find('#productThumbnail').val('');
            productForm.find('#productHolder').attr('src', '');
        }
    });
});
