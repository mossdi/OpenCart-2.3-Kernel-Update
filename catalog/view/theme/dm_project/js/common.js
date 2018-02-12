//Ajax product Card change
function changeProductCard(product_id, active) {
    if (!active) {
        var current_category_id = $('.chart-item span.variants-item[data-product-id = "' + product_id + '"]').data('currentCategoryId');

    	$.ajax({
            url: 'index.php?route=product/product_change/changeProductCard',
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
            url: 'index.php?route=product/product_change/changeProduct',
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

function productCardImages() {
    document.getElementById('blueimp').onclick = function (event) {
        $('#blueimp-gallery .slides').after('')

        event = event || window.event;
        var target = event.target || event.srcElement,
            link = target.src ? target.parentNode : target,
            options = {index: link, event: event},
            links = this.getElementsByClassName('thumbnail');
        blueimp.Gallery(links, options);
    };
    // The slider being synced must be initialized first
    $('div#slider').flexslider({
        animation: "slide",
        controlsContainer: $(".flexslider-container"),
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        prevText: '‹',
        nextText: '›',
        sync: "#carousel"
    });
    if (document.getElementById('carousel')) {
        $('div#carousel').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: false,
            prevText: '‹',
            nextText: '›',
            itemWidth: 250,
            itemMargin: 5,
            minItems: 6,
            asNavFor: '#slider'
        });
    }
}
        
//Price filter
function priceFilter(route) {
    var url = $('base').attr('href') + 'index.php?route=' + route;

    var minPrice = $('.price-gap input[name=\'min_price\']').val();
    var maxPrice = $('.price-gap input[name=\'max_price\']').val();

    if (minPrice && maxPrice) {
        url += '&min_price=' + encodeURIComponent(minPrice) + '&max_price=' + encodeURIComponent(maxPrice);
    }

    location = url;
}

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	} else { // Изменения для seo_url от Русской сборки OpenCart 2x
		var query = String(document.location.pathname).split('/');
		if (query[query.length - 1] == 'cart') value['route'] = 'checkout/cart';
		if (query[query.length - 1] == 'checkout') value['route'] = 'checkout/checkout';
		
		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {
	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').val($(this).attr('name'));

		$('#form-language').submit();
	});

	/* Search */
	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('header #search input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header #search input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 10) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
		$('#content .product-grid > .clearfix').remove();

		$('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
		$('#grid-view').removeClass('active');
		$('#list-view').addClass('active');

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
		// What a shame bootstrap does not take into account dynamically loaded columns
		var cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		$('#list-view').removeClass('active');
		$('#grid-view').addClass('active');

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
		$('#list-view').addClass('active');
	} else {
		$('#grid-view').trigger('click');
		$('#grid-view').addClass('active');
	}

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});
});

//Popup Login
function AjaxLogout() {
    $.ajax({
        url: 'index.php?route=account/logout_ajax/logout',
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
            src: 'index.php?route=account/login_ajax',
            type: 'ajax'
        }
    });
}

//Fast-view
function getPopupView(product_id) {
    $.magnificPopup.open({
        tLoading: '<img src="image/preload/ring-alt.svg"/>',
        items: {
            src: 'index.php?route=common/popup_view&product_id='+product_id,
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

//Popup window
$(document).ready(function() {
    function getPopupCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));

        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

	$.ajax({
		url: 'index.php?route=extension/module/popup_window/statusRequest',
		success: function (json) {
			if (json['status']) {
                if (!getPopupCookie('popup_window')) {
                	$('head').append('<link href="catalog/view/theme/dm_project/stylesheet/popup_window.css" rel="stylesheet" media="screen" />')
                    $(setTimeout(function() {
                        $.magnificPopup.open({
                            removalDelay: 300,
                            tLoading: '',
                            items: {
                                src: 'index.php?route=extension/module/popup_window',
                                type: 'ajax'
                            }
                        });
                    }, 3000));
                }
			}
        }
	});
});

// Cart add remove functions
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
                $('button[data-product-id = "' + product_id + '"]').button('loading');
                $('#cart > button').button('loading');
			},
			complete: function() {
                $('button[data-product-id = "' + product_id + '"]').button('reset');
                $('#cart > button').button('reset');
			},
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
                    getPopupCart();

					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart > button').html('<img src="/image/icons/cart-icon.png" alt="Cart"><span id="cart-total">' + json['total'] + '</span>');
					}, 100);

					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<img src="/image/icons/cart-icon.png" alt="Cart"><span id="cart-total">' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<img src="/image/icons/cart-icon.png" alt="Cart"><span id="cart-total">' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<img src="/image/icons/cart-icon.png" class="cart-img" alt="Cart"><span id="cart-total">' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);

$(document).ready(autoheight);
function autoheight() {
    //Auto-height col
    var max_col_height = 0;
    $('.auto-height').each(function () {
        if ($(this).height() > max_col_height) {
            max_col_height = $(this).height();
        }
    });
    if (max_col_height > 0) {
        $('.auto-height').height(max_col_height);
    }

    //Auto-height product-thumb h2
    var max_h2_height = 0;
    $('.product-thumb .caption h2').each(function () {
        if ($(this).height() > max_h2_height) {
            max_h2_height = $(this).height();
        }
    });
    if (max_h2_height > 0) {
        $('.product-thumb .caption h2').height(max_h2_height);
    }

    //Auto-height product-thumb h3
    var max_h3_height = 0;
    $('.product-thumb .caption h3').each(function () {
        if ($(this).height() > max_h3_height) {
            max_h3_height = $(this).height();
        }
    });
    if (max_h3_height > 0) {
        $('.product-thumb .caption h3').height(max_h3_height);
    }

    //Auto-height product-thumb h4
    var max_h4_height = 0;
    $('.product-thumb .caption h4').each(function () {
        if ($(this).height() > max_h4_height) {
            max_h4_height = $(this).height();
        }
    });
    if (max_h4_height > 0) {
        $('.product-thumb .caption h4').height(max_h4_height);
    }
}

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
