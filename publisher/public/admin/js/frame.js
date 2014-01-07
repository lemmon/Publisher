var $rail, $main;


//
// init
$(function(){


    // init instances
    $rail = $('#rail');
    $main = $('iframe[name=main]');
    
    
    // set proper targets
    $('a').filter(':not([target])').attr('target', 'main');


    // menu sublinks
    $('[data-href]').click(function(){
        $main.attr('src', $(this).data('href'));
        return false;
    })
    

    // menu active states
    $rail.find('ul a[target=main]').click(function(){
        var $this = $(this);
        $('.active').removeClass('active');
        $this.parent().addClass('active');
        $main.attr('src', $this.attr('href'));
        return false;
    });


});