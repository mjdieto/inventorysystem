<?php
$page_title = 'Sales Report';
$results = '';
require_once('includes/load.php');

// Check user permission level
page_require_level(3);
?>
<?php
if (isset($_POST['submit'])) {
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    if (empty($errors)) :
        $start_date = remove_junk($db->escape($_POST['start-date']));
        $end_date = remove_junk($db->escape($_POST['end-date']));
        $results = find_sale_by_dates($start_date, $end_date);
    else :
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    endif;
} else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
}
?>
<!doctype html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
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

        .sale-head {
            text-align: center;
            margin-bottom: 30px;
            color: #ffffff;
            background: #b22222; /* Dark red */
            padding: 15px;
            border-radius: 10px;
        }

        .sale-head h1 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .sale-head strong {
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

        table.table tfoot tr {
            font-weight: bold;
            background: #f4c2c2; /* Light red */
        }

        /* Footer Styling */
        tfoot {
            color: #000;
            text-transform: uppercase;
        }

        tfoot td {
            padding: 12px;
            border-top: 2px solid #b22222;
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
    <?php if ($results) : ?>
        <div class="page-break">
            <div class="sale-head">
                <h1>Inventory Management System - Sales Report</h1>
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

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Total Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_qty = 0;
                    foreach ($results as $result) :
                        $total_qty += $result['total_sales'];
                    ?>
                        <tr>
                            <td class="text-center">
                                <?php echo date("F j, Y", strtotime($result['date'])); ?>
                            </td>
                            <td><?php echo remove_junk(ucfirst($result['name'])); ?></td>
                            <td class="text-right">₱<?php echo number_format($result['buy_price'], 2); ?></td>
                            <td class="text-right">₱<?php echo number_format($result['sale_price'], 2); ?></td>
                            <td class="text-center"><?php echo number_format($result['total_sales']); ?></td>
                            <td class="text-right">₱<?php echo number_format($result['total_saleing_price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="text-right">
                        <td colspan="4"></td>
                        <td><strong>Total Quantity</strong></td>
                        <td><?php echo number_format($total_qty); ?></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="4"></td>
                        <td><strong>Grand Total</strong></td>
                        <td>₱<?php echo number_format(total_price($results)[0], 2); ?></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="4"></td>
                        <td><strong>Profit</strong></td>
                        <td>₱<?php echo number_format(total_price($results)[1], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="btn-container">
                            <button onclick="window.print()" class="btn btn-green">Print Report</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php
    else :
        $session->msg("d", "Sorry, no sales have been found.");
        redirect('sales_report.php', false);
    endif;
    ?>
</body>

</html>
<?php if (isset($db)) {
    $db->db_disconnect();
} ?>