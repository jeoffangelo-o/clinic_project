# Inventory Consumption Tracking - Implementation Summary

## Feature Overview
Complete medicine consumption tracking system that decrements inventory quantities when medicines are allocated during consultations, with automatic rollback on consultation deletion and comprehensive audit trail.

---

## Database Schema

### New Tables Created

#### 1. `consultation_medicines`
Tracks which medicines were used in each consultation.
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- consultation_id (INT, FK → consultations.consultation_id)
- item_id (INT, FK → inventory.item_id)
- quantity_used (DECIMAL, quantity consumed)
- unit (VARCHAR, e.g., "tablets", "ml", "units")
- created_at (TIMESTAMP, allocation date)
```

#### 2. `inventory_log`
Audit trail of all inventory changes (consumption, stock additions, adjustments).
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- item_id (INT, FK → inventory.item_id)
- quantity_change (DECIMAL, positive or negative)
- reason (ENUM: 'consumption', 'stock_in', 'adjustment', 'rollback')
- related_consultation_id (INT, FK → consultations.consultation_id, nullable)
- logged_by (INT, FK → users.user_id, user who initiated change)
- notes (TEXT, optional notes)
- created_at (TIMESTAMP, log date)
```

---

## Models Created

### 1. `ConsultationMedicineModel` (app/Models/ConsultationMedicineModel.php)
Maps the `consultation_medicines` table.
- Handles storage and retrieval of medicine allocations.
- Links consultations to inventory items used.

### 2. `InventoryLogModel` (app/Models/InventoryLogModel.php)
Maps the `inventory_log` table.
- Tracks all inventory changes with reason and related consultation.
- Supports audit trail queries.

---

## Controller Updates

### `ConsultationController.php` (app/Controllers/ConsultationController.php)

#### Updated Methods:

**1. `consultation()`**
- Now fetches medicine allocations for each consultation.
- Joins `consultation_medicines` and `inventory` tables.
- Provides `medicines` array to view with item_name, quantity_used, unit.

**2. `store_consultation()`**
- After creating consultation, calls `allocateMedicines($newConsultId)`.
- Processes medicine POST data (from form serialization).
- Decrements inventory quantities.
- Logs consumption to audit trail.

**3. `edit_consultation($id)`**
- Enhanced to fetch medicine allocations for display.
- Shows which medicines were used (read-only, cannot edit medicines in edit view).
- Passes medicines data to view.

**4. `delete_consultation($id)`**
- On deletion, retrieves all medicines allocated to that consultation.
- Restores inventory quantities (reverse decrement).
- Logs rollback to audit trail.
- Sets reason to 'rollback' with related_consultation_id.

**5. `allocateMedicines($consultation_id)` (NEW)**
- Private method called by `store_consultation()`.
- Receives JSON-encoded medicine data from POST.
- For each medicine:
  - Validates item exists in inventory.
  - Decrements inventory.quantity by quantity_used.
  - Inserts record in consultation_medicines.
  - Logs entry in inventory_log (reason='consumption').
- Handles errors gracefully; skips invalid items.

---

## View Updates

### 1. `add_consultation.php` (app/Views/Consultation/add_consultation.php)
**Added:** Medicine allocation fieldset
- Dynamic medicine selection rows.
- Dropdown to select inventory items.
- Quantity input field.
- Add/Remove row buttons.
- JavaScript functions:
  - `addMedicineRow()` - adds new medicine row.
  - `removeMedicineRow(index)` - removes row.
  - `serializeMedicines()` - converts form rows to JSON array and stores in hidden input.
- Form submits medicines as JSON: `{"medicines": [{"item_id": X, "quantity_used": Y, "unit": "Z"}, ...]}`

### 2. `consultation.php` (app/Views/Consultation/consultation.php)
**Added:** Medicine display section for each consultation
- Shows "Medicines Used:" with list of allocated medicines.
- Displays: item name, quantity used, unit.
- Shows "None" if no medicines allocated.

### 3. `edit_consultation.php` (app/Views/Consultation/edit_consultation.php)
**Added:** Read-only medicine display section
- Shows medicines that were used in the consultation.
- Read-only; medicines cannot be edited in the edit form.
- Shows "None allocated" if consultation has no medicines.

---

## Data Flow Workflow

### On Consultation Creation (POST /consultation/store):
```
1. Form submitted with:
   - consultation_date, diagnosis, treatment, prescription, notes
   - medicines (JSON array from serialization)

2. ConsultationController::store_consultation():
   - Validates patient or appointment
   - Inserts consultation record → gets $newConsultId
   - Calls allocateMedicines($newConsultId)

3. allocateMedicines():
   - Decodes JSON from POST['medicines']
   - For each medicine:
     a) Validates item exists in inventory
     b) Gets current inventory.quantity
     c) Updates inventory: quantity -= quantity_used
     d) Inserts row in consultation_medicines (links consultation to item/qty)
     e) Inserts row in inventory_log (records change for audit)
   - Returns with success message

4. Inventory now reflects consumed medicines ✓
```

### On Consultation View (/consultation):
```
1. ConsultationController::consultation():
   - Fetches all consultations
   - For each consultation:
     a) Queries consultation_medicines for that consultation_id
     b) JOINs with inventory to get item_name
     c) Builds medicines array: [{item_name, quantity_used, unit}, ...]
     d) Attaches to consultation record as 'medicines' key

2. View displays:
   - All consultation details
   - "Medicines Used:" section with list of medicines allocated
```

### On Consultation Edit (/consultation/edit/{id}):
```
1. ConsultationController::edit_consultation($id):
   - Fetches consultation record
   - Queries consultation_medicines for that consultation_id
   - JOINs with inventory to get item details
   - Passes medicines to view

2. View displays:
   - All consultation details (editable)
   - "Medicines Used in this Consultation:" (read-only, informational)
```

### On Consultation Deletion (/consultation/delete/{id}):
```
1. ConsultationController::delete_consultation($id):
   - Queries consultation_medicines for medicines allocated to this consultation
   - For each medicine:
     a) Restores inventory: quantity += quantity_used
     b) Logs entry in inventory_log (reason='rollback', related_consultation_id=$id)
   - Deletes consultation record

2. Inventory quantities restored ✓
3. Audit trail shows rollback with original consultation ID ✓
```

---

## Key Features

✓ **Automatic Decrement**
  - Inventory decrements when consultation is created with medicines.
  - Prevents overselling/over-allocation.

✓ **Rollback on Deletion**
  - If consultation is deleted, all medicines are "returned" to inventory.
  - Quantities restored automatically.

✓ **Audit Trail**
  - Every consumption and rollback logged in inventory_log.
  - Tracks which consultation consumed which medicines.
  - Tracks who initiated changes (logged_by).
  - Can query consumption history per medicine or per consultation.

✓ **Display Integration**
  - Medicines allocated shown in consultation listing.
  - Edit view shows medicines used (read-only).
  - Add view allows allocation during consultation creation.

✓ **Data Integrity**
  - Foreign keys link consultations to inventory items.
  - JSON serialization ensures correct data transfer from form.
  - Validation ensures items exist before allocation.

---

## Example Usage

### Create Consultation with Medicines:
```
1. User navigates to /consultation/add
2. Selects service type (walk-in or appointment)
3. Fills in diagnosis, treatment, prescription, notes
4. In "Medicine Allocation" section:
   - Clicks "Add Medicine"
   - Selects medicine from dropdown (e.g., "Paracetamol")
   - Enters quantity (e.g., "10")
   - Selects unit (e.g., "tablets")
   - Can add more medicines by clicking "Add Medicine" again
5. Submits form
6. System:
   - Creates consultation record
   - For each medicine:
     * Decrements inventory.quantity
     * Records in consultation_medicines
     * Logs in inventory_log
   - Displays success message
```

### View Consultation:
```
1. User navigates to /consultation
2. Sees consultation listing
3. Each consultation shows:
   - All details (ID, patient, diagnosis, etc.)
   - "Medicines Used:" section with list of allocated medicines
   - "None" if no medicines allocated
4. Can still Edit or Delete from listing
```

### Query Inventory Usage:
```sql
-- How much of a medicine was used in a consultation?
SELECT c.*, cm.quantity_used, cm.unit, i.item_name
FROM consultation_medicines cm
JOIN consultations c ON cm.consultation_id = c.consultation_id
JOIN inventory i ON cm.item_id = i.item_id
WHERE cm.item_id = 5
ORDER BY c.consultation_date DESC;

-- Total consumption of a medicine (audit trail):
SELECT SUM(quantity_change) as total_consumed
FROM inventory_log
WHERE item_id = 5 AND reason = 'consumption';

-- All rollbacks for a consultation:
SELECT * FROM inventory_log
WHERE related_consultation_id = 10 AND reason = 'rollback';
```

---

## Testing Checklist

- [ ] Create a consultation with one medicine → verify inventory decrements
- [ ] Create a consultation with multiple medicines → verify all decrement
- [ ] View consultation listing → verify medicines displayed
- [ ] Edit consultation → verify medicines shown (read-only)
- [ ] Delete consultation → verify inventory restored
- [ ] Query inventory_log → verify all changes recorded with correct reason and related_consultation_id
- [ ] Check inventory.quantity for consistency with sum of consumption logs

---

## Files Modified/Created

### Created:
- `app/Models/ConsultationMedicineModel.php`
- `app/Models/InventoryLogModel.php`
- `tools/create_consumption_tables.php` (setup script)

### Modified:
- `app/Controllers/ConsultationController.php` (consultation, store_consultation, edit_consultation, delete_consultation, allocateMedicines)
- `app/Views/Consultation/add_consultation.php` (medicine allocation fieldset + JS)
- `app/Views/Consultation/consultation.php` (display medicines)
- `app/Views/Consultation/edit_consultation.php` (display medicines used)

### Database:
- Created `consultation_medicines` table
- Created `inventory_log` table

---

## Notes

- Medicines cannot be edited in the edit_consultation view; they are read-only and informational.
- To modify medicines for a consultation, delete and recreate it.
- Inventory quantities must be updated manually in the inventory management section (not through consultations for stock additions).
- All medicine allocations are timestamped and can be queried by date range.
- The audit trail (inventory_log) provides complete traceability of all inventory changes.

