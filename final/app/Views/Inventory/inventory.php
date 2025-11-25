<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
</head>
<body>
    <h1>Manage Inventory</h1>

    <button><a href="/inventory/add">Add Item</a></button>

    <h2>List of Inventory Items</h2>

    <table border='1'>
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Expiry Date</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>

        <?php if(!empty($inventory)): ?>

            <?php foreach($inventory as $item): ?>

                <tr>
                    <td><?= $item['item_id'] ?></td>
                    <td><?= $item['item_name'] ?></td>
                    <td><?= ucfirst($item['category']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['unit'] ?: 'N/A' ?></td>
                    <td><?= $item['expiry_date'] ?: 'N/A' ?></td>
                    <td><?= $item['updated_at'] ?></td>

                    <td>
                        <a href="<?= base_url('/inventory/edit/'.$item['item_id'])?>">Edit</a>
                        <a href="<?= base_url('/inventory/delete/'.$item['item_id'])?>" onclick="return confirm('Are you sure to delete item #<?= $item['item_id'] ?>?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="8">No Items in Inventory</td>
            </tr>
        <?php endif; ?>

    </table>
</body>
</html>
