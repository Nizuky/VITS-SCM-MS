# TESTING CHECKLIST - Session Management Fix

## Quick Test Steps

### ✅ Test 1: Page Refresh (Admin)
1. Log in as Admin
2. Navigate to admin dashboard
3. Press F5 or click browser refresh
4. **Expected:** You stay logged in, dashboard loads normally

### ✅ Test 2: Page Refresh (Super Admin)
1. Log in as Super Admin
2. Navigate to super admin dashboard
3. Press F5 or click browser refresh
4. **Expected:** You stay logged in, dashboard loads normally

### ✅ Test 3: New Student Creation (Super Admin Logged In)
1. Log in as Super Admin
2. Go to student management page
3. Create a new student account
4. **Expected:** 
   - Student created successfully
   - You remain logged in as Super Admin
   - No redirect to login page

### ✅ Test 4: New Student Creation (Admin Logged In)
1. Log in as Admin (if admins can create students)
2. Create a new student account
3. **Expected:**
   - Student created successfully
   - You remain logged in as Admin
   - No redirect to login page

### ✅ Test 5: Refresh Button (See New Students)
1. Log in as Super Admin
2. Open student list
3. In another tab, create a new student (or have someone create one)
4. Go back to student list tab
5. Click the refresh/reload button on the page (not browser refresh)
6. **Expected:**
   - New student appears in the list
   - You stay logged in
   - Page updates without full reload

### ✅ Test 6: Student Registration Flow
1. Log out completely
2. Go to student registration page
3. Fill in registration form and submit
4. **Expected:**
   - Registration successful message
   - Redirected to login page
   - Must log in manually (not auto-logged in)

### ✅ Test 7: Multiple Tabs
1. Log in as Super Admin in Tab 1
2. Open Tab 2 with same session (new tab from Tab 1)
3. In Tab 2, create a new student
4. Go back to Tab 1
5. Click somewhere or refresh
6. **Expected:** Both tabs work normally, you stay logged in

### ✅ Test 8: Session Persistence
1. Log in as Admin/Super Admin
2. Close the browser completely
3. Open browser again
4. Navigate to admin/super admin dashboard URL
5. **Expected:** You are still logged in (session persists)

## Automated Tests

### Run Session Isolation Test
```powershell
php scripts/test_session_isolation.php
```
**Expected Output:**
```
🔍 SESSION ISOLATION TEST
==================================================

Step 1: Logging in as Super Admin...
✅ Super Admin logged in: [email]

Step 2: Creating new student account...
✅ Student created: [email]

Step 3: Simulating Fortify auto-login...
✅ Student logged in on web guard

Step 4: Checking if Super Admin session was affected...
✅ SUCCESS! Super Admin is still logged in

🎉 SESSION ISOLATION IS WORKING!
```

### Run Session Configuration Verification
```powershell
php scripts/verify_session_isolation.php
```
**Expected Output:**
```
✅ Session driver is database
✅ 61 active sessions found
✅ APP_KEY is stable
✅ Session configuration looks good
```

## Browser Console Checks

### Check SessionKeeper is Running
1. Open browser Developer Tools (F12)
2. Go to Console tab
3. Look for messages like:
   ```
   ✅ Session ping successful
   ✅ CSRF token refreshed
   🔄 Session keeper active
   ```

### Check for Errors
Look for any red errors related to:
- CSRF token mismatch
- 419 errors
- Session errors
- Auth errors

## Database Verification

### Check Sessions Table
```sql
SELECT * FROM sessions ORDER BY last_activity DESC LIMIT 10;
```
**Expected:** Recent sessions with your IP address

### Check Session Count
```sql
SELECT COUNT(*) FROM sessions;
```
**Expected:** Multiple active sessions

## What to Report if Issues Occur

### If Test Fails, Provide:
1. **Which test failed** (e.g., "Test 3: New Student Creation")
2. **What happened** (e.g., "Got redirected to login page")
3. **Browser console errors** (screenshot or copy/paste)
4. **Current URL** when the issue occurred
5. **Which user type** (Admin or Super Admin)
6. **Browser type and version**

### Example Issue Report:
```
Test: Test 3 - New Student Creation
User: Super Admin
Browser: Chrome 120
Issue: After creating student, got redirected to /super-admin/login
Console Error: "419 CSRF token mismatch"
URL: /super-admin/students
```

## Common Issues and Quick Fixes

### Issue: "419 Page Expired"
**Fix:** SessionKeeper should auto-refresh. If not:
1. Check if `session-keeper.js` is loaded
2. Check browser console for errors
3. Hard refresh (Ctrl+F5)

### Issue: Logged out after page refresh
**Check:**
1. Browser cookies enabled?
2. Session cookie present? (DevTools > Application > Cookies)
3. Middleware properly configured?

### Issue: Logged out after student creation
**Check:**
1. Is `IsolateWebGuardSession` middleware loaded?
2. Run `php scripts/test_session_isolation.php`
3. Check FortifyServiceProvider is registered

## Success Criteria

### All Tests Should Show:
- ✅ No unexpected logouts
- ✅ No CSRF token errors
- ✅ No 419 errors
- ✅ Sessions persist across refreshes
- ✅ Multiple guards work independently
- ✅ New students visible without full page reload

---

**Ready to Test!** 🚀

Start with Test 1 and work through each test in order. If any test fails, refer to the troubleshooting section in `SESSION_MANAGEMENT_FIX_COMPLETE.md`.
