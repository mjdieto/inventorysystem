<?php
$page_title = 'Stock Card Report';
require_once('includes/load.php');

// Get the date range from the GET parameters
$start_date = isset($_GET['start-date']) ? $_GET['start-date'] : null;
$end_date = isset($_GET['end-date']) ? $_GET['end-date'] : null;

// Adjust the end date to include the entire day
if ($end_date) {
    $end_date = date('Y-m-d 23:59:59', strtotime($end_date));
}

$products = join_product_table();
?>

<!doctype html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title>Stock Card Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <style>
        @media print {
            html, body {
                font-size: 9.5pt;
                margin: 0;
                padding: 0;
            }
            .page-break {
                page-break-before: always;
                width: auto;
                margin: auto;
            }
        }

        /* General Styling */
        body {
            background-color: #f8d7da; /* Light red background */
            font-family: 'Arial', sans-serif;
        }

        .page-break {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .stock-head {
            text-align: center;
            margin-bottom: 30px;
            color: #ffffff;
            background: #b22222; /* Dark red */
            padding: 15px;
            border-radius: 10px;
        }

        .stock-head h1 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .stock-head strong {
            display: block;
            font-size: 18px;
            margin-top: 10px;
        }

        /* Table Styling */
        .table {
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }

        table.table thead {
            background: #b22222; /* Dark red */
            color: white;
            font-weight: bold;
        }

        table.table tbody tr td {
            vertical-align: middle;
            border-bottom: 1px solid #ddd;
            padding: 12px;
        }

        table.table thead tr th {
            text-align: center;
            border: 1px solid #b22222;
        }

        /* Movement Type Colors */
        .movement-in {
            color: green; /* Green for incoming stock */
        }

        .movement-out {
            color: red; /* Red for outgoing stock */
        }

        /* Button Styling */
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-green {
            background-color: #28a745; /* Green color */
            color: white;
        }

        .btn-green:hover {
            background-color: #218838; /* Darker green on hover */
        }

        /* Align button to the right */
        .btn-container {
            text-align: right; /* Aligns the button to the right */
        }
    </style>
</head>

<body>
    <div class="page-break">
        <div class="stock-head">
            <h1>Inventory Management System - Stock Card Report</h1>
            <strong>
                <?php 
                    echo isset($start_date) ? date("F j, Y", strtotime($start_date)) : ''; 
                ?> 
                TILL DATE 
                <?php 
                    echo isset($end_date) ? date("F j, Y", strtotime($end_date)) : ''; 
                ?>
            </strong>
        </div>

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
                    <td><?php echo date("F j, Y", strtotime($movement['date'])); ?></td>
                    <td class="<?php echo $movement['movement_type'] === 'in' ? 'movement-in' : 'movement-out'; ?>">
                        <?php echo strtoupper($movement['movement_type']); ?>
                    </td>
                    <td><?php echo $movement['quantity']; ?></td>
                    <td>
                        <?php 
                        $balance += ($movement['movement_type'] === 'out') ? -$movement['quantity'] : $movement['quantity'];
                        echo $balance;
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="btn-container">
            <button onclick="window.print()" class="btn btn-green">Print Report</button>
        </div>
    </div>

    <script>
    function printReport() { 
        window.print();
    }
    </script>
</body>

</html>

<?php if (isset($db)) {
    $db->db_disconnect();
} ?>