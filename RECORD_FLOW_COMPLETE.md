# 🔄 Complete Social Contract Record Flow

## System Flow Overview

This document describes the complete workflow for social contract records from submission to final approval/rejection.

---

## Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│ STEP 1: STUDENT SUBMITS RECORD                                     │
│ Status: Pending                                                      │
│ Table: social_contract_records                                       │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│ VISIBLE TO:                                                          │
│ ✅ Admin - Pending Tab                                              │
│ ✅ Super Admin - Pending Tab                                        │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    │                               │
                    ▼                               ▼
        ┌───────────────────────┐       ┌───────────────────────┐
        │ ADMIN/SUPER ADMIN     │       │ ADMIN/SUPER ADMIN     │
        │ REJECTS               │       │ VERIFIES              │
        └───────────────────────┘       └───────────────────────┘
                    │                               │
                    ▼                               ▼
        ┌───────────────────────┐       ┌───────────────────────┐
        │ Status: Rejected      │       │ Status: Verified      │
        │ Tables:               │       │ Tables:               │
        │ • social_contract_    │       │ • social_contract_    │
        │   records             │       │   records             │
        │ • social_contract_    │       │ • social_contract_    │
        │   approvals (NEW)     │       │   approvals (NEW)     │
        └───────────────────────┘       └───────────────────────┘
                    │                               │
                    ▼                               ▼
        ┌───────────────────────┐       ┌───────────────────────┐
        │ VISIBLE TO:           │       │ VISIBLE TO:           │
        │ ✅ Admin - Archived   │       │ ✅ Admin - Archived   │
        │ ✅ Super Admin -      │       │ ✅ Super Admin -      │
        │    Archived           │       │    Archived           │
        │                       │       │ ✅ Super Admin -      │
        │                       │       │    For Approval       │
        └───────────────────────┘       └───────────────────────┘
                                                    │
                                    ┌───────────────┴───────────────┐
                                    │                               │
                                    ▼                               ▼
                        ┌───────────────────────┐       ┌───────────────────────┐
                        │ SUPER ADMIN           │       │ SUPER ADMIN           │
                        │ APPROVES              │       │ REJECTS (FINAL)       │
                        │ (From For Approval)   │       │ (From For Approval)   │
                        └───────────────────────┘       └───────────────────────┘
                                    │                               │
                                    ▼                               ▼
                        ┌───────────────────────┐       ┌───────────────────────┐
                        │ Status: Approved      │       │ Status: Rejected      │
                        │ Table:                │       │ Table:                │
                        │ • social_contract_    │       │ • social_contract_    │
                        │   approvals (UPDATE)  │       │   approvals (UPDATE)  │
                        └───────────────────────┘       └───────────────────────┘
                                    │                               │
                                    ▼                               ▼
                        ┌───────────────────────┐       ┌───────────────────────┐
                        │ VISIBLE TO:           │       │ VISIBLE TO:           │
                        │ ✅ Admin - Archived   │       │ ✅ Admin - Archived   │
                        │ ✅ Super Admin -      │       │ ✅ Super Admin -      │
                        │    Archived           │       │    Archived           │
                        └───────────────────────┘       └───────────────────────┘
```

---

## Detailed Step-by-Step Flow

### 📝 Step 1: Student Submits Record

**Action:** Student creates a new social contract record

**Database:**
- **Table**: `social_contract_records`
- **Status**: `Pending`
- **Fields Set**:
  - `event_name`
  - `venue`
  - `organization`
  - `hours_rendered`
  - `date`
  - `status = 'Pending'`

**Visibility:**
- ✅ **Admin Dashboard**: Pending Tab
- ✅ **Super Admin Dashboard**: Pending Tab

---

### ⚖️ Step 2a: Admin/Super Admin REJECTS (First Review)

**Who Can Do This:**
- ✅ Admin
- ✅ Super Admin

**Action:** Click "Reject" button on pending record

**Database Changes:**

1. **`social_contract_records` table**:
   - `status` → `'Rejected'`
   - `rejection_reason` → Admin/Super Admin's reason
   - `rejected_at` → Current timestamp

2. **`social_contract_approvals` table** (NEW RECORD CREATED by Observer):
   - All fields copied from `social_contract_records`
   - `status` → `'Rejected'`
   - `rejected_by` → Admin/Super Admin ID
   - `rejected_at` → Current timestamp
   - `rejection_reason` → Same as above

**Result:**
- Record removed from Pending tabs (both Admin & Super Admin)
- Record appears in Archived tabs (both Admin & Super Admin)
- Status shows as "Rejected"

**Visibility After Rejection:**
- ✅ **Admin Dashboard**: Archived Tab (Status: Rejected)
- ✅ **Super Admin Dashboard**: Archived Tab (Status: Rejected)
- ❌ **Super Admin Dashboard**: For Approval Tab (NOT visible)

---

### ✅ Step 2b: Admin/Super Admin VERIFIES (First Review)

**Who Can Do This:**
- ✅ Admin
- ✅ Super Admin

**Action:** Click "Verify" button on pending record

**Database Changes:**

1. **`social_contract_records` table**:
   - `status` → `'Verified'`

2. **`social_contract_approvals` table** (NEW RECORD CREATED by Observer):
   - All fields copied from `social_contract_records`
   - `status` → `'Verified'`
   - `verified_by` → Admin/Super Admin ID
   - `verified_at` → Current timestamp

**Result:**
- Record removed from Pending tabs (both Admin & Super Admin)
- Record appears in Archived tabs (both Admin & Super Admin)
- Record also appears in Super Admin's "For Approval" tab
- Status shows as "Verified"

**Visibility After Verification:**
- ✅ **Admin Dashboard**: Archived Tab (Status: Verified)
- ✅ **Super Admin Dashboard**: Archived Tab (Status: Verified)
- ✅ **Super Admin Dashboard**: For Approval Tab (Status: Verified) ⭐

---

### ✅ Step 3a: Super Admin APPROVES (Final Decision)

**Who Can Do This:**
- ✅ Super Admin ONLY

**Source:** Records from "For Approval" tab (status: Verified)

**Action:** Click "Approve" button on verified record

**Database Changes:**

1. **`social_contract_approvals` table** (UPDATE EXISTING):
   - `status` → `'Approved'`
   - `approved_by` → Super Admin ID
   - `approved_at` → Current timestamp

2. **`social_contract_records` table** (UPDATE):
   - `status` → `'Approved'`

**Result:**
- Record removed from "For Approval" tab
- Record remains/updates in Archived tabs (both Admin & Super Admin)
- Status shows as "Approved"

**Visibility After Approval:**
- ✅ **Admin Dashboard**: Archived Tab (Status: Approved)
- ✅ **Super Admin Dashboard**: Archived Tab (Status: Approved)
- ❌ **Super Admin Dashboard**: For Approval Tab (removed)

---

### ❌ Step 3b: Super Admin REJECTS from For Approval (Final Decision)

**Who Can Do This:**
- ✅ Super Admin ONLY

**Source:** Records from "For Approval" tab (status: Verified)

**Action:** Click "Reject" button on verified record

**Database Changes:**

1. **`social_contract_approvals` table** (UPDATE EXISTING):
   - `status` → `'Rejected'`
   - `approved_by` → Super Admin ID (who made final decision)
   - `rejected_at` → Current timestamp
   - `rejection_reason` → Super Admin's reason

2. **`social_contract_records` table** (UPDATE):
   - `status` → `'Rejected'`
   - `rejection_reason` → Same as above
   - `rejected_at` → Current timestamp

**Result:**
- Record removed from "For Approval" tab
- Record remains/updates in Archived tabs (both Admin & Super Admin)
- Status shows as "Rejected"

**Visibility After Final Rejection:**
- ✅ **Admin Dashboard**: Archived Tab (Status: Rejected)
- ✅ **Super Admin Dashboard**: Archived Tab (Status: Rejected)
- ❌ **Super Admin Dashboard**: For Approval Tab (removed)

---

## Summary Table

| Step | Action | Who | Source Table | Result Status | Destination Tables | Admin Pending | Admin Archived | SuperAdmin Pending | SuperAdmin For Approval | SuperAdmin Archived |
|------|--------|-----|--------------|---------------|-------------------|---------------|----------------|-------------------|----------------------|-------------------|
| 1 | Submit | Student | `social_contract_records` | Pending | Same table | ✅ | ❌ | ✅ | ❌ | ❌ |
| 2a | Reject | Admin/SuperAdmin | `social_contract_records` | Rejected | `social_contract_records`<br>`social_contract_approvals` (NEW) | ❌ | ✅ | ❌ | ❌ | ✅ |
| 2b | Verify | Admin/SuperAdmin | `social_contract_records` | Verified | `social_contract_records`<br>`social_contract_approvals` (NEW) | ❌ | ✅ | ❌ | ✅ | ✅ |
| 3a | Approve | SuperAdmin | `social_contract_approvals` | Approved | `social_contract_approvals` (UPDATE)<br>`social_contract_records` (UPDATE) | ❌ | ✅ | ❌ | ❌ | ✅ |
| 3b | Reject Final | SuperAdmin | `social_contract_approvals` | Rejected | `social_contract_approvals` (UPDATE)<br>`social_contract_records` (UPDATE) | ❌ | ✅ | ❌ | ❌ | ✅ |

---

## Key Points

### 🔑 Observer Magic

The **`SocialContractRecordObserver`** automatically:
1. Detects when `status` changes to `'Verified'` or `'Rejected'` in `social_contract_records`
2. Creates a corresponding record in `social_contract_approvals` table
3. Ensures both Admin and Super Admin see the same data in their Archived tabs

### 🔑 Two Types of Rejection

1. **First Review Rejection** (Step 2a):
   - Admin or Super Admin rejects a Pending record
   - Goes directly to Archived for both users
   - Does NOT go to "For Approval"

2. **Final Rejection** (Step 3b):
   - Super Admin rejects a Verified record from "For Approval"
   - Updates existing approval record
   - Remains in Archived for both users

### 🔑 Data Synchronization

- **Pending Tab Data**: From `social_contract_records` table (status = 'Pending')
- **For Approval Tab Data**: From `social_contract_approvals` table (status = 'Verified')
- **Archived Tab Data**: From `social_contract_approvals` table (status = 'Verified'/'Approved'/'Rejected')

### 🔑 Who Can Do What

| Action | Admin | Super Admin |
|--------|-------|-------------|
| View Pending Records | ✅ | ✅ |
| Verify Pending Records | ✅ | ✅ |
| Reject Pending Records | ✅ | ✅ |
| View For Approval | ❌ | ✅ |
| Approve Verified Records | ❌ | ✅ |
| Reject Verified Records (Final) | ❌ | ✅ |
| View Archived | ✅ | ✅ |

---

## Database Schema

### `social_contract_records` Table
```sql
id, social_contract_id, event_name, venue, organization, 
hours_rendered, date, status, rejection_reason, rejected_at,
created_at, updated_at
```

**Status Values**: `'Pending'`, `'Verified'`, `'Approved'`, `'Rejected'`

### `social_contract_approvals` Table
```sql
id, social_contract_record_id, student_id, student_name, 
event_name, organization, venue, hours_rendered, date, 
status, verified_by, verified_at, approved_by, approved_at, 
rejected_by, rejected_at, rejection_reason, created_at, updated_at
```

**Status Values**: `'Verified'`, `'Approved'`, `'Rejected'`

---

## Code Components

### 1. Observer
**File**: `app/Observers/SocialContractRecordObserver.php`

Automatically creates records in `social_contract_approvals` when status changes to `'Verified'` or `'Rejected'`

### 2. Admin Controller
**File**: `app/Http/Controllers/AdminDashboardController.php`

- `getSubmissions()` - Fetches pending + archived records
- `verifySubmission()` - Changes status to 'Verified', triggers observer
- `rejectSubmission()` - Changes status to 'Rejected', triggers observer

### 3. Super Admin Controller
**File**: `app/Http/Controllers/SuperAdminDashboardController.php`

- `getSubmissions()` - Fetches pending + for approval + archived records
- `verifySubmission()` - Changes status to 'Verified', triggers observer
- `rejectSubmission()` - Handles both pending and verified rejections
- `approveSubmission()` - Final approval, updates both tables

---

## Testing the Flow

### Test Scenario 1: Verify → Approve
1. Student submits → Status: Pending (both dashboards see it)
2. Admin verifies → Status: Verified (archived in both, for approval in super admin)
3. Super Admin approves → Status: Approved (archived in both)

### Test Scenario 2: Verify → Reject
1. Student submits → Status: Pending
2. Admin verifies → Status: Verified
3. Super Admin rejects → Status: Rejected (archived in both)

### Test Scenario 3: Direct Reject
1. Student submits → Status: Pending
2. Admin/Super Admin rejects → Status: Rejected (archived in both, NOT in for approval)

---

## Conclusion

✅ All data flows correctly between tables
✅ Admin and Super Admin see synchronized data
✅ Observer ensures data consistency
✅ Two-level approval process works as designed
✅ Both rejection types handled properly

**The system now perfectly matches your specified flow!** 🎉
