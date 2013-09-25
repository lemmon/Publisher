$(function(){
    
    //
    // forms
    $('form[data-action]').css('border', '1px dotted red').each(function(){
        var $form = $(this),
            $submit = $form.find('button[type=submit]'),
            url = $form.attr('action');
        $form.submit(function(){
            var _caption = $submit.text();
            $submit.attr('disabled', true).html('loading&hellip;').addClass('loading');
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'POST',
                data: $form.serialize()
            }).always(function(){
                $submit.removeClass('loading').html(_caption).attr('disabled', false);
            }).fail(function(){
                alert('Error');
            }).done(function(res){
                // on error
                if (res) {
                    if (res.flash){
                        if (res.flash.errors)
                            alert(res.flash.errors.join("\n"));
                        else if (res.flash.notices)
                            alert(res.flash.notices.join("\n"));
                    }
                }
            });
            return false;
        });
        $submit.attr('disabled', false);
    });

    
});