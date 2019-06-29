function createParentProduct() {
    let selectedProduct = $('#form-product').serialize();

    if (selectedProduct === "") return alert("Вы не выбрали ни одного товара из списка!");

    $.magnificPopup.open({
        items: {
            src: 'index.php?route=service/parent_product/index&token=' + getURLVar('token') + '&' + selectedProduct,
            type: 'ajax'
        }
    });
}
