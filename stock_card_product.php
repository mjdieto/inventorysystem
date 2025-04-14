<?php
require_once('includes/load.php');
page_require_level(2);

$product_id = (int)$_GET['id'];
$movements = find_by_sql("SELECT * FROM stock_movements WHERE product_id = '{$product_id}' ORDER BY date DESC");

include_once('layouts/header.php');
?>

<!doctype html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title>Stock Movements</title>
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
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="page-break">
        <div class="stock-head">
            <h1>Stock Movements for Product ID: <?php echo $product_id; ?></h1>
        </div>

        <table class="table table-bordered stock-card-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Movement Type</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movements as $movement): ?>
                <tr>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($movement['date'])); ?></td>
                    <td class="<?php echo $movement['movement_type'] === 'in' ? 'movement-in' : 'movement-out'; ?>">
                        <?php echo strtoupper($movement['movement_type']); ?>
                    </td>
                    <td><?php echo (int)$movement['quantity']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</body>

</html>

<?php if (isset($db)) {
    $db->db_disconnect();
} ?>