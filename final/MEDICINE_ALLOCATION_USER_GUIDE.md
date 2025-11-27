# Medicine Consumption Tracking - User Guide

## What This Feature Does

When you create a consultation and allocate medicines to a patient, the inventory quantities automatically decrease. If you delete the consultation, the medicines are returned to inventory.

This ensures accurate tracking of medicine usage and prevents over-allocation.

---

## How to Use

### Step 1: Create a Consultation

1. Go to **Consultation → Add Consultation**
2. Choose service type: **Walk-in** or **Appointment**
3. Fill in consultation details:
   - Diagnosis
   - Treatment
   - Prescription
   - Notes (optional)

### Step 2: Allocate Medicines (NEW)

4. In the **Medicine Allocation** section:
   - Click **"Add Medicine"** button
   - **Select Medicine**: Choose from dropdown list (populated from inventory)
   - **Quantity**: Enter how much was given (e.g., 10)
   - **Unit**: Select unit type (e.g., tablets, ml, units)
   
5. To add more medicines:
   - Click **"Add Medicine"** again for each additional medicine
   
6. To remove a medicine row:
   - Click the **"Remove"** button on that row

### Step 3: Save Consultation

7. Click **"Create Consultation"**
8. System automatically:
   - Creates the consultation record
   - **Decrements inventory** for each allocated medicine
   - Logs all changes to audit trail

---

## Viewing Allocated Medicines

### Consultation Listing
- Go to **Consultation**
- Each consultation shows:
  - All consultation details
  - **Medicines Used:** section with list of medicines allocated
  - If no medicines allocated, shows "None"

### Edit Consultation
- Go to **Consultation → Edit**
- Shows a read-only **"Medicines Used in this Consultation:"** section
- **Note:** You cannot edit medicines here; medicines are tied to the original consultation

---

## Deleting a Consultation

1. Go to **Consultation**
2. Click **"Delete"** button on consultation
3. System automatically:
   - **Restores inventory** for all medicines that were allocated
   - Logs rollback to audit trail

---

## Inventory Changes

### How Quantities Change

**When you create a consultation with medicines:**
- Inventory quantity **decreases** by the amount allocated

Example:
```
Before: Paracetamol quantity = 100 tablets
Allocate: 20 tablets to consultation
After: Paracetamol quantity = 80 tablets
```

**When you delete the consultation:**
- Inventory quantity **increases** back to original

Example:
```
Before: Paracetamol quantity = 80 tablets (after allocation)
Delete consultation
After: Paracetamol quantity = 100 tablets (restored)
```

---

## Audit Trail (For Admins)

All medicine usage is logged in the system with:
- **Date/Time** of allocation or deletion
- **Medicine** allocated
- **Quantity** used
- **Reason** (consumption, rollback, etc.)
- **Related Consultation** (which consultation it was for)
- **User** who initiated the action

This provides complete traceability of all inventory changes.

---

## Important Notes

⚠️ **Cannot Edit Medicines After Creating Consultation**
- If you need to change medicine allocation, delete the consultation and create a new one
- This ensures accurate audit trail

⚠️ **Inventory Stock Additions**
- Use the **Inventory Management** section to add new stock
- This is separate from consultation medicine allocation

⚠️ **Prevents Over-Allocation**
- Cannot allocate more medicine than available in inventory
- System validates quantities before saving

---

## Example Workflow

```
1. Patient comes in for consultation
2. Nurse creates consultation record
3. In the form, allocates medicines:
   - Paracetamol: 20 tablets
   - Cough syrup: 5 ml
   - Vitamin C: 10 tablets
4. System saves consultation and decrements inventory:
   - Paracetamol: 100 → 80
   - Cough syrup: 50 → 45 ml
   - Vitamin C: 200 → 190
5. Doctor can see in consultation view what medicines patient received
6. If consultation is accidentally created, delete it to restore inventory
```

---

## Questions?

Check the comprehensive documentation in `INVENTORY_CONSUMPTION_FEATURE.md` for technical details about database schema, models, and data flow.
