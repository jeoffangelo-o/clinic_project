<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">Add Consultation</h2>
</div>
<br>
<?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'):  ?>

    <div class="card">
        <div class="card-body">
        <form action="/consultation/add" method="get" class="row align-items-end">
                        <div class="col-md-10">
                            <label class="form-label">Service Type <span class="text-danger">*</span></label>
                            <select name="service" class="form-select" onchange="this.form.submit()">
                                <option value="walkin" <?= (session()->get('service') === 'walkin') ? 'selected' : '' ?>>Walk-In Patient</option>
                                <option value="appoint" <?= (session()->get('service') === 'appoint') ? 'selected' : '' ?>>Appointment</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="/consultation/store" method="post">
                        <?= csrf_field() ?>

                        <?php if(session()->get('service') === 'walkin'): ?>
                            
                            <input type="hidden" name="service" value="walkin">
                            
                            <div class="mb-3">
                                <label class="form-label">Patient ID <span class="text-danger">*</span></label>
                                <input type="number" name="patient_id" class="form-control" required>
                            </div>

                        <?php else: ?>

                            <input type="hidden" name="service" value="appoint">

                            <div class="mb-3">
                                <label class="form-label">Appointment <span class="text-danger">*</span></label>
                                <select name="appointment_id" id="appointment_id" class="form-select" required onchange="updatePatientFromAppt()">
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
                                <small class="text-muted">Selected Patient: <strong id="selectedPatientName">None</strong></small>
                            </div>

                        <?php endif; ?>
                        
                        <input type="hidden" name="nurse_id" value="<?= session()->get('user_id')?>">

                        <div class="mb-3">
                            <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                            <input type="text" name="diagnosis" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Treatment <span class="text-danger">*</span></label>
                            <input type="text" name="treatment" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prescription <span class="text-danger">*</span></label>
                            <input type="text" name="prescription" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- Medicines Section -->
                        <div class="card bg-light mt-4 mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-boxes-stacked text-primary"></i>
                                    <h5 class="card-title mb-0 ms-2">Allocate Medicines/Items (Optional)</h5>
                                </div>
                                <p class="text-muted small mb-3">Select medicines/items given to the patient. Inventory will automatically decrement.</p>
                                
                                <div id="medicines-container">
                                    <div class="medicine-row mb-3 p-3 bg-white border rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Medicine/Item <span class="text-danger">*</span></label>
                                                <select name="medicines_select" class="medicine-select form-select" required>
                                                    <option value="">-- Select a medicine --</option>
                                                    <?php
                                                        $inventory = new \App\Models\InventoryModel();
                                                        $items = $inventory->findAll();
                                                        foreach ($items as $item):
                                                    ?>
                                                        <option value="<?= $item['item_id'] ?>" data-unit="<?= htmlspecialchars($item['unit'] ?? '') ?>" data-available="<?= $item['quantity'] ?>">
                                                            <?= htmlspecialchars($item['item_name']) ?> (<?= $item['quantity'] ?> <?= htmlspecialchars($item['unit'] ?? '') ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                <input type="number" name="medicines_qty_0" class="medicine-qty form-control" min="1" value="1" required>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeMedicineRow(0)">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-success" onclick="addMedicineRow()">
                                    <i class="fa-solid fa-plus"></i> Add Another Medicine
                                </button>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary" onclick="return serializeMedicines()">
                                <i class="fa-solid fa-check"></i> Submit Consultation
                            </button>
                            <a href="/consultation" class="btn btn-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-warning">
        <i class="fa-solid fa-circle-info"></i> Only nurses and administrators can create consultations.
    </div>
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
                ${item.item_name} (${item.quantity} ${item.unit || ''})
            </option>`;
        });

        const newRow = `
            <div class="medicine-row mb-3 p-3 bg-white border rounded" id="med-row-${medicineRowCount}">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Medicine/Item <span class="text-danger">*</span></label>
                        <select name="medicines_select" class="medicine-select form-select" required>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="medicines_qty_${medicineRowCount}" class="medicine-qty form-control" min="1" value="1" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeMedicineRow(${medicineRowCount})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
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

<?= $this->endSection() ?>