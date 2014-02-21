/*

*/
(function ($) {

    var obj;
    var errors = 0;
    var topId = "";
    var options;

    var methods = {
        init: function (options) {
            var defaults = {
                backgroundColor: '#ffffff',
                errorBackgroundColor: '#FFEBE8',
                border: 'solid 2px #D4D4D4',
                errorBorder: 'solid 2px #C00',
                summary: 'false',
                backgroundNone: false
            };
            options = $.extend(defaults, options);

            obj = $(this);
            obj.addClass('form-container');

            //setup default form buttons
            var $btn = $('.default-button');
            var $form = $btn.parents('.form-container');

            $form.keypress(function (e) {
                if (e.which == 13 && e.target.type != 'textarea') {
                    $btn[0].click();
                    return false;
                }
            });
            //obj.find('input').css({ border: options.border });
            //obj.find('select').css({ border: options.border });
            /*
            //bind click event to validate form
            alert("return $('#" obj.attr("id") + "').validate() " + $btn.attr("onclick"));
            $btn.attr("onclick","return $('#" obj.attr("id") + "').validate() " + $btn.attr("onclick"));
            */
            var clickEvent = $btn.attr("onclick");
            $btn.removeAttr("onclick");
            $btn.bind("click", function () {
                if (methods.validate(options)) {
                    eval(clickEvent);
                }
            })

        },
        validate: function (options) {
            errors = 0;
            topId = "";
            //check requireds
            obj.find('.required').each(function () {
            	if ($(this).is(":visible") || methods.getElementType($(this)) == "hidden") {
                	methods.required(options, $(this));
                }
            });
            if (errors == 0) {
                return true;
            }
            else {
                if (topId != '') {
                    $('#' + topId).focus();
                }
                return false;
            }
        },
        getElementType: function (element) {
            var type = '';
            var tagname = $(element).prop('tagName').toLowerCase();
            if (tagname == 'input') {
                type = $(element).attr('type');
            }
            else {
                type = tagname;
            }
            return type;
        },
        required: function (options, element) {
            var type = methods.getElementType(element);
            if (type == 'checkbox') {
                //get name
                var name = $(element).attr('name');
                if ($(":checkbox[name='" + name + "']").is(":checked")) {
                    // one or more checked, we're good
                }
                else {
                    // nothing checked
                    $(":checkbox[name='" + name + "']").next().css({
                        'color': options.errorBorder,
                        'backgroundColor': options.errorBackgroundColor
                    });
                    $(":checkbox[name='" + name + "']").css({
                        'backgroundColor': options.errorBackgroundColor,
                        'border': options.errorBorder
                    });
                    $(element).bind('click', function () {
                        if ($(":checkbox[name='" + name + "']").is(":checked") > 0) {
                            $(":checkbox[name='" + name + "']").css({
                                'backgroundColor': options.backgroundColor,
                                'border': options.border
                            });
                            $(":checkbox[name='" + name + "']").next().css({
                                'backgroundColor': options.backgroundColor
                            });
                        }
                    });
                    errors++;
                    if (topId == '') {
                        topId = $(element).attr('id');
                    }
                }
            }
            else {
                if ($(element).val().length == 0) {
                    if (type == 'div') {
                        if ($(element).hasClass('file')) {
                            if ($(element).find('.uploaded-file').length == 0) {
                                alert('At least one file required!');
                            }
                            errors++;
                            if (topId == '') {
                                topId = $(element).attr('id');
                            }
                        }
                    }
                    else if (type == 'hidden')
		            {
			            //check for a requiredby element
			            var requiredById = $(element).attr('requiredby');
			            $('#'+requiredById).css({
                            'backgroundColor': options.errorBackgroundColor,
                            'border': options.errorBorder
                        });
                        if (type == 'select') {
                            $('#'+requiredById).bind('click', function () {
                                if ($(element).val().length > 0) {
                                    $('#'+requiredById).css({
                                        'backgroundColor': options.backgroundColor,
                                        'border': options.border
                                    });
                                }
                            });
                        }
                        else {
                            $('#'+requiredById).bind('blur', function () {
                                if ($(element).val().length > 0) {
                                    $('#'+requiredById).css({
                                        'backgroundColor': options.backgroundColor,
                                        'border': options.border
                                    });
                                }
                            });
                        }
                        errors++;
                        if (topId == '') {
                            topId = $('#'+requiredById).attr('id');
                        }		            
		            }
                    else {
                        $(element).css({
                            'backgroundColor': options.errorBackgroundColor,
                            'border': options.errorBorder
                        });
                        if (type == 'select') {
                            $(element).bind('click', function () {
                                if ($(element).val().length > 0) {
                                    $(element).css({
                                        'backgroundColor': options.backgroundColor,
                                        'border': options.border
                                    });
                                }
                            });
                        }
                        else {
                            $(element).bind('blur', function () {
                                if ($(element).val().length > 0) {
                                    $(element).css({
                                        'backgroundColor': options.backgroundColor,
                                        'border': options.border
                                    });
                                }
                            });
                        }
                        errors++;
                        if (topId == '') {
                            topId = $(element).attr('id');
                        }
                    }
                }
            }
        },
        minlength: function (value, element, param) {
            return this.optional(element) || this.getLength($.trim(value), element) >= param;
        },
        maxlength: function (value, element, param) {
            return this.optional(element) || this.getLength($.trim(value), element) <= param;
        },
        rangelength: function (value, element, param) {
            var length = this.getLength($.trim(value), element);
            return this.optional(element) || (length >= param[0] && length <= param[1]);
        },
        min: function (value, element, param) {
            return this.optional(element) || value >= param;
        },
        max: function (value, element, param) {
            return this.optional(element) || value <= param;
        },
        range: function (value, element, param) {
            return this.optional(element) || (value >= param[0] && value <= param[1]);
        },
        email: function (value, element) {
            return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(value);
        },
        url: function (value, element) {
            return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
        },
        date: function (value, element) {
            return this.optional(element) || !/Invalid|NaN/.test(new Date(value));
        },
        dateISO: function (value, element) {
            return this.optional(element) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(value);
        },
        number: function (value, element) {
            return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
        },
        digits: function (value, element) {
            return this.optional(element) || /^\d+$/.test(value);
        }
    }

    $.fn.jvalidate = function (method) {

        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.jvalidate');
        }

    }
})(jQuery);