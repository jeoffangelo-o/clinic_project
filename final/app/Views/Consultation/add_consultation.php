<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Consultation</title>
</head>
<body>
    <h1>Add Consultation</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'):  ?>

        <form action="/consultation/add" method="get" >
            <label for="">Service:</label>
            <select name="service" id="" onchange="this.form.submit()">
                <option value="walkin" <?= (session()->get('service') === 'walkin') ? 'selected' : '' ?>>Walk-In</option>
                <option value="appoint" <?= (session()->get('service') === 'appoint') ? 'selected' : '' ?>>Appointment</option>
            </select>
            <br><br>
        </form>
        
        <form action="/consultation/store" method="post">
            <?= csrf_field() ?>

            <?php if(session()->get('service') === 'walkin'): ?>
                
                <input type="hidden" name="service" value="walkin">
                
                <label for="">Patient ID:</label>
                <input type="number" name="patient_id" id="" required>

            <?php else: ?>

                <input type="hidden" name="service" value="appoint">

                <label for="appointment_id">Appointment ID:</label>
                <select name="appointment_id" id="appointment_id" required onchange="updatePatientFromAppt()">
                    <option value="">-- Select Approved Appointment --</option>
                    <?php if(!empty($appointments)): ?>
                        <?php foreach($appointments as $appt): ?>
                            <option value="<?= esc($appt['appointment_id']) ?>" data-patient-id="<?= esc($appt['patient_id']) ?>" data-patient-name="<?= esc($appt['patient_name']) ?>">
                                #<?= esc($appt['appointment_id']) ?> - <?= esc($appt['patient_name']) ?> (<?= esc($appt['appointment_date']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No approved appointments available</option>
                    <?php endif; ?>
                </select>
                <br>
                <small style="color: #666;">Selected Patient: <span id="selectedPatientName">None</span></small>

            <?php endif; ?>
            
            <br><br>
            <input type="hidden" name="nurse_id" value="<?= session()->get('user_id')?>">

            <label for="">Diagnosis:</label>
            <input type="text" name="diagnosis" id="" required><br><br>
            <label for="">Treatment:</label>
            <input type="text" name="treatment" id="" required><br><br>
            <label for="">Prescription:</label>
            <input type="text" name="prescription" id="" required><br><br>
            <label for="">Notes:</label>
            <input type="text" name="notes" id="" ><br><br>

            <fieldset style="border: 1px solid #ccc; padding: 15px; border-radius: 5px;">
                <legend><strong>📦 Allocate Medicines/Items (Optional)</strong></legend>
                <p style="font-size: 12px; color: #666;">Select medicines/items given to the patient. Inventory will automatically decrement.</p>
                
                <div id="medicines-container">
                    <div class="medicine-row" style="margin-bottom: 10px; padding: 10px; background-color: #f9f9f9; border-radius: 3px;">
                        <label for="item-0" style="display: block; margin-bottom: 5px;">Medicine/Item:</label>
                        <select name="medicines_select" id="item-0" class="medicine-select" style="width: 60%; padding: 5px;">
                            <option value="">-- Select a medicine --</option>
                            <?php
                                $inventory = new \App\Models\InventoryModel();
                                $items = $inventory->findAll();
                                foreach ($items as $item):
                            ?>
                                <option value="<?= $item['item_id'] ?>" data-unit="<?= htmlspecialchars($item['unit'] ?? '') ?>" data-available="<?= $item['quantity'] ?>">
                                    <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] ?> <?= htmlspecialchars($item['unit'] ?? '') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <label for="qty-0" style="display: inline-block; margin-left: 15px; margin-bottom: 5px;">Quantity:</label>
                        <input type="number" name="medicines_qty_0" id="qty-0" class="medicine-qty" min="1" value="1" style="width: 80px; padding: 5px;">
                        
                        <button type="button" onclick="removeMedicineRow(0)" style="margin-left: 10px; padding: 5px 10px; background-color: #ff6b6b; color: white; border: none; border-radius: 3px; cursor: pointer;">Remove</button>
                    </div>
                </div>

                <button type="button" onclick="addMedicineRow()" style="padding: 8px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer; margin-top: 10px;">+ Add Another Medicine</button>
            </fieldset>

            <br><br>
            
            <input type="submit" value="Submit Consultation" onclick="return serializeMedicines()">

        </form>

    <?php else: ?>

    <?php endif; ?>

    <script>
        let medicineRowCount = 0;

        function addMedicineRow() {
            medicineRowCount++;
            const container = document.getElementById('medicines-container');
            const inventory = <?= json_encode($items ?? []) ?>;
            
            let optionsHtml = '<option value="">-- Select a medicine --</option>';
            inventory.forEach(item => {
                optionsHtml += `<option value="${item.item_id}" data-unit="${item.unit || ''}" data-available="${item.quantity}">
                    ${item.item_name} (Available: ${item.quantity} ${item.unit || ''})
                </option>`;
            });

            const newRow = `
                <div class="medicine-row" id="med-row-${medicineRowCount}" style="margin-bottom: 10px; padding: 10px; background-color: #f9f9f9; border-radius: 3px;">
                    <label for="item-${medicineRowCount}" style="display: block; margin-bottom: 5px;">Medicine/Item:</label>
                    <select name="medicines_select" id="item-${medicineRowCount}" class="medicine-select" style="width: 60%; padding: 5px;">
                        ${optionsHtml}
                    </select>
                    
                    <label for="qty-${medicineRowCount}" style="display: inline-block; margin-left: 15px; margin-bottom: 5px;">Quantity:</label>
                    <input type="number" name="medicines_qty_${medicineRowCount}" id="qty-${medicineRowCount}" class="medicine-qty" min="1" value="1" style="width: 80px; padding: 5px;">
                    
                    <button type="button" onclick="removeMedicineRow(${medicineRowCount})" style="margin-left: 10px; padding: 5px 10px; background-color: #ff6b6b; color: white; border: none; border-radius: 3px; cursor: pointer;">Remove</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newRow);
        }

        function removeMedicineRow(index) {
            const row = document.getElementById(`med-row-${index}`);
            if (row) {
                row.remove();
            }
        }

        function serializeMedicines() {
            const form = event.target.closest('form');
            const medicinesObj = {};

            // Collect all medicine rows
            document.querySelectorAll('.medicine-row').forEach(row => {
                const select = row.querySelector('.medicine-select');
                const qtyInput = row.querySelector('.medicine-qty');
                
                if (select && select.value && qtyInput) {
                    const itemId = select.value;
                    const qty = parseInt(qtyInput.value) || 0;
                    const available = parseInt(select.options[select.selectedIndex].getAttribute('data-available')) || 0;
                    
                    if (qty > 0 && qty <= available) {
                        medicinesObj[itemId] = qty;
                    } else if (qty > available) {
                        alert(`Insufficient inventory for ${select.options[select.selectedIndex].text}. Available: ${available}`);
                        return false;
                    }
                }
            });

            // Create hidden input with serialized medicines
            const existingInput = form.querySelector('input[name="medicines"]');
            if (existingInput) {
                existingInput.remove();
            }
            
            if (Object.keys(medicinesObj).length > 0) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'medicines';
                input.value = JSON.stringify(medicinesObj);
                form.appendChild(input);
            }

            return true;
        }

        function updatePatientFromAppt() {
            const select = document.getElementById('appointment_id');
            const selectedOption = select.options[select.selectedIndex];
            const patientName = selectedOption.getAttribute('data-patient-name') || 'None';
            document.getElementById('selectedPatientName').textContent = patientName;
        }
    </script>

</body>
</html>