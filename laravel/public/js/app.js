$(document).ready(function() {
    var currentUrl = window.location.href; // Get the current URL

    // console.log(currentUrl);
    $.ajax({
        url: "{{ url('products')}}",
        type: "get",
        success: function(response){
            // console.log(7878);
            // console.log(response);
        }
    })
  });



// $(document).ready(function () {
//     console.log(99999);
//     /* When click show user */
//     var currentUrl = window.location.href; // Get the current URL
//     console.log(currentUrl);
//     $.ajax({
//         url: currentUrl,
//         method: 'GET',
//         data: { page: page }, // Pass the page parameter
//         success: function(response) {
//         // Update the page content with the received data
//         $('#product-list').html(response);
//         },
//         error: function(xhr) {
//         // Handle error
//         }
//     });
// });