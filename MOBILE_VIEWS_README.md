# Mobile View System - Growpath

## Overview

This mobile view system allows users to access Growpath on mobile devices without affecting the desktop experience. Mobile views are stored in `resources/views/mobile/` directory and are automatically served when a mobile device is detected.

## How It Works

1. **Auto-Detection**: The system automatically detects mobile devices based on User Agent
2. **Session-Based**: Mobile preference is stored in session for persistence
3. **Manual Toggle**: Users can force mobile or desktop view via URL parameters

## File Structure

```
resources/views/
├── mobile/
│   ├── dashboard.blade.php      # Student Dashboard (mobile)
│   ├── profile.blade.php        # Student Profile (mobile)
│   ├── kuesioner.blade.php      # Student Exam List (mobile)
│   ├── tipsbelajar.blade.php    # Student Tips (mobile)
│   ├── ortu-dashboard.blade.php  # Parent Dashboard (mobile)
│   ├── ortu-profile.blade.php   # Parent Profile (mobile)
│   └── admin-dashboard.blade.php # Admin Dashboard (mobile)
│
├── dashboard.blade.php          # Student Dashboard (desktop - UNCHANGED)
├── profile.blade.php            # Student Profile (desktop - UNCHANGED)
├── kuesioner.blade.php          # Student Exam List (desktop - UNCHANGED)
├── tipsbelajar.blade.php        # Student Tips (desktop - UNCHANGED)
├── ortu/
│   ├── ortu-dashboard.blade.php  # Parent Dashboard (desktop - UNCHANGED)
│   └── ortu-profile.blade.php   # Parent Profile (desktop - UNCHANGED)
└── admin/
    └── admin-dashboard.blade.php # Admin Dashboard (desktop - UNCHANGED)
```

## Key Files

### Backend
- `app/Helpers/ViewHelper.php` - View resolution helper
- `app/Http/Middleware/DetectMobileDevice.php` - Mobile device detection

### Frontend
- `resources/views/mobile/*.blade.php` - Mobile-optimized views

## Usage

### Automatic Detection
Mobile views are automatically served when:
- User accesses from a mobile device (phone/tablet)
- User has `mobile_view` session set to `true`

### Manual Toggle
Add these URL parameters to toggle view mode:
- `?mobile=1` - Force mobile view
- `?desktop=1` - Force desktop view

### Routes (for toggle buttons)
- `/mobile/toggle` - Toggle between mobile and desktop
- `/mobile/enable` - Force mobile view
- `/mobile/disable` - Force desktop view

## Important Notes

1. **DO NOT modify desktop views** - All changes for mobile should be in the `mobile/` folder
2. **Keep feature parity** - Mobile views should have the same functionality as desktop
3. **Test on actual devices** - Emulators may not accurately represent mobile behavior

## Adding New Mobile Views

1. Create the mobile view in `resources/views/mobile/`
2. Name it the same as the desktop view (e.g., `newpage.blade.php` → `mobile/newpage.blade.php`)
3. The `ViewHelper` will automatically resolve to the mobile version when detected

## Customizing Detection

Edit `app/Helpers/ViewHelper.php` to customize:
- Mobile detection patterns
- View resolution logic
- Toggle behavior