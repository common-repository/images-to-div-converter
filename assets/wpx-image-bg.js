jQuery(document).ready(function () {
    //Run after each 0.1 min
    setInterval(function () {
        if (jQuery(".variable-item-contents").length) {
            jQuery(".variable-item-contents").each(function () {
                if (jQuery(this).find('img').hasClass('lazyloaded')) {
                    var src = jQuery(this).find('img').attr('src');
                    if (typeof src != 'undefined') {
                        jQuery(this).addClass('custom-bg');
                        jQuery(this).css('background-image', 'url(' + src + ')');
                        jQuery(this).find('img').hide();
                    }
                }
            });
        }
        if (jQuery(".flex-control-nav li").length) {
            jQuery(".flex-control-nav li").each(function () {
                var src = jQuery(this).find('img').attr('src');
                if (typeof src != 'undefined') {
                    jQuery(this).addClass('wpx-main-image-thumbnail');
                    jQuery(this).css('background-image', 'url(' + src + ')');
                }
            });
        }
        if (jQuery(".woocommerce-product-gallery__image").length) {
            jQuery(".woocommerce-product-gallery__image").each(function () {
                var ele = jQuery(this).find('a');
                var src = ele.find('img').attr('src');
                if (typeof src != 'undefined') {
                    ele.addClass('wpx-main-image');
                    ele.css('background-image', 'url(' + src + ')');
                }
            });
        }

    }, 100);
});