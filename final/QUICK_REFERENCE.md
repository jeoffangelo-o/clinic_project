# Quick Reference Guide - Clinic Management System

## Common Tasks

### Create a Consultation with Medicine Allocation

```
1. Navigate to: Consultation → Add Consultation
2. Choose service type: Walk-in or Appointment
3. Fill in:
   - Diagnosis
   - Treatment
   - Prescription
   - Notes (optional)
4. Add medicines:
   - Click "Add Medicine"
   - Select medicine from dropdown
   - Enter quantity (how much given)
   - Select unit (tablets, ml, etc.)
   - Repeat for each medicine
5. Click "Create Consultation"
✓ Inventory automatically decremented
✓ Medicines linked to consultation
```

### View Medicine Usage History

```
1. Navigate to: Consultation
2. Look for "Medicines Used:" section in each consultation
3. Shows list of medicines allocated with quantity and unit
4. Click "Edit" to see medicines used (read-only)
```

### View Inventory After Consultation

```
1. Navigate to: Inventory
2. Check item quantities
3. Should be decreased by amount allocated
4. Click on inventory item to see full details
```

### Generate a Report

```
1. Navigate to: Report
2. Click "Add Report"
3. Select report type (patient, consultation, appointment, inventory, etc.)
4. System generates snapshot of selected data
5. Click "View" to see report content
6. Click "Export CSV/Excel/PDF" to download
```

### Export Report in Different Formats

```
From Report View:

CSV Export (spreadsheet-compatible):
- Click "Export CSV" button

Excel Export (formatted workbook):
- Click "Export Excel" button
- Download as .xlsx file

PDF Export (formatted document):
- Click "Export PDF" button
- Professional table-based layout
```

### Query Inventory Consumption

```
SQL Query to see what medicines were used:

SELECT c.consultation_id, c.consultation_date, 
       i.item_name, cm.quantity_used, cm.unit
FROM consultation_medicines cm
JOIN consultations c ON cm.consultation_id = c.consultation_id
JOIN inventory i ON cm.item_id = i.item_id
ORDER BY c.consultation_date DESC;
```

### Check Audit Trail

```
SQL Query to see all inventory changes:

SELECT il.*, i.item_name
FROM inventory_log il
JOIN inventory i ON il.item_id = i.item_id
WHERE il.reason IN ('consumption', 'rollback', 'stock_in')
ORDER BY il.created_at DESC;

Filter by consultation:
WHERE il.related_consultation_id = [consultation_id];
```

---

## URLs Reference

| Task | URL |
|------|-----|
| Patient List | /patient |
| Add Patient | /patient/add |
| Edit Patient | /patient/edit/{id} |
| Delete Patient | /patient/delete/{id} |
| Appointment List | /appointment |
| Add Appointment | /appointment/add |
| Consultation List | /consultation |
| Add Consultation | /consultation/add?service=appoint |
| Add Walk-in | /consultation/add?service=walkin |
| Edit Consultation | /consultation/edit/{id} |
| Delete Consultation | /consultation/delete/{id} |
| Inventory List | /inventory |
| Add Inventory | /inventory/add |
| Report List | /report |
| Create Report | /report/store |
| View Report | /report/view/{id} |
| Export CSV | /report/export/{id} |
| Export XLSX | /report/export/{id}?format=xlsx |
| Export PDF | /report/export/{id}?format=pdf |

---

## Database Query Examples

### Find Consultation with Most Medicines

```sql
SELECT c.consultation_id, COUNT(cm.item_id) as med_count
FROM consultations c
LEFT JOIN consultation_medicines cm ON c.consultation_id = cm.consultation_id
GROUP BY c.consultation_id
ORDER BY med_count DESC
LIMIT 1;
```

### Find Low Stock Items

```sql
SELECT item_name, quantity 
FROM inventory 
WHERE quantity < 10 
ORDER BY quantity ASC;
```

### Medicine Usage Per Item

```sql
SELECT i.item_name, SUM(cm.quantity_used) as total_used
FROM consultation_medicines cm
JOIN inventory i ON cm.item_id = i.item_id
GROUP BY cm.item_id
ORDER BY total_used DESC;
```

### Consultations Per Date

```sql
SELECT DATE(consultation_date) as date, COUNT(*) as count
FROM consultations
GROUP BY DATE(consultation_date)
ORDER BY date DESC;
```

### Patient Visit Count

```sql
SELECT p.patient_id, p.patient_name, COUNT(c.consultation_id) as visit_count
FROM patients p
LEFT JOIN consultations c ON p.patient_id = c.patient_id
GROUP BY p.patient_id
ORDER BY visit_count DESC;
```

---

## Troubleshooting Quick Checks

### Medicines Not Showing in Consultation Listing
- [ ] Check consultation_medicines table has records
- [ ] Check inventory table has matching item_ids
- [ ] Reload page (clear browser cache)

### Inventory Not Decreasing
- [ ] Check allocation form appeared during consultation create
- [ ] Check POST data in browser dev tools
- [ ] Verify inventory quantities before/after in database

### Report Won't Export
- [ ] Check report has snapshot (report_data not null)
- [ ] Check writable/uploads directory has write permissions
- [ ] Verify PhpSpreadsheet/mPDF installed: `composer show`

### Medicine Allocation Form Missing
- [ ] Check add_consultation.php view file
- [ ] Search for "Medicine Allocation" text
- [ ] Check browser JavaScript console for errors

---

## System Health Check

Run these commands to verify system is working:

```bash
# Check PHP syntax
php -l app/Controllers/ConsultationController.php
php -l app/Models/ConsultationMedicineModel.php
php -l app/Models/InventoryLogModel.php

# Check files exist
test -f app/Views/Consultation/add_consultation.php
test -f app/Views/Consultation/consultation.php
test -f app/Views/Consultation/edit_consultation.php

# Check tables exist (from PHP)
php tools/verify_integration.php
```

---

## File Locations

| Component | Location |
|-----------|----------|
| Consultation Controller | `app/Controllers/ConsultationController.php` |
| Consultation Models | `app/Models/Consultation*.php` |
| Inventory Models | `app/Models/Inventory*.php` |
| Report Controller | `app/Controllers/ReportController.php` |
| Consultation Views | `app/Views/Consultation/` |
| Report Views | `app/Views/Report/` |
| Database Config | `app/Config/Database.php` |
| Routes Config | `app/Config/Routes.php` |

---

## Tips & Tricks

### Tip 1: Filter Reports by Type
When viewing reports, check the report_type field to filter:
- patient, consultation, appointment, inventory, announcement, comprehensive

### Tip 2: Bulk Export
To export multiple consultations' data:
1. Create a comprehensive report
2. Export as Excel for spreadsheet analysis

### Tip 3: Inventory Audit
To verify inventory hasn't been corrupted:
1. Sum all consumption_medicines quantities per item
2. Compare with current inventory.quantity + deleted consultations

### Tip 4: Backup Before Deleting
Always verify before deleting consultations (especially if they had medicines allocated).

### Tip 5: Use Walk-in for Unscheduled Visits
If a patient comes without appointment, use walk-in consultation mode.

---

## Role-Based Access

| Feature | Admin | Nurse | Staff | Student |
|---------|-------|-------|-------|---------|
| View Patients | ✓ | ✓ | ✓ | ✓ |
| Create Patient | ✓ | ✓ | ✓ | ✗ |
| Create Consultation | ✓ | ✓ | ✗ | ✗ |
| View Inventory | ✓ | ✓ | ✓ | ✓ |
| Manage Inventory | ✓ | ✓ | ✗ | ✗ |
| Generate Reports | ✓ | ✓ | ✓ | ✗ |
| Export Reports | ✓ | ✓ | ✓ | ✗ |

---

## Emergency Contacts

For database issues:
- Database config: `app/Config/Database.php`
- Database name: `cspc_clinic`
- Check writable/logs/ for error logs

---

**Last Updated:** 2024  
**System Version:** 1.3 (Medicine Consumption Tracking)  
**Status:** ✓ Production Ready
