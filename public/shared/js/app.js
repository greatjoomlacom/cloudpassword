if (typeof jQuery === 'function')
{
    (function($)
    {
        $.fn.app = function(options)
        {
            // set defaults
            var
                defaults = {
                    name : 'app'
                },
                opts = $.extend( defaults, options );

            return this.each(function() {
                $this = $(this);

                layout();

                // hovered class
                $('.ui-hovered-class', $this).hover(
                    function()
                    {
                        $(this).addClass('ui-state-hover');
                    },
                    function()
                    {
                        $(this).removeClass('ui-state-hover');
                    }
                );

                // focus field
                $('.focus-first', $this).trigger('focus.' + opts.name);

                hideLayersOnBody();

                combobox();

                // reset action on fields
                resetField();

                initEvents();
            });

            /**
             * Reset fields
             */
            function resetField()
            {
                var elements = $('button', $('form', $this)).filter(
                    function()
                    {
                        return $(this).data(opts.name + 'Reset');
                    }
                );

                elements.click(
                    function(e)
                    {
                        e.preventDefault();
                        if(typeof $(this).data(opts.name + 'Reset') === 'string')
                        {
                            var element = $('form input[name="' + $(this).data(opts.name + 'Reset') + '"]', $this);


                            element.val('');
                            element.closest('form').trigger('submit.' + opts.name);
                        }
                    }
                );

            };

            /**
             * Create a combobox
             */
            function combobox()
            {
                $('.tab-content .active').addClass('restore-active');
                $('.tab-content').children('div').addClass('active').show();

                // advanced filter routine
                $('.admin-filter-advanced-content', $this).show();

                var selects = $('form select', $this);

                selects.each(
                    function()
                    {
                        var select = $(this),
                            select_container = select.parent();

                        var holder = $('<div />', {
                        }),
                        button = $('<button />', {
                            'type' : 'button',
                            'html' : (select.children(':selected').data(opts.name + 'Prepend') || '') + select.children(':selected').text() + ' <span class="icon-caret-down"></span>',
                            'class' : 'combobox-button btn btn-default btn-block',
                            'data-toggle' : "dropdown"
                        }),
                        ul = $('<ul />', {
                            'class' : 'dropdown-menu col-lg-12',
                            'role' : 'menu',
                            'aria-labelledby' :'dLabel'
                        });

                        if(select.children().length > 1)
                        {
                            select.children().each(
                                function()
                                {
                                    var item = $(this),
                                        li =  $('<li />'),
                                        a = $('<a />', {
                                            'href' : '#' + item.attr('value'),
                                            'tabindex' : -1,
                                            'html' : (item.data(opts.name + 'Prepend') || '') + item.text()
                                        });

                                    if(select.children().length <= 1)
                                    {
                                        return true;
                                    }

                                    li.append(a);
                                    ul.append(li);

                                    if(item.is(':selected'))
                                    {
                                        li.hide();
                                    }

                                    a.on('click.' + opts.name, function(e) {
                                        e.preventDefault();

                                        var value = $(this).attr('href').substr(1,$(this).attr('href').length),
                                            text = $(this).html();
                                        select.val(value);

                                        // preserve caret
                                        var caret = $('span', button);
                                        button.html(text + ' ');
                                        button.append(caret);

                                        // show all UL > LI elements
                                        $('li:hidden', ul).show();
                                        a.parent('li').hide();
                                    });
                                }
                            );

                            holder.append(ul);
                        }

                        // change dropdown position
                        ul.css({
                            'left' : select.position().left + 'px',
                            'top' : (select.position().top + select.outerHeight() ) + 'px',
                            'width' : select.outerWidth() + 'px'
                        });

                        // hide select
                        select.addClass('visuallyhidden');

                        select_container.append(holder);
                        holder.append(button);

                    }
                );

                $('.administration .nav li a:first').trigger('click');

                // advanced filter routine
                $('.admin-filter-advanced-content', $this).hide();
            };

            /**
             * Hide tooggle layers
             */
            function hideLayersOnBody()
            {
                var layers = $('.toggle-layer', 'body');

                $('body').on(
                    'click.' + opts.name,
                    function(event)
                    {
                        layers.each(
                            function()
                            {
                                var layer = $(this);

                                // no layer opened
                                if (layer.is(':hidden')) return false;

                                if ($(event.target).is('a'))
                                {
                                    var a_data = $(event.target).data(opts.name + 'Event');

                                    if(typeof a_data !== 'undefined' && a_data[0].name === 'toggleLayer')
                                    {
                                        return false;
                                    }
                                }

                                if($(event.target).closest('.toggle-layer').length === 1)
                                {
                                    return false;
                                }

                                // close layer
                                layer.slideUp();

                            }
                        );
                    }
                );
            };

            /**
             * Submit form next to element
             * @param element
             * @param event
             */
            function click_submitFormNext(element, event)
            {
                event.preventDefault();
                element.next('form').trigger('submit.' + opts.name);
            };

            /**
             * Show prompt window with default value, no action
             * @param element
             * @param event
             */
            function click_promptWindowsWithValue(element, event)
            {
                event.preventDefault();

                var data = element.data(opts.name + 'Event')[0];

                prompt(data.prompt_message, data.value);

                return true;
            };

            /**
             * Show prompt window with default value, no action
             * @param element
             * @param event
             */
            function reset_clearFormValuesAndSubmit(element, event)
            {
                event.preventDefault();

                var form = element.closest('form');

                $('input, select, textarea').each(
                    function()
                    {
                        var element = $(this);

                        if(element.attr('name') !== '_token')
                        {
                            element.val('');
                        }

                    }
                );

                form.trigger('submit. ' + opts.name);
            };

            /**
             * Get password and copy to clipboard (prompt window actually)
             * @param element
             * @param event
             */
            function click_copyToClipboard(element, event)
            {
                event.preventDefault();

                var form = element.parent('form');

                $.ajax({
                    url : form.attr('action'),
                    type : 'POST',
                    data : form.serialize(),
                    dataType : 'json',
                    cache : false,
                    async : true,
                    beforeSend : function() {
                        $('body').css('cursor', 'wait');
                        $('.icon-copy', element).removeClass('icon-copy').addClass('icon-refresh');
                    },
                    success : function(response) {

                        if (response.type === 'error')
                        {
                            alert(response.message);
                            return false;
                        }

                        // success
                        if (response.type === 'success')
                        {
                            window.prompt(response.message.prompt_header, response.message.prompt_message);
                            return true;
                        }

                    },
                    complete : function() {
                        $('body').css('cursor', 'auto');
                        $('.icon-refresh', element).removeClass('icon-refresh').addClass('icon-copy');
                    }
                });
            };

            /**
             * Toggle next container
             * @param element
             * @param event
             */
            function click_toggleNextContainer(element, event)
            {
                event.preventDefault();

                var container = element.next();

                if(container.is(':hidden'))
                {
                    container.slideDown();
                }
                else
                {
                    container.slideUp();
                }

            };

            /**
             * Confirm before delete
             * @param form
             * @param event
             * @return boolean
             */
            function submit_confirmAction(form, event)
            {
                var confirmation = confirm(language.confirmation.delete_one);

                if (!confirmation)
                {
                    event.preventDefault();
                    return false;
                }
                return true;
            };

            /**
             * Toggle layer after element
             * @param element
             * @param event
             */
            function click_toggleLayer(element, event)
            {
                event.preventDefault();

                var layer = $('div').filter(
                    function()
                    {
                        return $(this).hasClass(element.data(opts.name + 'LinkedTo'));
                    }
                );

                if (layer.is(':hidden'))
                {
                    layer.appendTo('body');

                    layer.removeAttr('style').position({
                        my : 'center top',
                        at : 'center bottom+15px',
                        of : element
                    });

                    layer.slideDown();
                }
                else
                {
                    layer.slideUp();
                }

            };

            /**
             * Get cipher
             * @param form
             * @param event
             */
            function submit_passwordGeneratorCipher(form, event)
            {
                event.preventDefault();

                var layer = form.parent();

                $.ajax({
                    url : form.attr('action'),
                    type : 'POST',
                    data : form.serialize(),
                    dataType : 'json',
                    cache : false,
                    async : true,
                    beforeSend : function() {
                        $('body').css('cursor', 'wait');
                        $('button', form).attr('disabled', true);
                        layer.addClass('ui-widget-shadow');
                    },
                    success : function(response) {

                        if (response.type === 'error')
                        {
                            alert(response.message);
                            return false;
                        }

                        // success
                        if (response.type === 'success')
                        {
                            $('input[name="password"]').val(response.message);
                            return true;
                        }

                    },
                    complete : function() {
                        $('body').css('cursor', 'auto');
                        $('button', form).removeAttr('disabled');
                        layer.removeClass('ui-widget-shadow');
                    }
                });
            };


            /**
             * Layout
             */
            function layout()
            {
                // UI Buttons
                $( 'button, a', $this ).filter( function() {
                    return $(this).data( opts.name + '-ui-button' )  && typeof $(this).data('button') === 'undefined';
                } ).each( function() {
                    var	element = $(this),
                        data = element.data( opts.name + '-ui-button' );
                    element.button( (typeof data === 'object' ? data : null) );
                } );

                // tooltip
                $('input:text, input:checkbox, input:file, button, select, textarea, span, a', $this).filter(function()
                {
                    return $(this).attr('title') && $(this).data(opts.name + '-tooltip') == '1'  && typeof $(this).data('tooltip') === 'undefined';
                }).tooltip({
                    track : true,
                    content : function()
                    {
                        var content = $('<div />', {
                            html : $(this).attr('title')
                        });
                        return content.html();
                    }
                });

                // UI Accordion
                $( 'div', $this ).filter( function() {
                    return $(this).data( opts.name + '-ui-accordion' )  && typeof $(this).data('accordion') === 'undefined';
                } ).each( function() {
                        var	element = $(this),
                            data = element.data( opts.name + '-ui-accordion' );
                        element.accordion( (typeof data === 'object' ? data : null) );
                    } );
            };

            /**
             * Register Events
             */
            function initEvents()
            {
                var on_events = new Array();
                on_events[0] = {
                    element : 'form',
                    event : 'submit.' + opts.name
                },
                on_events[1] = {
                    element : 'a, button',
                    event : 'click.' + opts.name
                },
                on_events[2] = {
                    element : 'button',
                    event : 'reset.' + opts.name
                };

                $.each(on_events, function() {
                    $this.on( this.event, this.element, function(e) {
                        var element = $(this);

                        if(element.data(opts.name + '-event')) {
                            if (typeof element.data(opts.name + '-event') !== 'array') {
                                // make array
                                element.data(opts.name + '-event', $.makeArray(element.data(opts.name + '-event')));
                            }
                            $.each(element.data(opts.name + '-event'), function() {
                                if (eval('typeof ' + this.type + '_' + this.name) === 'function') {
                                    eval(this.type + '_' + this.name + '(element, e);');
                                }
                            });
                        }
                    } );
                });
            };
        };
    } )(jQuery);

    jQuery(document).ready(function($){
        if ($.fn.app) {
            $('body').app();
        }
    });
}

