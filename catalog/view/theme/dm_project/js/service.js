//Ajax product Card change
function changeProductCard(product_id, active) {
    if (!active) {
        var current_category_id = $('.chart-item span.variants-item[data-product-id = "' + product_id + '"]').data('currentCategoryId');

        $.ajax({
            url: 'index.php?route=service/product_change/changeProductCard',
            type: 'POST',
            dataType: 'json',
            data: {
                'product_id': product_id,
                'current_category_id': current_category_id
            },
            beforeSend: function () {
                $('#preloader').fadeIn().show();
            },
            complete: function () {
                setTimeout(function () {
                    $("#preloader").delay(100).fadeOut().hide();
                }, 400);
            },
            success: function (json) {
                history.replaceState(null, null, json['link']);
                $("link[rel='canonical']").attr('href', json['link']);

                document.title = json['meta_data']['meta_title'];
                $("meta[name='description']").attr('content', json['meta_data']['meta_description']);
                $("meta[name='keywords']").attr('content', json['meta_data']['meta_keyword']);

                if (json['category_name']) {
                    $('.breadcrumb li.penult a').html(json['category_name']);
                    $('.breadcrumb li.penult a').attr('href', json['category_href']);
                    $('span.parent-product-title').html(json['category_name']);

                    $('.chart-item span.variants-item').data('currentCategoryId', json['category_id']).attr('data-current-category-id', json['category_id']);
                }

                $('.breadcrumb li.end span').html(json['heading-title']);

                $('h1.h1-product-title').html(json['heading-title']);

                $('#product-specification .product-name #product-model').html(json['model']);

                $('.product-info').html(json['info']);

                $('div#product-images div.slider').html(json['images']);

                if (document.getElementById('tab-nav-tabs')) {
                    $('#tab-nav-tabs').replaceWith(json['nav_tabs']);
                } else if (json['nav_tabs']) {
                    $('#product-specification').after(json['nav_tabs']);
                }

                if (document.getElementById('related-products')) {
                    $('#related-products').replaceWith(json['related']);
                } else if (json['related']) {
                    $('#col-product-info').append(json['related']);
                }

                autoheight();

                $('.chart-item span.variants-item.active').removeClass('active');
                $('.chart-item span.variants-item[data-product-id = "' + product_id + '"]').addClass('active');
            }
        });
    }
}

//Ajax product change into Category list product
function changeProduct(product_id, category_id, category_parent_id, active) {
    if (!active) {
        var thisProductThumb = '.product-thumb[data-category-id = "' + category_id + '"]';

        $.ajax({
            url: 'index.php?route=service/product_change/changeProduct',
            type: 'POST',
            dataType: 'json',
            data: {
                'product_id': product_id,
                'category_id': category_id,
                'category_parent_id': category_parent_id
            },
            beforeSend: function () {
                $('#preloader').fadeIn().show();
            },
            complete: function () {
                setTimeout(function () {
                    $("#preloader").delay(100).fadeOut().hide();
                }, 400);
            },
            success: function (json) {
                $(thisProductThumb + ' .variant-item-preview img.image-preview').attr({
                    'src': json['thumb'],
                    'title': json['product_name'],
                    'alt': json['product_name']
                });

                $(thisProductThumb + ' .product-info').html(json['info']);

                $(thisProductThumb + ' a.category-href').attr("href", json['product_link']);
                $(thisProductThumb + ' a.preview-href').attr("href", json['product_link']);
                $(thisProductThumb + ' a.popup_view_button').attr('onclick', 'getPopupView("' + product_id + '");');

                if (document.querySelector(thisProductThumb + ' a.preview-href > .special-percent')) {
                    $(thisProductThumb + ' a.preview-href > .special-percent').replaceWith(json['percent']);
                } else if (json['percent']) {
                    $(thisProductThumb + ' a.preview-href').prepend(json['percent']);
                }

                $(thisProductThumb + ' span.variants-item.active').removeClass('active');
                $(thisProductThumb + ' span.variants-item[data-product-id = "' + product_id + '"]').addClass('active');
            }
        });
    }
}

//Popup Logout
function AjaxLogout() {
    $.ajax({
        url: 'index.php?route=account/login_ajax/logout',
        beforeSend: function () {
            $('#preloader').fadeIn().show();
        },
        success: function () {
            location.reload();
        }
    });
}

//Popup Login
function getPopupLogin() {
    $.magnificPopup.open({
        tLoading: '<img src="image/preload/ring-alt.svg"/>',
        items: {
            src: 'index.php?route=account/login_ajax/login',
            type: 'ajax'
        }
    });
}

//Fast-view
function getPopupView(product_id) {
    $.magnificPopup.open({
        tLoading: '<img src="image/preload/ring-alt.svg"/>',
        items: {
            src: 'index.php?route=common/popup_view&product_id=' + product_id,
            type: 'ajax'
        }
    });
}

//Popup cart
function getPopupCart() {
    $.magnificPopup.open({
        tLoading: '<img src="image/preload/ring-alt.svg"/>',
        items: {
            src: 'index.php?route=common/popup_cart',
            type: 'ajax'
        }
    });
}

//Preloader triggers
$(function() {
    var triggerClick = [
        'header #logo a',
        '#menu .nav > li a',
        '#menu-desktop .nav > li a',
        '#menu-desktop table.nav > tbody > tr > td a',
        '#category-wall .category-wall-item a',
        '#tab-specification a',
        '#carousel-manufacturers a',
        'button#price-filter',
        '.breadcrumb li a',
        '.product-manufacturer #content a',
        '.product-thumb .variant-item-preview a.preview-href',
        '.product-thumb .variant-item-preview a.category-href',
        '.manufacturers-filter a',
        '.pagination a',
        'a.category-list-item',
        'a.filter-reset',
        'input#input-in-stock',
        'footer ul > li > a'
    ];
    $(triggerClick.join()).click(function () {
        $('#preloader').fadeIn().show();
    });

    var triggerChange = [
        'select#input-sort',
        'select#input-limit'
    ];
    $(triggerChange.join()).change (function () {
        $('#preloader').fadeIn().show();
    })
});
