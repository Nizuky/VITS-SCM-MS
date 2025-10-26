# Single Table Architecture Refactoring

## Overview
Simplified the architecture from a dual-table system (social_contract_records + social_contract_approvals) to a single-table system (social_contract_records only).

## Previous Architecture
```
Student submits → social_contract_records (status: Pending)
↓
Admin verifies → Observer copies to social_contract_approvals (status: Verified)
↓
SuperAdmin approves → Updates both tables (status: Approved)
```

**Problems:**
- Data duplication
- Sync issues between tables
- Complex Observer logic
- Hard to maintain

## New Architecture
```
Student submits → social_contract_records (status: Pending)
↓
Admin verifies → social_contract_records (status: Verified)
↓
SuperAdmin approves → social_contract_records (status: Approved)
```

**Benefits:**
- Single source of truth
- No data duplication
- Simpler code
- Easier to maintain
- No sync issues

## Status Flow

### Student Dashboard
- Shows records where `student_id = current_user.id`
- All statuses visible to student

### Admin Dashboard
- **Pending Tab**: `status = 'Pending'`
- **Archived Tab**: `status IN ('Verified', 'Approved', 'Rejected')`

### Super Admin Dashboard
- **Pending Tab**: `status = 'Pending'`
- **For Approval Tab**: `status = 'Verified'`
- **Archived Tab**: `status IN ('Verified', 'Approved', 'Rejected')`

## Database Schema

### social_contract_records table
```
id
social_contract_id
status (Pending|Verified|Approved|Rejected)
rejection_reason
verified_at
verified_by (references admin_users.id)
approved_at
approved_by (references super_admins.id)
rejected_at
rejected_by (references admin_users.id or super_admins.id)
created_at
updated_at
```

## Changes Made

### 1. AdminDashboardController.php

#### getSubmissions()
- **Before**: Archived tab read from `social_contract_approvals`
- **After**: Archived tab reads from `social_contract_records WHERE status IN ('Verified', 'Approved', 'Rejected')`
- Uses eager loading: `->with(['socialContract.student'])`
- Extracts student info from relationship instead of denormalized fields

#### verifySubmission()
- **Before**: Updated status, Observer created approval record
- **After**: Directly sets `status`, `verified_at`, and `verified_by`
- Removed Observer verification checks

#### rejectSubmission()
- **Before**: Updated status, let Observer handle
- **After**: Directly sets `status`, `rejection_reason`, `rejected_at`, and `rejected_by`

### 2. SuperAdminDashboardController.php

#### getSubmissions()
- **For Approval Tab**:
  - **Before**: Read from `social_contract_approvals WHERE status = 'Verified'`
  - **After**: Read from `social_contract_records WHERE status = 'Verified'`
  
- **Archived Tab**:
  - **Before**: Read from `social_contract_approvals WHERE status IN ('Verified', 'Approved', 'Rejected')`
  - **After**: Read from `social_contract_records WHERE status IN ('Verified', 'Approved', 'Rejected')`

- **Deduplication Logic**: Simplified since all records use same `id` field now

#### approveSubmission()
- **Before**: 
  ```php
  $approval = SocialContractApproval::findOrFail($id);
  $approval->status = 'Approved';
  $approval->socialContractRecord->status = 'Approved';
  ```
- **After**:
  ```php
  $record = SocialContractRecord::findOrFail($id);
  $record->status = 'Approved';
  $record->approved_at = now();
  $record->approved_by = auth()->guard('superadmin')->id();
  ```

#### rejectSubmission()
- **Before**: Complex logic checking both `social_contract_approvals` and `social_contract_records`
- **After**: Simple, direct update to `social_contract_records` only
- Sets `status`, `rejection_reason`, `rejected_at`, `rejected_by`

### 3. Removed References
- Removed `use App\Models\SocialContractApproval;` from both controllers
- Removed Observer verification checks
- Removed dual-table update logic

## Next Steps

### 1. Remove Observer (Optional)
Since we're no longer copying data to a second table, you can:
- **Option A**: Delete `app/Observers/SocialContractRecordObserver.php`
- **Option B**: Repurpose it for notifications/logging only
- Unregister from `AppServiceProvider.php` if deleting

### 2. Drop social_contract_approvals Table

Create migration:
```bash
php artisan make:migration drop_social_contract_approvals_table
```

Migration content:
```php
public function up()
{
    Schema::dropIfExists('social_contract_approvals');
}

public function down()
{
    // Recreate table if needed for rollback
}
```

Run migration:
```bash
php artisan migrate
```

### 3. Remove Model
Delete `app/Models/SocialContractApproval.php`

### 4. Update Documentation
Update all documentation (RECORD_FLOW_COMPLETE.md, etc.) to reflect single-table architecture

### 5. Testing Checklist

#### Student Flow
- [ ] Submit new record (status: Pending)
- [ ] View own submissions in dashboard
- [ ] Receive notifications for status changes

#### Admin Flow
- [ ] View Pending tab (shows Pending records)
- [ ] Verify a submission (status → Verified, sets verified_at, verified_by)
- [ ] Reject a submission (status → Rejected, sets rejected_at, rejected_by, rejection_reason)
- [ ] View Archived tab (shows Verified, Approved, Rejected)

#### Super Admin Flow
- [ ] View Pending tab (shows Pending records)
- [ ] View For Approval tab (shows Verified records)
- [ ] Approve a verified submission (status → Approved, sets approved_at, approved_by)
- [ ] Reject a submission (status → Rejected, sets rejected_at, rejected_by, rejection_reason)
- [ ] View Archived tab (shows Verified, Approved, Rejected)

#### Data Integrity
- [ ] No duplicate records appear
- [ ] Timestamps are set correctly
- [ ] User IDs (verified_by, approved_by, rejected_by) are recorded
- [ ] Notifications are sent to students
- [ ] Activity logs are created for SuperAdmin actions

## Rollback Plan
If issues arise:
1. Restore Observer functionality
2. Re-enable social_contract_approvals table
3. Update controllers to read from both tables
4. Run data sync script to populate approvals table

## File Changes Summary
```
Modified:
- app/Http/Controllers/AdminDashboardController.php
- app/Http/Controllers/SuperAdminDashboardController.php

To Remove (next steps):
- app/Observers/SocialContractRecordObserver.php
- app/Models/SocialContractApproval.php
- database table: social_contract_approvals

To Update:
- RECORD_FLOW_COMPLETE.md
- VERIFIED_TO_APPROVAL_WORKFLOW.md
```

## Summary
The refactoring is complete in both controllers. All CRUD operations now work directly with `social_contract_records` table. The `social_contract_approvals` table is no longer used by the application and can be safely removed after testing confirms everything works correctly.
