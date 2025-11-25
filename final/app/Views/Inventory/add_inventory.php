<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory Item</title>
</head>
<body>
    <h1>Add Inventory Item</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="/inventory/store" method="post">

        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" id="item_name" required><br><br>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="">--Select--</option>
            <option value="medicine">Medicine</option>
            <option value="supply">Supply</option>
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="0" required><br><br>

        <label for="unit">Unit:</label>
        <input type="text" name="unit" id="unit" placeholder="e.g., pcs, bottles, boxes"><br><br>

        <label for="expiry_date">Expiry Date (optional):</label>
        <input type="date" name="expiry_date" id="expiry_date"><br><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" cols="50"></textarea><br><br>

        <input type="submit" value="Add Item">
        
    </form>
</body>
</html>
