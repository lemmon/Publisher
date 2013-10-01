//
// run
$(function(){
    
    var $notices = $('#notices');
    
    //
    // forms
    $('form[data-action]').each(function(){
        var $form = $(this),
            $submit = $form.find('button[type=submit]'),
            url = $form.data('action');
        $form.submit(function(){
            $submit.attr('disabled', true).addClass('loading');
            $notices.empty();
            $form.find('[data-field].error').removeClass('error').find('.errormsg').remove();
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'POST',
                data: $form.serialize()
            }).always(function(){
                // request completed
                $submit.removeClass('loading').attr('disabled', false);
            }).done(function(res){
                // on successful submit
                if (res) {
                    if (res.flash) {
                        if (res.flash.errors) {
                            $.each(res.flash.fields, function(field, errors){
                                var $field = $form.find('[data-field=' + field + ']').addClass('error');
                                $.each(errors, function(_case, error){
                                    $('<span>', {
                                        class: 'errormsg',
                                        text: error
                                    }).appendTo($field);
                                });
                            });
                            $notices.append($('<div>', {
                                class: 'flash-error',
                                html: res.flash.errors.join("<br>")
                            }));
                        }
                        else if (res.flash.notices)
                            alert(res.flash.notices.join("\n"));
                    }
                    else if (res.redir) {
                        location = res.redir;
                    }
                }
            }).fail(function(){
                // on failed request
                alert('Error');
            });
            return false;
        });
        $submit.attr('disabled', false);
    });

});