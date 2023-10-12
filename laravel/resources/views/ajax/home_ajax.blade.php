<html>
   <head>
      <title>Ajax Example</title>
      
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <style>
        /* Button Styles */
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1636c4;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2868bc;
        }

        .button-link {
            display: inline-block;
            text-decoration: none;
            margin: 20px 20px;
        }

      </style>
   </head>
   <body>
    <a href="{{ route('clone-products') }}" target="_parent" class="button-link"><button class="button">Sync data product !</button></a>
    <br>
    <div>
        <table id="product-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Vendor</th>
                    <th>Product Type</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be dynamically added here using JavaScript -->``
            </tbody>
        </table>  
        <button id="load-more-btn" class="button">Load More</button>
        <button id="load-back-btn" class="button">Back</button>
    </div> 
   </body>
   <script>

    var currentPage = 1;
    var recordsPerPage = 10;
    function getProducts(page) {
       console.log(44444);
       $.ajax({
          type:'GET',
          url:'/api/products',
          data: {
            page: page,
            per_page: recordsPerPage
            },
          success:function(data) {

            // Get the table body element
            var tableBody = $('#product-table tbody');

            // Clear any existing table rows
            tableBody.empty();

            // Iterate over the products and create table rows
            $.each(data.products, function(index, product) {

                var bodyText = $('<div>').html(product.body_html).text();
                // Create a new row
                var row = $('<tr>');

                // Populate the row with data
                row.append($('<td>').text(product.id));
                row.append($('<td>').text(product.title));
                row.append($('<td>').text(bodyText));
                row.append($('<td>').text(product.vendor));
                row.append($('<td>').text(product.product_type));
                row.append($('<td>').text(product.created_at));

                // Add the row to the table body
                tableBody.append(row);
                 // Check if there are more records to load
                var totalRecords = 30;

                var totalPages = Math.ceil(totalRecords / recordsPerPage);

                if (currentPage < totalPages) {
                    // Display the "Load More" button or link
                    $('#load-more-btn').show();
                    $('#load-back-btn').show();
                } else {
                    // Hide the "Load More" button or link
                    $('#load-more-btn').hide();
                    $('#load-back-btn').show();
                }
            });
             $("#msg").html(data.message);
          },
          error: function(xhr, status, error) {
            console.log(error);
        }
       });
    }

    function loadMore() {
        currentPage++;
        getProducts(currentPage);
    }

    function loadBack(){
        currentPage--;
        getProducts(currentPage)
    }

    $(document).ready(function() {
        // Hide the "Load More" button initially
        $('#load-more-btn').hide();

        $('#load-back-btn').hide();

        // Load the initial page of products
        getProducts(currentPage);

        // Attach click event to the "Load More" button
        $('#load-more-btn').click(function() {
            loadMore();
        });

        $('#load-back-btn').click(function(){
            loadBack();
        });
    });
 </script>
</html>