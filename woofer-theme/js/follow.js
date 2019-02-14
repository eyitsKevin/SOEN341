$ = jQuery;

$(document).ready(function(){


  /////////////////////////////////////////////////////////
  // AJAX calls to update user meta and change btns that
  // are displayed
  /////////////////////////////////////////////////////////
  $('.sub-btn').click(function(){
    var btn = $(this);
    var data = {
            action : "woof_ajax_follow",
            auth: btn.data('authid')
        };
        $.ajax({
            url : my_ajax_object.ajax_url,
            data : data,
            success : function(response) {
              btn.hide();
              tailAll(btn.data('authid'));
              btn.next().show();
            }
        });

  });

  $('.unsub-btn').click(function(){
    var btn = $(this);
    var data = {
            action : "woof_ajax_unfollow",
            auth: btn.data('authid')
        };
        $.ajax({
            url : my_ajax_object.ajax_url,
            data : data,
            success : function(response) {
              btn.hide();
              unleashAll(btn.data('authid'));
              btn.prev().show();
            }
        });

  });
});
