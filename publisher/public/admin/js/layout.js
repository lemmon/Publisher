var $notices, // flash notifications
    $modalOverlay, $modalWindow; // modal


//
// location
var section = location.pathname.substring(7).replace(/\/main$/, ''),
    sectionThis = section.split('/')[0],
    sectionIn = parent.location.pathname.substring(7).split('/')[0];
if (window.name != 'modal' && window != top.window) {
    top.setLocation(section + (sectionIn && sectionThis != sectionIn ? '/in/' + sectionIn : ''), document.getElementsByTagName('title')[0].text);
}


//
// init
$(function(){
    
    
    //
    // notifications
    $notices = $('#notices');
   
    //
    // tabs
    $('.tabs').each(function(){
        var $list = $(this),
            $tabs = $list.nextAll('.tab');
        $list.find('a').each(function(i){
            var $this = $(this);
            $this.click(function(){
                $this.addClass('active').siblings('.active').removeClass('active');
                $tabs.filter(':not(:eq(' + i + '))').hide();
                $tabs.filter(':eq(' + i + ')').show();
                return false;
            });
        }).filter(':first').click();
    });

    //
    // modal
    $modalOverlay = $('#modal-overlay');
    $modalWindow = $('#modal-window')
    $modalOverlay.click(function(){
        modalHide();
    });
    
    
});


//
// modal
function modalShow(url){
    $modalOverlay.show();
    $modalWindowIframe.load(function(){
        $modalWindow.show();
        $modalWindowIframe.unbind('load');
    }).attr('src', url);
}

function modalHide(){
    $modalOverlay.hide();
    $modalWindow.hide();
    $modalWindow.empty();
}
