function createParentProduct(token) {
    let selectedProduct = $('#form-product').serialize();

    if (selectedProduct === "") return alert("Вы не выбрали ни одного товара из списка!");

    $.magnificPopup.open({
        items: {
            src: 'index.php?route=service/create_parent_product/index&token=' + token + '&' + selectedProduct,
            type: 'ajax'
        }
    });
}
