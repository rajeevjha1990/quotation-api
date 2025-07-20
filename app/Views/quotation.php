<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .no-border th,
        .no-border td {
            border: none;
        }

        .product-table th,
        .product-table td {
            border-top: 2px solid #ddd;
        }

        .grand-total {
            text-align: right;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Quotation</div>
        <table>
            <tr>
                <td class="no-border">
                    <table>
                        <tr>
                          <td colspan="2">
                              <strong><?php echo $client->client_name; ?></strong><br>
                              <?php echo $client->client_contact; ?><br>
                              <?php echo $client->client_address; ?>
                          </td>
                        </tr>
                    </table>
                </td>
                <td class="no-border">
                    <table>
                        <tr>
                            <td colspan="2">
                                <strong>ajjj</strong><br>
                                    ajjj
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grandTotal = 0;
                foreach ($quotationitems as $key => $value) {
                    $grandTotal += $value->qtnitem_amount;
                ?>
                    <tr>
                        <td><?php echo strtoupper($value->item_name); ?></td>
                        <td><?php echo $value->qtnitem_quantity; ?></td>
                        <td><?php echo $value->qtnitem_rate; ?></td>
                        <td><?php echo number_format($value->qtnitem_amount, 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="grand-total">
            <strong>Grand Total: <?php echo number_format($grandTotal, 2); ?></strong>
        </div>
    </div>
</body>
</html>
