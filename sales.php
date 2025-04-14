<?php
  $page_title = 'All Sale';
  require_once('includes/load.php');
  page_require_level(3);
?>
<?php
$sales = find_all_sale();
?>

<style>
  /* Panel Heading */
  .panel-heading {
      background-color: #b22222 !important;
      padding: 15px;
      color: white !important;
  }

  /* Table Styling */
  .table thead {
      background: #b22222;
      color: white;
  }

  .table thead th, .table tbody td {
      text-align: center;
      border: 1px solid #b22222;
      padding: 12px;
  }

  /* Search Bar */
  #searchContainer {
      position: relative;
      display: inline-block;
      width: 50%;
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

  /* Live Search Dropdown */
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

  /* Responsive search bar */
  @media (max-width: 768px) {
      #searchContainer {
          width: 100%;
      }
  }

  /* Buttons */
  .btn-custom {
      background: #b22222;
      color: white !important;
      border-radius: 5px;
      padding: 8px 12px;
  }

  .btn-custom:hover {
      background: #8b1a1a;
  }

  .btn-edit {
      background: rgb(60, 149, 223);
      color: white;
      border-radius: 5px;
      padding: 5px 10px;
      font-weight: bold;
  }

  .btn-edit:hover {
      background: rgb(40, 120, 190);
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
          let tableRows = document.querySelectorAll("tbody tr");

          dropdown.innerHTML = ""; // Clear previous results
          dropdown.style.display = "none";

          if (filter === "") {
              tableRows.forEach(row => row.style.display = "");
              return;
          }

          let foundMatch = false;
          let matchedItems = new Set(); 

          tableRows.forEach(row => {
              let productNameCell = row.querySelector("td:nth-child(2)");
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
</script>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Sales</span>
        </strong>
        <div class="pull-right">
          <div id="searchContainer">
            <input type="text" id="searchInput" placeholder="Search products..." autocomplete="off" />
            <div id="searchDropdown" class="search-dropdown"></div>
          </div>
          <a href="add_sale.php" class="btn btn-custom">Add Sale</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product Name</th>
              <th class="text-center" style="width: 15%;">Quantity</th>
              <th class="text-center" style="width: 15%;">Total</th>
              <th class="text-center" style="width: 15%;">Date</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $sale): ?>
            <tr>
              <td class="text-center"><?php echo count_id(); ?></td>
              <td><?php echo remove_junk($sale['name']); ?></td>
              <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
              <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
              <td class="text-center"><?php echo date('F j, Y', strtotime($sale['date'])); ?></td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-xs btn-edit" title="Edit">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a href="delete_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-xs btn-danger" title="Delete">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
