 if (window.jQuery) {

    var access_token = $('meta[name="csrf-token"]').attr('content');

    $(document).ready( function(){
        if(typeof $().ionRangeSlider === 'function') {
            $(".range").ionRangeSlider({
                onFinish: function(data) {
                    var input = $(data.input.data('input'));
                    if(input) {
                        input.val(data.input.val());
                        input.prop('checked', true);
                        input.trigger('input');
                    }
                }
            });
        }
        if(typeof $().rating === 'function') {
            $('.rating').rating('refresh', {
                clearCaption: 'Evalueaza',
                starTitles: function(val) {
                    return val+'/5';
                },
                starCaptions: function(val) {
                    return val+'/5';
                }
            });
        }
        $('.uncheck-others').on('input', function() {
            $('[name="'+this.name+'"]').not(this).prop('checked', false);
        });
        $('[name^="search"]').on('input', function() {
            var input = $(this).data('range') ? $(this).data('range') : null;
            if(this.type == 'checkbox' && this.checked && input) {
                this.value = $(input).val();
            }
            getFilteredProducts();
        });

        $('#top-cart-close').on('click', function(event) {
            $('#top-cart').toggleClass('top-cart-open', false);
        });

        $('#shop, #header, #cart, .shop').on('click', '[data-cart-route]', function() {
            if(this.tagName != 'SELECT' && this.tagName != 'TEXTAREA') {
                updateCart(this);
            }
        });

        var typingTimer;
        $('textarea[data-cart-route], input[data-cart-route]').on('input', function() {
            clearTimeout(typingTimer);
            let el = this;
            typingTimer = setTimeout(function(){ updateCart(el); }, 1000);
        });
    });

    function getLocalitatiInOptions(input, location) {
        input = $(input);
        $.post({
            url: input.data('url') + ( input.val() ?  '/' + input.val() : ''),
            headers: {'X-CSRF-TOKEN': access_token},
            success: (data) => {
                $(location).html(data);
                $(location).trigger('change');
            }
        });
    }

    function getLocalitatiInOptionsWithEmpty(input, location) {
        input = $(input);
        $.post({
            url: input.data('url') + ( input.val() ?  '/' + input.val() : ''),
            headers: {'X-CSRF-TOKEN': access_token},
            success: (data) => {
                $(location).html('<option value=""></option>'+data);
                $(location).trigger('change');
            }
        });
    }

    function getFilteredProducts() {
        var url = $('[data-search-route]').data('search-route');
        if(url) {
            var i = 0;
            $('[name^="search"]').each(function( index ) {
                if(inputValid(this)) {
                    url += (i == 0 ? '?' : '&') + encodeURI(this.name + '=' + this.value);
                    i++;
                }
            });
            $.get({
                url: url + (i == 0 ? '?' : '&') + 'only_prod=1',
                headers: {'X-CSRF-TOKEN': access_token},
                beforeSend: (data) => {
                    $('#shop .product').addClass('d-none');
                    $('#shop nav').addClass('d-none');
                    $('#shop').append($('<div class="text-center"><i id="products-spinner" class="icon-line-loader icon-spin"></i> Se incarca</div>'));
                },
                success: (data) => {
                    window.history.pushState({}, '', url);
                    $('#shop').html(data);
                },
                error: (data) => {
                    $('#shop nav').removeClass('d-none');
                },
                complete: (data) => {
                    $('#products-spinner').remove();
                    $('#shop .product').removeClass('d-none');
                },
            });
        }
    }

    function getNewShippingPrice(input) {
        var input = $(input);
        var dataset = input.data();
        var url = dataset['url'];
        if(url && $(dataset['input']).prop('checked') == dataset['value'] && $(input).val()) {
            var post = {'localitate': $(input).val()};
            $.post({
                url: url,
                headers: {'X-CSRF-TOKEN': access_token},
                data: post,
                beforeSend: (data) => {
                    let spinner = $('<i data-shipping-spinner="1" class="icon-line-loader icon-spin"></i>');
                    $('.amount.shipping').append(spinner.clone());
                    $('.amount.total').append(spinner.clone());
                },
                success: (data) => {
                    $('.amount.shipping').html(data[0]);
                    $('.amount.total').html(data[1]);
                },
                error: (data) => {
                    showErrorMessage(data.responseText);
                },
                complete: (data) => {
                    $('[data-shipping-spinner]').remove();
                },
            });
        }
    }

    function updateCart(input) {
        var input = $(input);
        var dataset = input.data();
        if(dataset['disabled'] != true) {
            var post = dataset['reload'] == true 
                ? { 'redirect': true }
                : {};
            if(dataset['value'] != undefined) {
                var field = $(dataset['value']);
                var name = field.attr('name');
                var val = field.val();
                post[name] = val;
            }
            $.post({
                url: dataset['cartRoute'],
                headers: {'X-CSRF-TOKEN': access_token},
                data: post,
                beforeSend: (data) => {
                    var spinner = $('<i id="products-spinner" class="icon-line-loader icon-spin"></i>');
                    var container = $('<div class="text-center">'+spinner+' Se incarca</div>');
                    input.data('disabled', true);
                    input.children().addClass('d-none');
                    input.append(spinner);
                    $('#top-cart .top-cart-item').addClass('d-none');
                    $('#top-cart #top-cart-items').append(container);
                },
                success: (data) => {
                    if(dataset['reload'] == true) {
                        location.reload();
                    } else {
                        $('#top-cart .top-cart-content').html($(data).find('.top-cart-content').html());
                        $('#top-cart #top-cart-trigger').html($(data).find('#top-cart-trigger').html());
                    }
                },
                error: (data) => {
                    if(dataset['reload'] == true) {
                        location.reload();
                    } else {
                        if(data.responseJSON.message) {
                            var time = 5000; 
                            var toast = '<div class="notify" data-notify-timeout="'+time+'" data-notify-msg="A avut loc o eroare. Incercati mai tarziu" data-notify-type="errormsg style-msg2 rounded-0"></div>';
                            var notify = $('body').append(toast).find('.notify');
                            notify.attr('data-notify-msg', data.responseJSON.message);
                            SEMICOLON.widget.notifications(notify);
                            setTimeout(() => { $(notify.data('notify-target')).closest('.position-fixed').remove(); notify.remove(); }, time + 500);
                        }
                    }
                },
                complete: (data) => {
                    input.data('disabled', false);
                    input.find('#products-spinner').remove();
                    input.children().removeClass('d-none');
                    $('#top-cart .top-cart-item').removeClass('d-none');
                    if(dataset['open'] != false && dataset['reload'] != true) {
                        $('#top-cart').addClass('top-cart-open');
                    }
                },
            });
        }
    }

    function inputValid(el) {
        if (el.disabled) return false;
        if (el.value == '') return false;
        if ((el.type === "radio" || el.type === "checkbox") && !el.checked) return false;
        return true;
    }

    function showErrorMessage(message = null) {
        var time = 5000; 
        var toast = '<div class="notify" data-notify-timeout="'+time+'" data-notify-msg="A avut loc o eroare. Incercati mai tarziu" data-notify-type="errormsg style-msg2 rounded-0"></div>';
        var notify = $('body').append(toast).find('.notify');
        if(message) {
            notify.attr('data-notify-msg', message);
        }
        SEMICOLON.widget.notifications(notify);
        setTimeout(() => { $(notify.data('notify-target')).closest('.position-fixed').remove(); notify.remove(); }, time + 500);
    }

    const delay = async t => new Promise(resolve => setTimeout(resolve, t));
}