if (typeof jQuery === 'function')
{
    (function($)
    {
        $.fn.login = function(options)
        {
            // set defaults
            var
                defaults = {
                    name : 'login'
                },
                opts = $.extend( defaults, options );

            return this.each(function() {
                $this = $(this);
                initEvents();
            });

            /**
             * Login check
             * @param form
             * @param event
             * @returns {boolean}
             */
            function submit_ajaxLogin(form, event)
            {
                event.preventDefault();

                $.ajax({
                    url : form.attr('action'),
                    type : 'POST',
                    data : form.serialize(),
                    dataType : 'json',
                    cache : false,
                    async : true,
                    beforeSend : function() {
                        $(':submit', form).addClass('disabled');
                        form.closest('.login-container').fadeTo('slow', '0.5');
                    },
                    success : function(response) {

                        if (response.type === 'error')
                        {
                            form.closest('.login-container').fadeTo('slow', 1);
                            alert(response.message);
                            return false;
                        }

                        // success
                        if (response.type === 'success')
                        {
                            window.location.href = response.message;
                        }

                    },
                    error : function()
                    {
                        form.closest('.login-container').fadeTo('slow', 1);
                    },
                    complete : function() {
                        $(':submit', form).removeClass('disabled');
                    }
                });
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
        if ($.fn.login)
        {
            $('body').login();
        }
    });
}

