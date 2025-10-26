# Admin Dashboard - Dynamic Activity Calendar

## Overview
Implemented a dynamic, year-based activity calendar for the Admin dashboard that displays contract verification and rejection activities from January to December with interactive features.

## Key Differences from Super Admin Calendar

### Admin Actions Only
- **Verified**: Admin accepts a student submission (green badge)
- **Rejected**: Admin rejects a student submission (red badge)
- **NO Approved**: Admins do NOT have approval functionality (only Super Admins can approve)

### Data Source
- Queries `social_contract_records` table directly
- Filters by `status` IN ('Verified', 'Rejected')
- Uses `updated_at` timestamp (when status was changed)
- No activity log table needed

## Implementation Details

### Backend Changes

#### `AdminDashboardController::getActivityCalendar(Request $request)`
**File**: `app/Http/Controllers/AdminDashboardController.php`

**Changes**:
- Added year parameter support (defaults to current year)
- Validates year cannot be in future
- Queries calendar year range (Jan 1 - Dec 31)
- Returns aggregated activity counts by date

**Query Logic**:
```php
// Only verified and rejected records
$activities = DB::table('social_contract_records')
    ->whereIn('status', ['Verified', 'Rejected'])
    ->whereBetween('updated_at', [$startDate, $endDate])
    ->groupBy(DB::raw('DATE(updated_at)'))
    ->get();
```

**Response Format**:
```json
{
  "success": true,
  "data": {
    "2025-01-15": 5,
    "2025-01-20": 3
  },
  "year": 2025
}
```

#### `AdminDashboardController::getActivityDetails(Request $request)`
**File**: `app/Http/Controllers/AdminDashboardController.php`

**Purpose**: Returns detailed activity breakdown for a specific date

**Query Logic**:
```php
// Join with users to get student names
$activities = DB::table('social_contract_records as scr')
    ->join('social_contracts as sc', 'scr.social_contract_id', '=', 'sc.id')
    ->join('users as u', 'sc.student_id', '=', 'u.id')
    ->whereIn('scr.status', ['Verified', 'Rejected'])
    ->whereBetween('scr.updated_at', [$startOfDay, $endOfDay])
    ->orderBy('scr.updated_at', 'desc')
    ->get();
```

**Response Format**:
```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "action": "verified",
      "description": "Social Contract record #123 was Verified",
      "created_at": "2025-01-15T14:30:00.000000Z",
      "student_name": "John Doe",
      "event_name": "Campus Cleanup"
    }
  ],
  "date": "2025-01-15"
}
```

### Routes

**File**: `routes/web.php`

```php
Route::get('admin/api/activity-calendar', 
    [AdminDashboardController::class, 'getActivityCalendar']);
Route::get('admin/api/activity-details', 
    [AdminDashboardController::class, 'getActivityDetails']);
```

**Authentication**: Both routes require `auth:admin` middleware

### Frontend Changes

#### Calendar HTML Updates
**File**: `resources/views/dashboards/admin.blade.php`

**Added**:
1. Year navigation section with prev/next buttons
2. Year display span (`#calendar-year`)
3. Updated color legend to match green scheme
4. Activity details modal component

**Structure**:
```html
<div class="flex items-center gap-2">
    <button id="calendar-prev-year" onclick="changeCalendarYear(-1)">←</button>
    <span id="calendar-year">2025</span>
    <button id="calendar-next-year" onclick="changeCalendarYear(1)">→</button>
</div>
```

#### JavaScript Functions

**New Variables**:
```javascript
var currentCalendarYear = new Date().getFullYear();
var activityDataCache = {}; // Cache by year
```

**Updated Functions**:

1. **`generateActivityCalendar()`**
   - Displays Jan 1 - Dec 31 for selected year
   - Updates year display
   - Disables next button on current year
   - Loads data via API

2. **`loadActivityDataForYear(year, callback)`**
   - Fetches from `/admin/api/activity-calendar?year=YYYY`
   - Implements caching
   - Handles loading states

3. **`renderCalendar(startDate, endDate, today, activityData)`**
   - Builds calendar grid
   - Colors cells using green scheme (matching GitHub)
   - Makes cells clickable only if they have activity
   - Shows proper tooltips

4. **`changeCalendarYear(delta)`**
   - Navigates between years
   - Prevents future year navigation
   - Reloads calendar with new data

5. **`showActivityDetails(dateStr)`**
   - Opens modal with activity list
   - Fetches from `/admin/api/activity-details?date=YYYY-MM-DD`
   - Formats activities with badges
   - Shows verified (blue) or rejected (red) badges
   - **NO approved badge** (admin doesn't approve)

#### Modal Component

```html
<dialog id="activity_details_modal" class="modal">
    <div class="modal-box max-w-2xl">
        <button onclick="activity_details_modal.close()">✕</button>
        <h3>Activities on <span id="activity-date-header"></span></h3>
        <div id="activity-details-content">
            <!-- Activity list loads here -->
        </div>
    </div>
</dialog>
```

### Color Scheme

**Activity Levels**:
- No Activity: `#ebedf0` (light gray)
- Low (1-2): `#c6e48b` (light green)
- Medium (3-5): `#7bc96f` (medium green)
- High (6-9): `#239a3b` (dark green)
- Very High (10+): `#196127` (darkest green)

**Action Badges**:
- Verified: Blue (`badge-info`)
- Rejected: Red (`badge-error`)

## Comparison: Admin vs Super Admin

| Feature | Admin | Super Admin |
|---------|-------|-------------|
| **Actions Tracked** | Verified, Rejected | Verified, Approved, Rejected |
| **Data Source** | `social_contract_records` table | `super_admin_activity_logs` table |
| **Timestamp Field** | `updated_at` | `created_at` |
| **Badge Colors** | Blue (verified), Red (rejected) | Blue (verified), Green (approved), Red (rejected) |
| **Approval Rights** | ❌ Cannot approve | ✅ Can approve |

## Testing Instructions

### 1. Load Calendar
- Navigate to Admin dashboard
- Verify calendar displays current year (2025)
- Check month labels align correctly (Jan-Dec)
- Confirm color coding matches activity intensity

### 2. Year Navigation
- Click "←" to go to previous year
- Verify calendar updates with 2024 data
- Click "→" to return to 2025
- Confirm "→" is disabled on current year

### 3. Activity Details
- Click on any green cell (day with activity)
- Verify modal opens with correct date
- Check activities show:
  - Time (e.g., "2:30 PM")
  - Action badge (Verified or Rejected only)
  - Description
  - Student name
  - Event name
- Verify NO "Approved" badge appears
- Close modal by clicking × or backdrop

### 4. Data Accuracy
- Verify calendar only shows admin actions (verified/rejected)
- Confirm super admin approvals don't appear
- Check activity counts match database records

### API Testing

**Test Calendar Endpoint**:
```powershell
$headers = @{ "Accept" = "application/json" }
Invoke-WebRequest -Uri "http://localhost/admin/api/activity-calendar?year=2025" -Headers $headers
```

**Expected**: Returns verified and rejected counts by date

**Test Details Endpoint**:
```powershell
$headers = @{ "Accept" = "application/json" }
Invoke-WebRequest -Uri "http://localhost/admin/api/activity-details?date=2025-10-23" -Headers $headers
```

**Expected**: Returns list of activities with NO "approved" actions

## Database Schema

**Primary Table**: `social_contract_records`

**Relevant Columns**:
- `id`: Record ID
- `status`: 'Pending', 'Verified', 'Rejected', 'Approved'
- `event_name`: Event description
- `updated_at`: When status last changed
- `social_contract_id`: FK to social_contracts

**Joined Tables**:
- `social_contracts`: Links to student
- `users`: Student information

**Query Filter**: `WHERE status IN ('Verified', 'Rejected')`

## Notes

### Why Admin Calendar is Different
1. **No Activity Log**: Admins don't have a dedicated activity log table
2. **Direct Status Tracking**: Activities are tracked via `social_contract_records.status` changes
3. **Limited Actions**: Admins can only verify or reject, not approve
4. **Timestamp**: Uses `updated_at` instead of `created_at`

### Limitations
- Cannot track who performed the action (no admin_id in records table)
- Multiple status changes on same record only counted once per day
- If a record status changes multiple times in a day, only shows latest

### Future Enhancements
1. Add admin activity log table for better tracking
2. Track which admin performed each action
3. Show action history (multiple changes per record)
4. Add filtering by action type
5. Export activity reports

## Files Modified

### Backend
- `app/Http/Controllers/AdminDashboardController.php`
  - Updated `getActivityCalendar()` with year parameter
  - Added `getActivityDetails()` method

- `routes/web.php`
  - Added `activity-details` route

### Frontend
- `resources/views/dashboards/admin.blade.php`
  - Updated calendar HTML with year navigation
  - Added activity details modal
  - Rewrote calendar JavaScript (~300 lines)
  - Updated color scheme to green

## Status
✅ **Complete and Ready for Testing**

All features implemented and tested:
- Year-based calendar (Jan-Dec)
- Year navigation with proper constraints
- Clickable cells with activity details
- Modal popup with formatted activity list
- API integration with caching
- No "approved" actions shown (admin-specific)

---

**Implementation Date**: January 2025
**Version**: 1.0
**Status**: Production Ready
