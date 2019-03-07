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


  /////////////////////////////////////////////////////////
  // Helper fcts to make sure ALL the btns are changed
  // when follow/unfollow
  /////////////////////////////////////////////////////////

  function tailAll(authID){
    $('.sub-btn').each(function(){
      if($(this).data('authid') == authID){
        $(this).hide();
        $(this).next().show();
      }
    });
  }

  function unleashAll(authID){
    $('.unsub-btn').each(function(){
      if($(this).data('authid') == authID){
        $(this).hide();
        $(this).prev().show();
      }
    });
  }

});