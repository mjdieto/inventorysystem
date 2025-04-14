<?php
$page_title = 'All Products';
require_once('includes/load.php');
date_default_timezone_set('Asia/Manila');
$db->query("SET time_zone = '+08:00'");

$products = join_product_table();
?>

<?php include_once('layouts/header.php'); ?>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
  .img-avatar { 
      width: 120px; /* Adjusted size */
      height: 120px; /* Adjusted size */
      object-fit: cover; 
      padding: 2px; 
      cursor: pointer; 
      border: 2px solid #b22222; /* Added border */
      border-radius: 8px; /* Rounded corners */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow effect */
      transition: transform 0.2s; /* Smooth transition */
  }

  .img-avatar:hover {
      transform: scale(1.05); /* Slightly enlarge on hover */
  }

  .table { width: 100%; border-collapse: collapse; }
  .table th, .table td { padding: 10px; text-align: center; border: 1px solid #b22222; }
  .btn-group .btn { margin: 2px; }
  .panel-heading { background-color: #b22222 !important; padding: 15px; color: white; font-weight: bold; }
  .btn-primary { background-color: #b22222; border-color: #b22222; }
  .btn-primary:hover { background-color: #8b1a1a; border-color: #8b1a1a; }
  .btn-danger { background-color: #8b0000; border-color: #8b0000; }
  .btn-danger:hover { background-color: #600000; border-color: #600000; }
  table.table thead { background: #b22222; color: white; }

  .btn-edit {
      background: rgb(60, 149, 223) !important;
      color: white !important;
      border-radius: 5px;
      border: none;
      padding: 5px 10px;
      font-weight: bold;
  }

  .btn-edit:hover {
      background: rgb(40, 120, 190) !important;
      color: white !important;
  }

  #searchContainer {
      position: relative;
      display: inline-block;
      width: 70%;
  }

  #searchInput {
      width: 100%;
      padding: 8px;
      border: 2px solid #b22222;
      border-radius: 4px;
      font-size: 14px;
      background-color: white;
      color: black;
      z-index: 1000;
      position: relative;
  }

  #searchInput:focus {
      border-color: #8b1a1a;
      outline: none;
  }

  .search-dropdown {
      position: absolute;
      background: white;
      border: 1px solid #b22222;
      border-radius: 4px;
      max-height: 150px;
      overflow-y: auto;
      width: 100%;
      z-index: 999;
      display: none;
      top: 40px;
  }

  .search-dropdown div {
      padding: 8px;
      cursor: pointer;
      text-transform: lowercase;
  }

  .search-dropdown div:hover {
      background: #f8d7da;
  }

  @media (max-width: 768px) {
      #searchContainer {
          width: 100%;
      }
  }

  /* Modal Styles */
  .modal-body {
      text-align: center; /* Center the content */
  }

  #modalImage {
      max-width: 100%; /* Responsive image */
      height: auto; /* Maintain aspect ratio */
      border-radius: 8px; /* Rounded corners for the image */
  }

  #productInfo {
      margin-top: 15px; /* Space above the product info */
      font-size: 16px; /* Larger font size for readability */
      line-height: 1.5; /* Improved line height for readability */
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
      let searchInput = document.getElementById("searchInput");
      let dropdown = document.getElementById("searchDropdown");

      searchInput.removeAttribute("readonly");
      searchInput.disabled = false;
      searchInput.style.display = "block";
      searchInput.style.visibility = "visible";

      searchInput.addEventListener("keyup", function () {
          searchProducts();
      });

      function searchProducts() {
          let filter = searchInput.value.toLowerCase();
          let tableRows = document.querySelectorAll("#productTable tbody tr");

          dropdown.innerHTML = "";
          dropdown.style.display = "none";

          if (filter === "") {
              tableRows.forEach(row => row.style.display = "");
              return;
          }

          let foundMatch = false;
          let matchedItems = new Set();

          tableRows.forEach(row => {
              let productNameCell = row.querySelector("td:nth-child(3)");
              if (productNameCell) {
                  let productName = productNameCell.innerText.toLowerCase();
                  if (productName.includes(filter)) {
                      row.style.display = "";
                      let matchStart = productName.indexOf(filter);
                      let matchedSubstring = productNameCell.innerText.substring(matchStart, matchStart + filter.length).toLowerCase();

                      matchedItems.add(matchedSubstring);
                      foundMatch = true;
                  } else {
                      row.style.display = "none";
                  }
              }
          });

          if (foundMatch) {
              matchedItems.forEach(match => {
                  let suggestion = document.createElement("div");
                  suggestion.innerHTML = highlightMatch(match, filter);
                  suggestion.onclick = function () {
                      searchInput.value = match;
                      dropdown.style.display = "none";
                      searchProducts();
                  };
                  dropdown.appendChild(suggestion);
              });
              dropdown.style.display = "block";
          } else {
              dropdown.style.display = "none";
          }
      }

      function highlightMatch(text, query) {
          let regex = new RegExp(`(${query})`, "gi");
          return text.replace(regex, "<strong style='color: #b22222;'>$1</strong>");
      }

      document.addEventListener("click", function (event) {
          if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
              dropdown.style.display = "none";
          }
      });
  });

  $(document).ready(function() {
    $('#imageModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var imgSrc = button.data('img-src'); // Extract info from data-* attributes
      var productName = button.closest('tr').find('td:nth-child(3)').text(); // Get product name
      var productCategory = button.closest('tr').find('td:nth-child(4)').text(); // Get product category
      var productLocation = button.closest('tr').find('td:nth-child(5)').text(); // Get product location
      var modal = $(this);
      modal.find('#modalImage').attr('src', imgSrc); // Update the modal's image source
      modal.find('#productInfo').html(`<strong>Name:</strong> ${productName}<br><strong>Category:</strong> ${productCategory}<br><strong>Location:</strong> ${productLocation}`); // Update the modal's product information
    });
  });
</script>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-left">
          <a href="add_product.php" class="btn btn-primary">Add New</a>
        </div>
        <div class="pull-right">
          <div id="searchContainer">
            <input type="text" id="searchInput" placeholder="Search products..." autocomplete="off" />
            <div id="searchDropdown" class="search-dropdown"></div>
          </div>
          <a href="stock_card.php" class="btn btn-primary" style="margin-left:5px;">Stock Card</a>
        </div>
      </div>
      <div class="panel-body">
        <table id="productTable" class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Photo</th>
              <th>Product </th>
              <th class="text-center">Categories</th>
              <th class="text-center">Location</th>
              <th class="text-center">Stocks</th>
              <th class="text-center">Buying Price</th>
              <th class="text-center">Selling Price</th>
              <th class="text-center">Date & Time Added</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
              <td class="text-center"><?php echo count_id(); ?></td>
              <td>
                <?php if($product['media_id'] === '0'): ?>
                  <img class="img-avatar" src="uploads/products/no_image.png" alt="Product Image" data-toggle="modal" data-target="#imageModal" data-img-src="uploads/products/no_image.png">
                <?php else: ?>
                  <img class="img-avatar" src="uploads/products/<?php echo remove_junk($product['image']); ?>" alt="Product Image" data-toggle="modal" data-target="#imageModal" data-img-src="uploads/products/<?php echo remove_junk($product['image']); ?>">
                <?php endif; ?>
              </td>
              <td><?php echo remove_junk($product['name']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
              <td class="text-center"><?php echo !empty($product['Location']) ? remove_junk($product['Location']) : 'N/A'; ?></td>
              <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['buy_price']); ?></td>
              <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
              <td class="text-center"><?php echo format_date($product['date']); ?></td>
              <td class="text-center">
                <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-xs btn-edit" title="Edit">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Delete">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"> <!-- Larger modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" alt="Product Image" class="img-fluid"> <!-- Responsive image -->
        <div id="productInfo" class="mt-3"></div> <!-- Placeholder for product information -->
      </div>
    </div>
  <?php include_once('layouts/footer.php'); ?>