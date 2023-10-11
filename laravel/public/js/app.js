$(document).ready(function() {
    var currentUrl = window.location.href; // Get the current URL

    console.log(currentUrl);
    $.ajax({
        url: currentUrl,
        type: "get",
        success: function(response){
            console.log(7878);
        }
    })
  });
