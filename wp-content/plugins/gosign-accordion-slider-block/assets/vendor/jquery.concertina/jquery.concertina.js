/*!
 * jQuery.concertina
 * v 0.0.0
 *
 * Yet another boring jQuery accordion plugin in very yearly development stage
 *
 *
 * Inspiration and original idea: accordionizer by Igor Dranichnikov & Aleksey Leshko:
 * http://github.com/i-dranichnikov/accordionizer
 *
 *
 * Copyright (c) 2014 Evgeny Pirogov
 * Licensed under the The MIT License (MIT)
 */

// TODO: image labels for 'image' mode
// TODO: implement vertical resize of elements in 'max' and 'min' height-calculation modes
// TODO: migrate from javascript calculations of element sizes to 'pure css' one
// TODO: smooth resize effect
// TODO: external controls
// TODO: external API
// TODO: 'html' mode
// TODO: canvas fallback for grayscale filter
// TODO: graceful degradation for older browsers
// TODO: jQuery.easing support
// TODO: jsDoc coverage
// TODO: documentation
// TODO: customization examples
// TODO: Grunt migration

(function ($, undefined) {

    var Concertina = function (parent, options) {

        this._parent = parent;
        this.options = this._verifyOptions(options);

        this._init();
    };

    Concertina.prototype = {

        _init: function () {
            this._setUniqueId();
            this._parent.className += (' concertina-mode_' + this.options.mode);
            this._runImageLoader();
        },

        _runImageLoader: function () {
            var items = this._parent.getElementsByTagName('li');
            this.itemsCount = items.length;
            this._itemsToLoad = items.length;
            this.items = [];
            for (var i = items.length; i > 0;) {
                var item = items[--i];
                item.dataset.itemIndex = i;
                this._requestItemLoad(item);
            }
            this._itemLoadStateChanged();
        },

        _requestItemLoad: function (item) {
            item.className += ' concertina-item';
            if (this.options.mode == 'image') {
                var image = item.getElementsByTagName('img');
                if (image.length && image[0].src) {
                    var concertinaInstance = this;
                    $(image)
                        .one('load', function () {
                            concertinaInstance._itemOnLoad(item, this);
                        })
                        .one('error', function () {
                            concertinaInstance._itemOnError(item, this);
                        })
                        .each(function () {
                            if (this.complete) {
                                $(this).load();
                            }
                        });
                } else {
                    this.itemsCount--;
                    this._itemsToLoad--;
                }
            }
        },

        _itemLoadStateChanged: function () {
            if (!this._itemsToLoad) {
                if (this.itemsCount)  {
                    this._initStyles();
                    this._initControls();
                    this._initRuntimeCallbacks();
                    this._applyCallback('onInit', 'success');
                } else {
                    this._applyCallback('onInit', 'error');
                }
            }
        },

        _itemOnLoad: function (item, loader) {
            item.style.backgroundImage = 'url(' + loader.src + ')';
            this._itemsToLoad--;
            this.items[item.dataset.itemIndex] = item;
            this._itemLoadStateChanged();
        },

        _itemOnError: function (item, loader) {
            this.itemsCount--;
            this._itemsToLoad--;
            this._itemLoadStateChanged();
        },

        _initStyles: function () {
            this._parent.style.maxWidth = 'none';
            var singleItemWidth = this._parent.clientWidth;
            this._parent.style.maxWidth = singleItemWidth + 'px';
            singleItemWidth -= (this.itemsCount - 1)*this.options.tabWidth;
            this.inactiveMargin = '-' + (singleItemWidth - this.options.tabWidth + 1) + 'px';


            for (var i = this.itemsCount; i > 0;) {
                var item = this.items[--i],
                    $item = $(item);

                $item.css({
                    width: singleItemWidth + 'px',
                    marginRight: (!$item.is('.active')) ? this.inactiveMargin : 0
                });

                if (this.options.height == 'min' || this.options.height == 'max') {
                    if (!this._calculatedHeight) {
                        this._calculatedHeight = item.clientHeight;
                    }
                    this._calculatedHeight = Math[this.options.height](item.clientHeight, this._calculatedHeight);
                }
            }
            this._parent.style.height = ((this._calculatedHeight) ? this._calculatedHeight : this.options.height) + 'px';
        },

        _initControls: function () {
            var concertinaInstance = this;
            for (var i = this.itemsCount; i > 0;) {
                $(this.items[--i])
                    .not('.concertina-item_control')
                    .addClass('concertina-item_control')
                    .on('click mouseover', function () {
                        $(this)
                            .addClass('active')
                            .css({
                                marginRight: 0
                            })
                            .siblings()
                            .removeClass('active')
                            .css({
                                marginRight: concertinaInstance.inactiveMargin
                            });
                    });
            }
            $(this.items[i]).trigger('click');
        },

        _initRuntimeCallbacks: function () {
            var instance = this;

            $(window).resize(function () {
                instance.resize();
            });
        },

        _isRenderFree: function () {
            return !this._renderBusy
        },

        _setRenderBusy: function () {
            var instance = this;
            this._renderBusy = true;
            clearTimeout(this._renderTimeout);
            this._renderTimeout = setTimeout(function () {
                instance._renderBusy = false;
            }, 20);
        },

        _applyCallback: function (callback, arguments) {
            if (typeof arguments == 'string' || typeof arguments == 'number' || typeof arguments == 'boolean') {
                arguments = [arguments];
            }
            try {
                this.options.callbacks[callback].apply(this, arguments)
            } catch (e) {
                console.log('jQuery.concertina callback execution error with callback name "%s" : %s', callback, e);
            }
        },

        _setUniqueId: function () {
            if (!this._parent.id) {
                var min = this.options.instancesEstimated,
                    max = this.options.instancesEstimated*5,
                    id;
                do {
                    id = 'concertina-' + (Math.round(Math.random() * (max - min)) + min);
                } while (!!document.getElementById(id));
                this._parent.id = id;
            }
        },

        _verifyOptions: function (options) {
            var defaults = Concertina._defaultOptions;

            // Options verification
            if ( $.isNumeric(options.height) && (options.height != 'min' && options.height != 'max') ) {
                options.height = defaults.height;
            }
            // TODO: change on implementing 'html' mode
            if (options.mode != 'image') {
                options.mode = 'image';
            }
            if (!$.isNumeric(options.tabWidth)) {
                options.tabWidth = defaults.tabWidth;
            }
            if (!$.isNumeric(options.instancesEstimated) || options.instancesEstimated < 1) {
                options.instancesEstimated = defaults.instancesEstimated;
            }

            // Callbacks verification
            for (var callback in options.callbacks) {
                if (options.callbacks.hasOwnProperty(callback) && typeof options.callbacks[callback] !== 'function') {
                    options.callbacks[callback] = (typeof defaults.callbacks[callback] == 'function') ?
                        defaults.callbacks[callback] :
                        function () {}
                }
            }

            return options;
        },

        resize: function () {
            if (this._isRenderFree()) {
                // TODO: fix redundant _initStyles call
                this._setRenderBusy();
                var oldWidth = this._parent.scrollWidth;
                this._initStyles();
                if (oldWidth != this._parent.scrollWidth) {
                    this._applyCallback('onAfterResize');
                }
            }
        }
    };

    Concertina._defaultOptions = {
        // Generic options:
        mode: 'image',
        height: 'min',
        tabWidth: 80,

        // Rarely required and almost useless options:
        instancesEstimated: 100,

        // Callbacks:
        callbacks: {
            onInit: function (status) {
                return status
            },
            onAfterResize: function () {
                return true
            }
        }
    };

    $.fn.concertina = function (options) {
        var _options = $.extend(true, {}, Concertina._defaultOptions, options);

        return this.each(function () {
            new Concertina(this, _options);
        });
    };

})(jQuery);
