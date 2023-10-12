$(document).ready(function() {
    var currentUrl = 'http://localhost:9000/api/products'; // Get the current URL

    console.log(currentUrl);
    $.ajax({
        url: currentUrl,
        type: "get",
        dataType: 'json',
        success: function(response){
            // console.log(response);
            // Get the table body element
            var tableBody = $('#product-table tbody');

            // Clear any existing table rows
            tableBody.empty();

            // Iterate over the response data and create table rows
            $.each(response, function(index, product) {
                // Create a new row
                var row = $('<tr>');

                // Populate the row with data
                row.append($('<td>').text(product.name));
                row.append($('<td>').text(product.price));
                row.append($('<td>').text(product.description));

                // Add the row to the table body
                tableBody.append(row);
            });
        },
        error: function(xhr, status, error) {
            // Handle the error
            console.log(error);
        }
    })
  });
