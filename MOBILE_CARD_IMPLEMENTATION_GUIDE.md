# Mobile Card Layout Implementation Guide

## ✅ Completed
1. **Student Record Status Table** - Fully transformed with mobile cards, sorting, and delete functionality
2. **Student Support Tickets Table** - Fully transformed with mobile cards and sorting

## 🔄 In Progress / Remaining Work

### 1. Super Admin Support Tickets (`super_admin.blade.php`)

**HTML Updated**: ✅ The view file has been updated with mobile cards container

**JavaScript Updates Needed** in `super_admin.blade.php`:

Find the `renderTicketsTable()` function and update it to:

```javascript
function renderTicketsTable() {
    const tableBody = document.getElementById('ticket-table-body');
    const cardsContainer = document.getElementById('ticket-cards-container');
    
    if (tableBody) tableBody.innerHTML = '';
    if (cardsContainer) cardsContainer.innerHTML = '';
    
    // Filter logic
    const searchValue = document.getElementById('ticket-search-input')?.value.toLowerCase() || '';
    const filteredTickets = allTickets.filter(ticket => {
        if (!searchValue) return true;
        return (
            String(ticket.id).includes(searchValue) ||
            (ticket.student_id && ticket.student_id.toLowerCase().includes(searchValue)) ||
            (ticket.student_name && ticket.student_name.toLowerCase().includes(searchValue)) ||
            (ticket.type && ticket.type.toLowerCase().includes(searchValue)) ||
            (ticket.details && ticket.details.toLowerCase().includes(searchValue))
        );
    });
    
    if (filteredTickets.length === 0) {
        if (tableBody) tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-4">No tickets found</td></tr>';
        if (cardsContainer) cardsContainer.innerHTML = '<div class="text-center text-gray-500 py-4">No tickets found</div>';
        return;
    }
    
    // Render desktop table
    if (tableBody) {
        filteredTickets.forEach(ticket => {
            // ... existing table row rendering code ...
        });
    }
    
    // Render mobile cards
    if (cardsContainer) {
        filteredTickets.forEach(ticket => {
            let statusBadgeClass = '';
            let statusClass = '';
            switch(ticket.status) {
                case 'Pending':
                    statusBadgeClass = 'bg-yellow-100 text-yellow-800';
                    statusClass = 'status-open';
                    break;
                case 'Resolved':
                    statusBadgeClass = 'bg-green-100 text-green-800';
                    statusClass = 'status-resolved';
                    break;
                case 'Closed':
                    statusBadgeClass = 'bg-gray-100 text-gray-800';
                    statusClass = 'status-closed';
                    break;
                default:
                    statusBadgeClass = 'bg-blue-100 text-blue-800';
                    statusClass = 'status-in-progress';
            }
            
            const shortDetails = ticket.details.split('\n')[0];
            
            const card = document.createElement('div');
            card.className = `ticket-card ${statusClass}`;
            card.onclick = function() { showTicketDetails(ticket.id); };
            
            card.innerHTML = `
                <div class="ticket-card-header">
                    <div class="ticket-card-id">#${ticket.id}</div>
                    <div class="badge ${statusBadgeClass} font-semibold border-0">
                        ${ticket.status}
                    </div>
                </div>
                <div class="ticket-card-title">${ticket.type}</div>
                <div class="ticket-card-info">
                    <div class="ticket-card-row">
                        <span class="ticket-card-label">Student ID:</span>
                        <span class="ticket-card-value">${ticket.student_id || 'N/A'}</span>
                    </div>
                    <div class="ticket-card-row">
                        <span class="ticket-card-label">Student:</span>
                        <span class="ticket-card-value">${ticket.student_name || 'N/A'}</span>
                    </div>
                    <div class="ticket-card-row">
                        <span class="ticket-card-label">Details:</span>
                        <span class="ticket-card-value">${shortDetails}</span>
                    </div>
                    <div class="ticket-card-row">
                        <span class="ticket-card-label">Date:</span>
                        <span class="ticket-card-value">${ticket.date}</span>
                    </div>
                </div>
                <div class="ticket-card-footer">
                    <div class="ticket-card-actions">
                        ${ticket.status === 'Pending' ? `<button onclick="openResolveModal(${ticket.id}); event.stopPropagation();" class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg">Resolve</button>` : ''}
                    </div>
                </div>
            `;
            
            cardsContainer.appendChild(card);
        });
    }
}

// Add mobile sort handler after renderTicketsTable function
const mobileAdminTicketSort = document.getElementById('mobile-admin-ticket-sort-select');
if (mobileAdminTicketSort) {
    mobileAdminTicketSort.addEventListener('change', (e) => {
        const [column, direction] = e.target.value.split('-');
        let sortCol = 'ticket-' + (column === 'student_id' ? 'student-id' : column === 'student_name' ? 'student-name' : column === 'type' ? 'issue-type' : column);
        ticketSortColumn = sortCol;
        ticketSortDirection = direction;
        sortTickets(sortCol);
    });
}
```

**CSS Updates Needed** in `super_admin.blade.php` `<style>` section:

Add the same ticket card CSS that was added to student.blade.php (already provided above).

---

### 2. Super Admin Students Table (`students-page.blade.php`)

**HTML Structure** - Find the table and add:

```html
<!-- Mobile Sort Dropdown -->
<div class="lg:hidden px-4 mb-4">
    <select id="mobile-students-sort-select" class="select select-bordered select-sm w-full">
        <option value="student_id-asc">Student ID (A-Z)</option>
        <option value="student_id-desc">Student ID (Z-A)</option>
        <option value="name-asc">Name (A-Z)</option>
        <option value="name-desc">Name (Z-A)</option>
        <option value="email-asc">Email (A-Z)</option>
        <option value="email-desc">Email (Z-A)</option>
        <option value="year_level-asc">Year Level (Ascending)</option>
        <option value="year_level-desc">Year Level (Descending)</option>
    </select>
</div>

<!-- Wrap existing table in desktop-only div -->
<div class="hidden lg:block overflow-x-auto">
    <!-- EXISTING TABLE HERE -->
</div>

<!-- Add mobile cards container -->
<div class="lg:hidden flex flex-col gap-3" id="students-cards-container">
    <div class="text-center text-gray-500 py-4">Loading students...</div>
</div>
```

**CSS for Student Cards**:

```css
/* Student Card Styles */
.student-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: relative;
    transition: all 0.2s ease;
    border-left: 4px solid #6D28D9;
}

.student-card:active {
    transform: scale(0.98);
}

.student-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.student-card-id {
    font-weight: 700;
    font-size: 16px;
    color: #6D28D9;
}

.student-card-name {
    font-weight: 600;
    font-size: 18px;
    color: #1F2937;
    margin-bottom: 8px;
}

.student-card-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
}

.student-card-row {
    display: flex;
    align-items: flex-start;
    font-size: 14px;
}

.student-card-label {
    font-weight: 500;
    color: #6B7280;
    min-width: 90px;
    flex-shrink: 0;
}

.student-card-value {
    color: #374151;
    word-break: break-word;
}

.student-card-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #E5E7EB;
}

/* Dark theme */
[data-theme="dark"] .student-card {
    background: #1F2937;
}

[data-theme="dark"] .student-card-id {
    color: #A78BFA;
}

[data-theme="dark"] .student-card-name {
    color: #F9FAFB;
}

[data-theme="dark"] .student-card-label {
    color: #9CA3AF;
}

[data-theme="dark"] .student-card-value {
    color: #E5E7EB;
}

[data-theme="dark"] .student-card-footer {
    border-top-color: #374151;
}
```

**JavaScript for Students Rendering**:

```javascript
function renderStudentsTable() {
    const tableBody = document.getElementById('students-table-body');
    const cardsContainer = document.getElementById('students-cards-container');
    
    if (tableBody) tableBody.innerHTML = '';
    if (cardsContainer) cardsContainer.innerHTML = '';
    
    // ... filter logic ...
    
    // Render desktop table (existing code)
    if (tableBody) {
        filteredStudents.forEach(student => {
            // ... existing table row code ...
        });
    }
    
    // Render mobile cards
    if (cardsContainer) {
        filteredStudents.forEach(student => {
            const card = document.createElement('div');
            card.className = 'student-card';
            
            card.innerHTML = `
                <div class="student-card-header">
                    <div class="student-card-id">${student.student_id}</div>
                </div>
                <div class="student-card-name">${student.name}</div>
                <div class="student-card-info">
                    <div class="student-card-row">
                        <span class="student-card-label">Email:</span>
                        <span class="student-card-value">${student.email}</span>
                    </div>
                    <div class="student-card-row">
                        <span class="student-card-label">Year Level:</span>
                        <span class="student-card-value">${student.year_level || 'N/A'}</span>
                    </div>
                    <div class="student-card-row">
                        <span class="student-card-label">Status:</span>
                        <span class="student-card-value">
                            <span class="badge ${student.is_active ? 'badge-success' : 'badge-error'}">${student.is_active ? 'Active' : 'Inactive'}</span>
                        </span>
                    </div>
                </div>
                <div class="student-card-footer">
                    <button onclick="viewStudentDetails(${student.id})" class="btn btn-sm btn-primary">View</button>
                    <button onclick="editStudent(${student.id})" class="btn btn-sm btn-ghost">Edit</button>
                </div>
            `;
            
            cardsContainer.appendChild(card);
        });
    }
}
```

---

### 3. Admin & Super Admin Submissions Table

**Similar approach**:
- Add mobile sort dropdown
- Wrap table in `lg:block hidden`
- Add cards container with `lg:hidden`
- Create submission cards showing: Record ID, Student Name, Event Name, Date, Hours, Status
- Include action buttons (Verify/Approve/Reject based on role)

**Card Structure**:
```html
<div class="submission-card status-{status}">
    <div class="card-header">
        <div class="record-id">#${record.id}</div>
        <div class="status-badge">${status}</div>
    </div>
    <div class="card-title">${event_name}</div>
    <div class="card-info">
        <div class="card-row">
            <span class="label">Student:</span>
            <span class="value">${student_name}</span>
        </div>
        <div class="card-row">
            <span class="label">Date:</span>
            <span class="value">${date}</span>
        </div>
        <div class="card-row">
            <span class="label">Hours:</span>
            <span class="value">${hours}h</span>
        </div>
        <div class="card-row">
            <span class="label">Venue:</span>
            <span class="value">${venue}</span>
        </div>
    </div>
    <div class="card-footer">
        <!-- Action buttons here -->
    </div>
</div>
```

---

### 4. Admin Data Management Table

**Same pattern**:
- Mobile sort dropdown
- Desktop table (hidden on mobile)
- Mobile cards (hidden on desktop)
- Cards show: User ID, Name, Role, Email, Actions

---

## 📋 Implementation Checklist

For each remaining table:

1. ✅ Add mobile sort dropdown above table
2. ✅ Wrap table in `<div class="hidden lg:block">`
3. ✅ Add cards container `<div class="lg:hidden" id="xxx-cards-container">`
4. ✅ Add CSS for card styles in respective dashboard file
5. ✅ Update render function to populate both table AND cards
6. ✅ Add mobile sort event listener
7. ✅ Test responsiveness and functionality

---

## 🎨 Universal CSS Pattern

```css
.{entity}-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
    border-left: 4px solid {color};
}

.{entity}-card:active {
    transform: scale(0.98);
}

/* Header, Info, Footer sections follow same structure */
```

---

## ✨ Benefits Achieved

1. **Mobile-First**: Cards are touch-friendly and easy to scan
2. **Consistent UX**: All tables follow same pattern
3. **Data Integrity**: All information displayed on both views
4. **Sorting**: Full sorting capability on mobile via dropdown
5. **Actions**: All table actions available on cards
6. **Dark Mode**: Full dark theme support
7. **Performance**: No duplicate data loading, shared filtering logic

---

## 🚀 Next Steps

1. Apply the patterns above to remaining tables
2. Test on actual mobile devices
3. Adjust spacing/sizing if needed for specific screen sizes
4. Add animations/transitions for better UX
5. Consider adding swipe gestures for actions (optional enhancement)

---

**Note**: The CSS and JavaScript patterns are consistent across all implementations. Copy the student records example as a template and adjust field names and data structures for each specific table.
