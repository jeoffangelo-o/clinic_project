<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory Item</title>
</head>
<body>
    <h1>Edit Inventory Item</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <?php if(!empty($item)): ?>

    <form action="/inventory/update/<?= $item['item_id'] ?>" method="post">
        <?= csrf_field() ?>
        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" id="item_name" value="<?= $item['item_name'] ?>" required><br><br>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="medicine" <?= $item['category'] == 'medicine' ? 'selected' : '' ?>>Medicine</option>
            <option value="supply" <?= $item['category'] == 'supply' ? 'selected' : '' ?>>Supply</option>
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="<?= $item['quantity'] ?>" required><br><br>

        <label for="unit">Unit:</label>
        <input type="text" name="unit" id="unit" value="<?= $item['unit'] ?: '' ?>" placeholder="e.g., pcs, bottles, boxes"><br><br>

        <label for="expiry_date">Expiry Date (optional):</label>
        <input type="date" name="expiry_date" id="expiry_date" value="<?= $item['expiry_date'] ?: '' ?>"><br><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" cols="50"><?= $item['description'] ?: '' ?></textarea><br><br>

        <input type="submit" value="Update Item">
        
    </form>

    <?php else: ?>
        <p>Item not found</p>
    <?php endif; ?>
</body>
</html>
