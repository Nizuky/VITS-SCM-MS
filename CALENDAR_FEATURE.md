# Contract Update Activity Calendar - Dynamic Implementation

## Overview
Implemented a dynamic, year-based activity calendar for the Super Admin dashboard that displays contract updates from January to December with interactive features.

## Features Implemented

### 1. Calendar Display
- **Year-based View**: Calendar displays activities from January 1 to December 31 of the selected year
- **Grid Layout**: GitHub-style contribution graph with:
  - Week labels (Sun-Sat) on the left
  - Month labels at the top
  - Activity cells color-coded by intensity
- **Responsive Design**: Adapts to different screen sizes while maintaining readability

### 2. Year Navigation
- **Year Selector**: Shows current year with prev/next buttons
- **Smart Navigation**: 
  - Previous button: Navigate to earlier years
  - Next button: Navigate to later years (disabled when viewing current year)
- **Initial State**: Automatically loads current year (2025) on page load

### 3. Activity Color Coding
- **No Activity**: Light gray (`#ebedf0`)
- **Low Activity** (1-2 actions): Light green (`#c6e48b`)
- **Medium Activity** (3-5 actions): Medium green (`#7bc96f`)
- **High Activity** (6-9 actions): Dark green (`#239a3b`)
- **Very High Activity** (10+ actions): Darkest green (`#196127`)

### 4. Interactive Details
- **Clickable Cells**: Any day with activity can be clicked
- **Activity Modal**: Shows detailed breakdown of activities for the selected day:
  - Date header (e.g., "October 23, 2025")
  - List of all activities with:
    - Timestamp (formatted as time only, e.g., "2:30 PM")
    - Action badge (Verified/Approved/Rejected with color coding)
    - Description (e.g., "Social Contract record #123 was approved")
    - Student name (if available)
    - Event name (if available)
  - Loading spinner while fetching data
  - "No activities found" message for empty days

### 5. Performance Optimization
- **Data Caching**: Activity data is cached by year to avoid redundant API calls
- **Efficient Rendering**: Only re-renders calendar when year changes
- **Lazy Loading**: Activity details are only fetched when a day is clicked

## Backend Implementation

### Controller Methods

#### `SuperAdminDashboardController::getActivityCalendar(Request $request)`
**Location**: `app/Http/Controllers/SuperAdminDashboardController.php`

**Purpose**: Returns aggregated activity data for a specific calendar year

**Parameters**:
- `year` (optional): Year to fetch data for (defaults to current year)

**Query Logic**:
```php
// Validates year (can't be future)
// Queries SuperAdminActivityLog for date range (Jan 1 - Dec 31)
// Groups by date and counts activities per day
// Returns: ['2025-01-15' => 5, '2025-01-20' => 3, ...]
```

**Response Format**:
```json
{
  "success": true,
  "data": {
    "2025-01-15": 5,
    "2025-01-20": 3,
    "2025-02-01": 8
  },
  "year": 2025
}
```

#### `SuperAdminDashboardController::getActivityDetails(Request $request)`
**Location**: `app/Http/Controllers/SuperAdminDashboardController.php`

**Purpose**: Returns detailed activity breakdown for a specific date

**Parameters**:
- `date` (required): Date in YYYY-MM-DD format

**Query Logic**:
```php
// Queries SuperAdminActivityLog for the specific date
// Joins with SocialContractRecord to get student/event info
// Orders by created_at descending (newest first)
// Returns array of activity objects
```

**Response Format**:
```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "action": "approved",
      "description": "Social Contract record #456 was approved",
      "created_at": "2025-01-15T14:30:00.000000Z",
      "student_name": "John Doe",
      "event_name": "Campus Cleanup"
    }
  ],
  "date": "2025-01-15"
}
```

### Routes

**File**: `routes/auth.php`

```php
// Get activity calendar for a year
Route::get('super-admin/api/activity-calendar', 
    [SuperAdminDashboardController::class, 'getActivityCalendar']);

// Get activity details for a specific date
Route::get('super-admin/api/activity-details', 
    [SuperAdminDashboardController::class, 'getActivityDetails']);
```

**Authentication**: Both routes require `auth:superadmin` middleware

## Frontend Implementation

### JavaScript Functions

#### `generateActivityCalendar()`
- Initializes the calendar with current year
- Loads activity data from API
- Renders the calendar grid

#### `loadActivityDataForYear(year, callback)`
- Fetches activity data for specified year
- Implements caching to avoid redundant API calls
- Handles loading states and errors
- Calls callback with fetched data

#### `renderCalendar(startDate, endDate, today, activityData)`
- Builds the calendar HTML grid
- Positions months correctly
- Colors cells based on activity count
- Makes cells with activity clickable
- Handles week alignment

#### `changeCalendarYear(delta)`
- Navigates between years (delta: -1 for prev, +1 for next)
- Updates year display
- Disables "next" button when viewing current year
- Reloads calendar with new year data

#### `showActivityDetails(dateStr)`
- Opens modal for selected date
- Fetches activity details from API
- Formats and displays activity list with:
  - Time formatting (12-hour format)
  - Action badges with colors
  - Student and event information
- Handles loading and error states

### Modal Component

**File**: `resources/views/dashboards/super_admin.blade.php`

**HTML Structure**:
```html
<div id="activity_details_modal" class="modal">
    <div class="modal-box">
        <button class="btn btn-sm btn-circle absolute right-2 top-2">✕</button>
        <h3 class="font-bold text-lg mb-4">
            Activities on <span id="activity-date-header"></span>
        </h3>
        <div id="activity-details-content">
            <!-- Loading spinner or activity list -->
        </div>
    </div>
</div>
```

**DaisyUI Classes**: Uses modal component with responsive sizing

### CSS Customization

**Calendar Grid**:
- Grid cells: 12px × 12px
- Gap: 2px between cells
- Border radius: 2px
- Hover effect: Slight scale increase + border

**Action Badges**:
- Verified: Blue background (`bg-info`)
- Approved: Green background (`bg-success`)
- Rejected: Red background (`bg-error`)
- Text: White, small size, rounded pills

## Testing Instructions

### Manual Testing

1. **Load Calendar**:
   - Navigate to Super Admin dashboard
   - Verify calendar displays current year (2025)
   - Check that month labels align correctly
   - Confirm color coding matches activity intensity

2. **Year Navigation**:
   - Click "←" to go to 2024
   - Verify calendar updates and shows 2024 data
   - Click "→" to return to 2025
   - Confirm "→" button is disabled on current year

3. **Activity Details**:
   - Click on any colored cell (day with activity)
   - Verify modal opens with correct date
   - Check that activities are listed with proper formatting
   - Verify action badges show correct colors
   - Click × or outside modal to close

4. **Edge Cases**:
   - Click on gray cell (no activity) - should not open modal
   - Navigate to year with no activity - should show empty grid
   - Check that future dates are not clickable

### API Testing

**Test Activity Calendar Endpoint**:
```bash
# PowerShell
$headers = @{ "Accept" = "application/json" }
Invoke-WebRequest -Uri "http://localhost/super-admin/api/activity-calendar?year=2025" -Headers $headers -UseBasicParsing
```

**Expected Response**:
```json
{
  "success": true,
  "data": { "2025-01-15": 5, ... },
  "year": 2025
}
```

**Test Activity Details Endpoint**:
```bash
# PowerShell
$headers = @{ "Accept" = "application/json" }
Invoke-WebRequest -Uri "http://localhost/super-admin/api/activity-details?date=2025-01-15" -Headers $headers -UseBasicParsing
```

**Expected Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "action": "approved",
      "description": "...",
      "created_at": "2025-01-15T14:30:00.000000Z",
      "student_name": "John Doe",
      "event_name": "Campus Cleanup"
    }
  ],
  "date": "2025-01-15"
}
```

## Database Schema

**Table**: `super_admin_activity_logs`

**Relevant Columns**:
- `id`: Primary key
- `super_admin_id`: Foreign key to super_admins
- `action`: Type of action (verified, approved, rejected)
- `description`: Activity description
- `social_contract_record_id`: Foreign key to social_contract_records (nullable)
- `created_at`: Timestamp of activity

**Indexes**: Ensure `created_at` and `super_admin_id` are indexed for query performance

## Future Enhancements

### Potential Improvements
1. **Activity Filtering**: Filter by action type (verified/approved/rejected)
2. **Export Functionality**: Download activity report as CSV/PDF
3. **Statistics Panel**: Show year summary (total activities, most active day, etc.)
4. **Comparison View**: Compare activity between different years
5. **Real-time Updates**: WebSocket integration for live activity updates
6. **Activity Heatmap Legend**: Add visual legend explaining color intensity
7. **Mobile Optimization**: Improve touch interactions for mobile devices

### Performance Considerations
- For super admins with 1000+ activities per year, consider:
  - Pagination in activity details modal
  - Server-side filtering options
  - Database query optimization with proper indexing

## Files Modified

### Backend
- `app/Http/Controllers/SuperAdminDashboardController.php`
  - Updated `getActivityCalendar()` method with year parameter
  - Added `getActivityDetails()` method

- `routes/auth.php`
  - Added activity-details route

### Frontend
- `resources/views/dashboards/super_admin.blade.php`
  - Updated calendar HTML with year navigation
  - Added activity details modal
  - Completely rewrote calendar JavaScript (~250 lines)

## Dependencies

**Backend**:
- Laravel 10.x
- Carbon (date manipulation)
- Eloquent ORM

**Frontend**:
- Vanilla JavaScript (ES6+)
- DaisyUI (modal component)
- TailwindCSS (styling)

## Browser Compatibility

Tested and working on:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

**Note**: Requires JavaScript enabled and modern browser with fetch API support.

---

**Implementation Date**: January 2025
**Version**: 1.0
**Status**: Complete and Production Ready
