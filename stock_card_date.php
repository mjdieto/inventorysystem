<?php
$page_title = 'Stock Card Report';
require_once('includes/load.php');

// Get the date range from the GET parameters
$start_date = isset($_GET['start-date']) ? $_GET['start-date'] : null;
$end_date = isset($_GET['end-date']) ? $_GET['end-date'] : null;

$products = join_product_table();
?>

<?php include_once('layouts/header.php'); ?>

<style>
    .stock-card-table th { background-color: #b22222; color: white; }
    .movement-in { color: green; }
    .movement-out { color: red; }

    /* Print styles */
    @media print {
        .inventory-system,
        .account-name,
        .btn,
        .panel-heading {
            display: none !important;
        }

        .stock-card-table {
            width: 100%;
            border-collapse: collapse;
        }
    }
</style>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="product.php" class="btn btn-primary">Back to Products</a>
                    <button class="btn btn-success" onclick="printReport()">Print Report</button>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered stock-card-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Date</th>
                            <th>Movement Type</th>
                            <th>Quantity</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <?php
                        $movements = $db->query("SELECT * FROM stock_movements 
                                               WHERE product_id = '{$product['id']}' 
                                               AND date BETWEEN '{$start_date}' AND '{$end_date}'
                                               ORDER BY date DESC")->fetch_all(MYSQLI_ASSOC);
                        $balance = $product['quantity'];
                        ?>
                        <tr class="product-header">
                            <td colspan="5" style="background:#f5f5f5;">
                                <strong><?php echo remove_junk($product['name']); ?></strong>
                                (Current Stock: <?php echo $balance; ?>)
                            </td>
                        </tr>
                        <?php foreach ($movements as $movement): ?>
                        <tr>
                            <td></td>
                            <td><?php echo $movement['date']; ?></td>
                            <td class="<?php echo $movement['movement_type'] === 'in' ? 'movement-in' : 'movement-out'; ?>">
                                <?php echo strtoupper($movement['movement_type']); ?>
                            </td>
                            <td><?php echo $movement['quantity']; ?></td>
                            <td>
                                <?php 
                                $balance += ($movement['movement_type'] === 'out') ? $movement['quantity'] : -$movement['quantity'];
                                echo $balance;
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function printReport() {
    window.print();
}
</script>

<?php include_once('layouts/footer.php'); ?>