function createParentProduct(token) {
    var selectedProduct = $('#form-product').serialize();

    if (selectedProduct === "") return alert("Вы не выбрали ни одного товара из списка!");

    $.magnificPopup.open({
        items: {
            src: 'index.php?route=service/create_parent_product/index&token=' + token + '&' + selectedProduct,
            type: 'ajax'
        }
    });
}

function quickEditProduct(product_id, token) {
    $.magnificPopup.open({
        items: {
            src: 'index.php?route=service/quick_edit_product/index&product_id=' + product_id + '&token=' + token,
            type: 'ajax'
        }
    });
}
