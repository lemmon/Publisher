var title = document.getElementsByTagName('title')[0],
    $nav, $frame;


//
// init
$(function(){
    

    // init instances
    $nav = $('#nav');
    $frame = $('iframe[name=frame]');

    
    // nav init
    $nav.find('a').click(function(){
        var $this = $(this),
            $li = $this.parent(),
            section = $this.attr('href').split('/')[2];
        $('.active').removeClass('active')
        $li.addClass('active');
        setLocation(section, $this.text());
    });


    // init location
    refresh();
    
    
    // window.hash
    $(window).on('hashchange', function(){
        refresh();
    });


});


//
// parse section
function getCurrentSection(url) {
    var hash = url || location.hash.replace(/^#/, '');
    return hash.split('/in/')[1] || hash.split('/')[0];
}


//
// change anchor location
function setLocation(myLocation, myTitle) {
    window.history.replaceState({}, myTitle, location.pathname + '#' + myLocation);
    title.text = myTitle;
    refreshNav(getCurrentSection(myLocation));
}


//
// set frame src
function setFrameLocation(url) {
    $frame.get(0).contentWindow.location = url;
}


//
// refresh
function refresh(force) {
    // get current section
    var section = getCurrentSection();
    // nav init
    if (section) {
        // section is defined
        setFrameLocation(refreshNav(section).find('a').attr('href'));
    } else {
        // init default section
        setFrameLocation($nav.find('a:first').click().attr('href'));
    }
    // force
    if (force){
        $frame.get(0).contentWindow.location.replace('/admin/' + section + '/main');
        //setFrameLocation('/admin/' + section + '/main');
    }
}

function refreshNav(mySection){
    var section = mySection || getCurrentSection();
        $li = $nav.find('li').filter('.nav-' + section);
    $('.active').removeClass('active')
    $li.addClass('active');
    return $li;
}