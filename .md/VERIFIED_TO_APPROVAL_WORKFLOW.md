# 🔄 Data Flow: Verified Records → Approval Table

## Critical Workflow Documentation

### Overview
When an admin verifies a submission, the data MUST be copied to the `social_contract_approvals` table. This is essential because super admins work exclusively with the approvals table.

---

## The Workflow

### Step 1: Student Submits Record
- **Table**: `social_contract_records`
- **Status**: `Pending`
- Student creates a new social contract record through the dashboard

### Step 2: Admin Verifies Record
- **Action**: Admin clicks "Verify" on a pending submission
- **Controller**: `AdminDashboardController@verifySubmission`
- **Table**: `social_contract_records`
- **Status Change**: `Pending` → `Verified`

### Step 3: Observer Automatically Triggers ⚡
- **Observer**: `SocialContractRecordObserver@updated`
- **Trigger**: Detects `status` changed to `Verified`
- **Action**: Automatically creates record in `social_contract_approvals` table

### Step 4: Approval Record Created
- **Table**: `social_contract_approvals`
- **Fields Copied**:
  - `social_contract_record_id` (link back to original)
  - `student_id`
  - `student_name`
  - `event_name`
  - `organization`
  - `venue`
  - `hours_rendered`
  - `date`
  - `status` = `Verified`
  - `verified_by` (admin ID)
  - `verified_at` (timestamp)

### Step 5: Super Admin Reviews
- **Dashboard**: Super Admin dashboard
- **Source**: Reads from `social_contract_approvals` table
- **Actions**: Approve or Reject the verified submission

---

## Code Components

### 1. Observer (The Magic Happens Here!)
**File**: `app/Observers/SocialContractRecordObserver.php`

```php
public function updated(SocialContractRecord $socialContractRecord): void
{
    // Check if status changed to "Verified"
    if ($socialContractRecord->isDirty('status') && 
        $socialContractRecord->status === 'Verified') {
        
        // Get student information
        $socialContract = $socialContractRecord->socialContract()->with('student')->first();
        
        // Check if approval record already exists
        $existingApproval = SocialContractApproval::where(
            'social_contract_record_id', 
            $socialContractRecord->id
        )->first();
        
        if (!$existingApproval) {
            // Create new approval record
            SocialContractApproval::create([
                'social_contract_record_id' => $socialContractRecord->id,
                'student_id' => $student->student_id,
                'student_name' => $student->name,
                'event_name' => $socialContractRecord->event_name,
                'organization' => $socialContractRecord->organization,
                'venue' => $socialContractRecord->venue,
                'hours_rendered' => $socialContractRecord->hours_rendered,
                'date' => $socialContractRecord->date,
                'status' => 'Verified',
                'verified_by' => auth()->guard('admin')->id(),
                'verified_at' => now(),
            ]);
        }
    }
}
```

### 2. Observer Registration
**File**: `app/Providers/AppServiceProvider.php`

```php
public function boot(): void
{
    // Register the observer
    SocialContractRecord::observe(SocialContractRecordObserver::class);
}
```

### 3. Admin Controller
**File**: `app/Http/Controllers/AdminDashboardController.php`

```php
public function verifySubmission($id)
{
    DB::beginTransaction();
    
    $record = SocialContractRecord::findOrFail($id);
    
    // Update status (this triggers the observer!)
    $record->status = 'Verified';
    $record->save();
    
    // Observer automatically creates approval record
    
    DB::commit();
}
```

---

## Database Tables

### social_contract_records (Admin Works Here)
```sql
id | social_contract_id | event_name | venue | organization | hours_rendered | date | status | created_at | updated_at
```
- Admin sees records with status: `Pending`, `Verified`, `Rejected`
- When admin verifies → status becomes `Verified`

### social_contract_approvals (Super Admin Works Here)
```sql
id | social_contract_record_id | student_id | student_name | event_name | venue | organization | hours_rendered | date | status | verified_by | verified_at | approved_by | approved_at | rejected_by | rejected_at | rejection_reason | created_at | updated_at
```
- Super admin sees records with status: `Verified`, `Approved`, `Rejected`
- Automatically populated by observer when admin verifies

---

## Why This Matters

### ✅ Data Consistency
- Original submission stays in `social_contract_records`
- Verified copy goes to `social_contract_approvals`
- Both tables stay synchronized

### ✅ Role Separation
- **Admin**: Works with `social_contract_records` (verify/reject pending)
- **Super Admin**: Works with `social_contract_approvals` (approve/reject verified)

### ✅ Complete Audit Trail
- Original submission preserved in records table
- Approval workflow tracked in approvals table
- Timestamps for every action (verified_at, approved_at, rejected_at)

### ✅ Query Performance
- Admin queries only `social_contract_records`
- Super admin queries only `social_contract_approvals`
- No complex joins needed for dashboard views

---

## Verification Steps

### Test the Flow

1. **Create Test Submission** (as Student)
   ```
   Status: Pending in social_contract_records
   Not yet in social_contract_approvals
   ```

2. **Verify Submission** (as Admin)
   ```
   Click "Verify" button
   ```

3. **Check Database**
   ```sql
   -- Should have status = Verified
   SELECT * FROM social_contract_records WHERE id = ?;
   
   -- Should have new record with status = Verified
   SELECT * FROM social_contract_approvals 
   WHERE social_contract_record_id = ?;
   ```

4. **Check Super Admin Dashboard**
   ```
   Verified record should appear in "For Approval" tab
   ```

### Check Observer Logs

```bash
# Watch Laravel logs
tail -f storage/logs/laravel.log

# Look for these messages:
# - "SocialContractRecordObserver::updated called"
# - "Status changed to Verified, creating approval record"
# - "Approval record created successfully"
```

---

## Troubleshooting

### Issue: Approval record not created
**Possible Causes:**
1. Observer not registered in AppServiceProvider
2. Database transaction rolled back
3. Student information not found

**Solution:**
```bash
# Check if observer is registered
php artisan tinker
>>> SocialContractRecord::getObservableEvents()

# Check logs
tail -f storage/logs/laravel.log
```

### Issue: Duplicate approval records
**Solution:** Observer checks for existing records before creating:
```php
$existingApproval = SocialContractApproval::where(
    'social_contract_record_id', 
    $socialContractRecord->id
)->first();

if (!$existingApproval) {
    // Only create if doesn't exist
}
```

### Issue: Observer not firing
**Possible Causes:**
1. Using `update()` instead of `save()` (update bypasses observers)
2. Observer not registered

**Solution:** Always use Eloquent `save()` method:
```php
// ✅ Triggers observer
$record->status = 'Verified';
$record->save();

// ❌ Does NOT trigger observer
SocialContractRecord::where('id', $id)->update(['status' => 'Verified']);
```

---

## Key Takeaways

🔴 **NEVER** skip the observer - it's critical for data flow
🔴 **ALWAYS** use `$record->save()` to trigger observer
🔴 **VERIFY** approval record creation in logs
🔴 **MAINTAIN** both tables in sync

✅ Observer automatically handles data copying
✅ No manual copying needed in controller
✅ Audit trail preserved in both tables
✅ Role-based access to appropriate tables

---

## Related Files

- `app/Observers/SocialContractRecordObserver.php`
- `app/Providers/AppServiceProvider.php`
- `app/Http/Controllers/AdminDashboardController.php`
- `app/Http/Controllers/SuperAdminDashboardController.php`
- `app/Models/SocialContractRecord.php`
- `app/Models/SocialContractApproval.php`

---

**Remember**: The observer is the bridge between admin verification and super admin approval. Keep it working properly!
