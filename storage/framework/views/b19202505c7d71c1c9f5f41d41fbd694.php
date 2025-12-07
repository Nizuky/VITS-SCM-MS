<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin - Student Contract Management</title>
        <script>
        (function(){
            try{
                var s=localStorage.getItem('scms_admin_theme');
                console.log('Admin - Loaded theme from localStorage:', s);
                if(!s){
                    s='light';a
                    localStorage.setItem('scms_admin_theme','light');
                    console.log('Admin - Set default theme to light');
                }
                document.documentElement.setAttribute('data-theme',s);
                console.log('Admin - Applied theme:', s);
            }catch(e){
                console.error('Admin - Error loading theme:', e);
                document.documentElement.setAttribute('data-theme','light');
            }
        })();
        </script>
        <script>
        tailwind=typeof tailwind==='object'?tailwind:{};tailwind.config={theme:{extend:{colors:{'background-light':'#EDF1FA','primary-purple':'#6D28D9','primary-purple-hover':'#5B21B6','text-header':'#2B3674','text-muted':'#707EAE','badge-pending-text':'#E29C44','badge-pending-bg':'#FAEAD0','badge-verified-text':'#399552','badge-verified-bg':'#CCEED6','badge-rejected-text':'#CC525D','badge-rejected-bg':'#FFD7DB','success-green':'#4CAF50','success-green-hover':'#45a049','danger-red':'#CC525D','danger-red-hover':'#b33e46'},fontFamily:{sans:['Inter','sans-serif']}}}};
        </script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css">
        <?php
            $iconCandidates = ['storage/vits_white.png', 'vits_white.png', 'storage/vits_whites.png', 'vits_whites.png', 'vitswhite.png', 'vitslogo.png', 'public/storage/vits_white.png', 'storage/vits_header.png'];
            $iconUrl = null;
            $iconMTime = null;
            foreach ($iconCandidates as $relPath) {
                try {
                    $full = public_path($relPath);
                    if (file_exists($full)) {
                        $iconUrl = asset($relPath);
                        try {
                            $iconMTime = @filemtime($full) ?: null;
                        } catch (Throwable $e) {
                        }
                        break;
                    }
                } catch (Throwable $e) {
                }
            }
            if (!$iconUrl) {
                $iconUrl = asset('vits_white.png');
            }
            if ($iconUrl && $iconMTime) {
                $iconUrl .= '?v=' . $iconMTime;
            }
        ?>
        <link rel="icon" href="<?php echo e($iconUrl); ?>" sizes="any">
        <link rel="icon" href="<?php echo e($iconUrl); ?>" type="image/png">
        <link rel="shortcut icon" href="<?php echo e($iconUrl); ?>" type="image/png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
        body{font-family:'Inter',sans-serif}
        .active-nav{background-color:#6D28D9;color:#fff;border-radius:0.5rem}
        .flex-1-dynamic{flex:1 1 auto;min-height:0}
        .content-area-auto{height:auto;max-height:100%}
        .btn-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
        .btn-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
        .btn-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
        .btn-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
        .btn-primary-purple svg{stroke:#fff!important}
        .btn.bg-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
        .btn.bg-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
        .btn.bg-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
        .btn.bg-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
        .btn.bg-primary-purple svg{stroke:#fff!important}
        .btn.bg-success-green,.btn-success-green{background-color:#4CAF50!important;color:#fff!important;border-color:transparent!important}
        .btn.bg-success-green:hover,.btn-success-green:hover{background-color:#45a049!important;color:#fff!important}
        .btn.bg-success-green:active,.btn-success-green:active{background-color:#3d9341!important;color:#fff!important}
        .btn.bg-success-green:focus,.btn-success-green:focus{outline:none!important;box-shadow:0 0 0 2px rgba(34,197,94,0.35)!important}
        .btn.bg-danger-red,.btn-danger-red{background-color:#CC525D!important;color:#fff!important;border-color:transparent!important}
        .btn.bg-danger-red:hover,.btn-danger-red:hover{background-color:#b33e46!important;color:#fff!important}
        .btn.bg-danger-red:active,.btn-danger-red:active{background-color:#9c2936!important;color:#fff!important}
        .btn.bg-danger-red:focus,.btn-danger-red:focus{outline:none!important;box-shadow:0 0 0 2px rgba(204,82,93,0.35)!important}
        .btn-action{font-weight:600;border-width:1.5px;border-radius:6px;padding:4px 12px;font-size:0.75rem;height:auto;min-height:auto;background-color:transparent;transition:all 0.2s ease-in-out}
        .btn-action-verify{border-color:#13AAAA;color:#13AAAA}
        .btn-action-verify:hover{background-color:#13AAAA;color:white}
        .btn-action-reject{border-color:#CC525D;color:#CC525D}
        .btn-action-reject:hover{background-color:#CC525D;color:white}
        .bg-gradient-primary-purple{background-image:linear-gradient(to bottom,#bbacffff,#6D28D9)}
        .bg-gradient-pending{background-image:linear-gradient(to bottom,#FFF4DE,#FFE0A2)}
        .bg-gradient-accepted{background-image:linear-gradient(to bottom,#e1fff8ff,#2ce0e0ff)}
        .bg-gradient-rejected{background-image:linear-gradient(to bottom,#FFE2E5,#FFB7BE)}
        .bg-gradient-primary-purple h2,.bg-gradient-primary-purple p{word-wrap:break-word;overflow-wrap:break-word;-ms-word-wrap:break-word}
        .bg-gradient-primary-purple{-ms-flex-wrap:nowrap;flex-wrap:nowrap}
        .custom-tab-wrapper{background-color:white;border-radius:0.5rem;box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px -1px rgba(0,0,0,0.1);padding:0.5rem}
        .custom-tab{font-weight:600;color:#707EAE;padding:0.5rem 1.25rem;border-bottom:3px solid transparent;transition:all 0.2s ease-in-out;cursor:pointer}
        .custom-tab:hover{color:#6D28D9}
        .custom-tab-active{color:#6D28D9!important;border-bottom-color:#6D28D9!important}
        .details-input{width:100%;padding:0.75rem 1rem;border:1px solid #D1D5DB;border-radius:0.5rem;background-color:#F9FAFB;font-size:0.875rem;color:#111827}
        .details-label{font-weight:600;color:#374151;margin-bottom:0.5rem;display:block}
        .status-badge{display:inline-flex;align-items:center;padding:0.5rem 1.25rem;border-radius:0.5rem;font-weight:600;font-size:0.875rem}
        .status-badge.verified{background-color:#D1FAE5;color:#065F46}
        .status-badge.approved{background-color:#C8E6C9;color:#2E7D32}
        .status-badge.rejected{background-color:#FFD1D3;color:#CC525D}
        .scms-badge{display:inline-flex;align-items:center;justify-content:center;font-weight:600;border-radius:9999px;padding:0.25rem 0.5rem;font-size:0.75rem;line-height:1;border:0!important}
        .scms-badge--pending{background-color:#FAEAD0!important;color:#E29C44!important}
        .scms-badge--verified{background-color:#B2F5EA!important;color:#0D9488!important}
        .scms-badge--approved{background-color:#C8E6C9!important;color:#2E7D32!important}
        .scms-badge--rejected{background-color:#FFD7DB!important;color:#CC525D!important}
        .bg-custom{background-color:#EDF1FA;background-image:url('<?php echo e(asset("vits_bg_white.png")); ?>');background-repeat:no-repeat;background-size:cover;background-position:center;background-attachment:fixed}
        #toast-root{position:fixed;right:1rem;bottom:1rem;z-index:2000;display:flex;flex-direction:column;gap:0.75rem;pointer-events:none}
        #toast-root .alert{pointer-events:auto}
        .scms-toast{position:relative;display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 0.875rem;border-radius:9999px;color:#fff;box-shadow:0 10px 24px rgba(0,0,0,0.18),0 2px 6px rgba(0,0,0,0.08);border:1px solid rgba(255,255,255,0.08);max-width:520px}
        .scms-toast--success{background:linear-gradient(90deg,#16A34A,#22C55E)}
        .scms-toast--error{background:linear-gradient(90deg,#EF4444,#DC2626)}
        .scms-toast--info{background:linear-gradient(90deg,#6D28D9,#7C3AED)}
        .scms-toast__msg{font-weight:600;font-size:0.925rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .scms-toast__close{margin-left:0.25rem;display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:9999px;background:rgba(255,255,255,0.12);color:#fff;border:0;cursor:pointer;transition:background 0.18s ease,transform 0.12s ease}
        .scms-toast__close:hover{background:rgba(255,255,255,0.22);transform:translateY(-1px)}
        .scms-toast__progress{position:absolute;left:6px;right:6px;bottom:4px;height:3px;border-radius:9999px;background:rgba(255,255,255,0.55);transform-origin:left center}
        @keyframes scms-toast-progress{from{transform:scaleX(1)}to{transform:scaleX(0)}}
        [data-theme="dark"] body{color:#fff}
        [data-theme="dark"] .text-black,[data-theme="dark"] .text-gray-900,[data-theme="dark"] .text-gray-800,[data-theme="dark"] .text-gray-700,[data-theme="dark"] .text-gray-600,[data-theme="dark"] .text-gray-500,[data-theme="dark"] .text-text-header,[data-theme="dark"] .text-text-muted,[data-theme="dark"] h1,[data-theme="dark"] h2,[data-theme="dark"] h3,[data-theme="dark"] h4,[data-theme="dark"] h5,[data-theme="dark"] h6,[data-theme="dark"] p,[data-theme="dark"] span,[data-theme="dark"] label,[data-theme="dark"] td,[data-theme="dark"] th,[data-theme="dark"] a{color:#fff!important}
        [data-theme="dark"] .scms-badge--pending{background-color:#ff9d26ff!important;color:#ffffffff!important}
        [data-theme="dark"] .scms-badge--verified{background-color:#14B8A6!important;color:#ffffffff!important}
        [data-theme="dark"] .scms-badge--approved{background-color:#4CAF50!important;color:#ffffffff!important}
        [data-theme="dark"] .scms-badge--rejected{background-color:#b8000fff!important;color:#ffffffff!important}
        [data-theme="dark"] .bg-gradient-pending{background-image:linear-gradient(to top,#6D28D9,#FFE0A2)}
        [data-theme="dark"] .bg-gradient-accepted{background-image:linear-gradient(to top,#6D28D9,#aeffeeff)}
        [data-theme="dark"] .bg-gradient-rejected{background-image:linear-gradient(to top,#6D28D9,#FFB7BE)}
        [data-theme="dark"] .bg-custom{background-color:#0b0f19;background-image:url('<?php echo e(asset("storage/vits_bg_black.png")); ?>')}
        [data-theme="dark"] .table thead,[data-theme="dark"] .table thead tr,[data-theme="dark"] .table thead th{background-color:#374151!important}
        [data-theme="dark"] .table th,[data-theme="dark"] .table td{border-color:#374151!important}
        [data-theme="dark"] .bg-white{background-color:#1f2937!important}
        [data-theme="dark"] .bg-gray-100{background-color:#374151!important}
        [data-theme="dark"] .border-gray-200{border-color:#374151!important}
        [data-theme="dark"] .bg-base-100{background-color:#111827!important}
        [data-theme="dark"] .scms-toast{border-color:rgba(255,255,255,0.14);box-shadow:0 10px 24px rgba(0,0,0,0.35),0 2px 6px rgba(0,0,0,0.2)}
        [data-theme="dark"] .custom-tab-wrapper{background-color:#1f2937}
        [data-theme="dark"] .details-input{background-color:#374151;border-color:#4b5563;color:#fff}
        [data-theme="dark"] .status-badge.verified{background-color:#14B8A6;color:#fff}
        [data-theme="dark"] .status-badge.approved{background-color:#4CAF50;color:#fff}
        [data-theme="dark"] .status-badge.rejected{background-color:#b8000f;color:#fff}
        [data-theme="dark"] .btn-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
        [data-theme="dark"] .btn-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
        [data-theme="dark"] .btn-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
        [data-theme="dark"] .btn-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
        [data-theme="dark"] .btn-primary-purple svg{stroke:#fff!important}
        [data-theme="dark"] .btn-primary-purple span{color:#fff!important}
        [data-theme="dark"] .btn.bg-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
        [data-theme="dark"] .btn.bg-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
        [data-theme="dark"] .btn.bg-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
        [data-theme="dark"] .btn.bg-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
        [data-theme="dark"] .btn.bg-primary-purple svg{stroke:#fff!important}
        [data-theme="dark"] .btn.bg-primary-purple span{color:#fff!important}
        [data-theme="dark"] .btn.bg-success-green,[data-theme="dark"] .btn-success-green{background-color:#4CAF50!important;color:#fff!important;border-color:transparent!important}
        [data-theme="dark"] .btn.bg-success-green:hover,[data-theme="dark"] .btn-success-green:hover{background-color:#45a049!important;color:#fff!important}
        [data-theme="dark"] .btn.bg-success-green:active,[data-theme="dark"] .btn-success-green:active{background-color:#3d9341!important;color:#fff!important}
        [data-theme="dark"] .btn.bg-success-green:focus,[data-theme="dark"] .btn-success-green:focus{outline:none!important;box-shadow:0 0 0 2px rgba(34,197,94,0.45)!important}
        #status-filter-dropdown .btn{color:#707EAE;min-height:auto;height:auto;padding:0.25rem 0.5rem}
        #status-filter-dropdown .btn:hover{color:#6D28D9;background-color:rgba(109,40,217,0.1)}
        #status-filter-dropdown svg{fill:#707EAE}
        #status-filter-dropdown .btn:hover svg{fill:#6D28D9}
        #status-filter-dropdown{position:relative!important}
        #status-filter-dropdown .dropdown-content{position:fixed!important;box-shadow:0 10px 25px rgba(0,0,0,0.15)!important;z-index:9999!important}
        .dropdown-content li a{font-size:0.875rem;padding:0.5rem 1rem}
        .dropdown-content li a:hover{background-color:#6D28D9;color:#fff}
        [data-theme="dark"] .dropdown-content{background-color:#1f2937!important;border:1px solid #374151}
        [data-theme="dark"] .dropdown-content li a:hover{background-color:#6D28D9}
        [data-theme="dark"] #status-filter-dropdown .btn{color:#fff}
        [data-theme="dark"] #status-filter-dropdown .btn:hover{color:#6D28D9;background-color:rgba(109,40,217,0.2)}
        [data-theme="dark"] #status-filter-dropdown svg{fill:#fff}
        [data-theme="dark"] #status-filter-dropdown .btn:hover svg{fill:#6D28D9}
        thead{overflow:visible!important}
        table{overflow:visible!important}
        #action-status-header{overflow:visible!important;position:relative}
        .table thead tr{height:60px!important;max-height:60px!important}
        .table thead th{height:60px!important;max-height:60px!important;vertical-align:middle!important}
        /* Activity calendar legend - light theme */
        .activity-legend-0{background-color:#E5E7EB}
        .activity-legend-1{background-color:#E5D4FF}
        .activity-legend-2{background-color:#C9A9FF}
        .activity-legend-3{background-color:#A475FF}
        .activity-legend-4{background-color:#6D28D9}
        /* Activity calendar legend - dark theme */
        [data-theme="dark"] .activity-legend-0{background-color:#2c354aff}
        [data-theme="dark"] .activity-legend-1{background-color:#411b7a}
        [data-theme="dark"] .activity-legend-2{background-color:#5A3590}
        [data-theme="dark"] .activity-legend-3{background-color:#804ED6}
        [data-theme="dark"] .activity-legend-4{background-color:#A770FF}

        /* Dark mode for Data Management section - comprehensive fixes */
        /* Card backgrounds */
        [data-theme="dark"] #data-management-page > div > .bg-white{background-color:#1f2937!important}

        /* All text elements */
        [data-theme="dark"] #data-management-page h2,
        [data-theme="dark"] #data-management-page h4,
        [data-theme="dark"] #data-management-page p,
        [data-theme="dark"] #data-management-page th,
        [data-theme="dark"] #data-management-page td,
        [data-theme="dark"] #data-management-page span,
        [data-theme="dark"] #data-management-page .text-text-header,
        [data-theme="dark"] #data-management-page .text-text-muted{
            color:#ffffff!important;
        }

        /* Table headers with darker background */
        [data-theme="dark"] #data-management-page .table thead,
        [data-theme="dark"] #data-management-page .table thead tr,
        [data-theme="dark"] #data-management-page .table thead th,
        [data-theme="dark"] #data-management-page .bg-gray-50{
            background-color:#374151!important;
            color:#ffffff!important;
        }

        /* Table body rows - fix zebra striping for dark mode */
        [data-theme="dark"] #data-management-page .table-zebra tbody tr{
            background-color:#1f2937!important;
        }
        [data-theme="dark"] #data-management-page .table-zebra tbody tr:nth-child(even){
            background-color:#374151!important;
        }
        [data-theme="dark"] #data-management-page tbody tr{
            background-color:#1f2937!important;
        }
        [data-theme="dark"] #data-management-page tbody td{
            background-color:transparent!important;
            border-color:#4b5563!important;
        }

        /* Subtle text */
        [data-theme="dark"] #data-management-page .text-gray-500{
            color:#9ca3af!important;
        }

        /* Delete confirmation modals - dark mode */
        [data-theme="dark"] #confirm_delete_all_records_modal .modal-box,
        [data-theme="dark"] #confirm_delete_all_accounts_modal .modal-box,
        [data-theme="dark"] #confirm_delete_record_modal .modal-box,
        [data-theme="dark"] #confirm_delete_account_modal .modal-box{
            background-color:#1f2937!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal h3,
        [data-theme="dark"] #confirm_delete_all_accounts_modal h3,
        [data-theme="dark"] #confirm_delete_record_modal h3,
        [data-theme="dark"] #confirm_delete_account_modal h3{
            color:#ffffff!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal p,
        [data-theme="dark"] #confirm_delete_all_accounts_modal p,
        [data-theme="dark"] #confirm_delete_record_modal p,
        [data-theme="dark"] #confirm_delete_account_modal p{
            color:#d1d5db!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal .text-gray-600,
        [data-theme="dark"] #confirm_delete_all_accounts_modal .text-gray-600,
        [data-theme="dark"] #confirm_delete_record_modal .text-gray-600,
        [data-theme="dark"] #confirm_delete_account_modal .text-gray-600{
            color:#9ca3af!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal .bg-red-100,
        [data-theme="dark"] #confirm_delete_all_accounts_modal .bg-red-100,
        [data-theme="dark"] #confirm_delete_record_modal .bg-red-100,
        [data-theme="dark"] #confirm_delete_account_modal .bg-red-100{
            background-color:rgba(153, 27, 27, 0.3)!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal .bg-red-50,
        [data-theme="dark"] #confirm_delete_all_accounts_modal .bg-red-50,
        [data-theme="dark"] #confirm_delete_record_modal .bg-red-50,
        [data-theme="dark"] #confirm_delete_account_modal .bg-red-50{
            background-color:rgba(153, 27, 27, 0.2)!important;
            border-color:#991b1b!important;
        }
        [data-theme="dark"] #confirm_delete_record_modal .bg-yellow-50{
            background-color:rgba(161, 98, 7, 0.2)!important;
            border-color:#ca8a04!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal .text-red-600,
        [data-theme="dark"] #confirm_delete_all_accounts_modal .text-red-600,
        [data-theme="dark"] #confirm_delete_record_modal .text-red-600,
        [data-theme="dark"] #confirm_delete_account_modal .text-red-600{
            color:#fca5a5!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal .text-red-800,
        [data-theme="dark"] #confirm_delete_all_accounts_modal .text-red-800,
        [data-theme="dark"] #confirm_delete_record_modal .text-red-800,
        [data-theme="dark"] #confirm_delete_account_modal .text-red-800{
            color:#fca5a5!important;
        }
        [data-theme="dark"] #confirm_delete_all_records_modal .text-red-700,
        [data-theme="dark"] #confirm_delete_all_accounts_modal .text-red-700,
        [data-theme="dark"] #confirm_delete_record_modal .text-red-700,
        [data-theme="dark"] #confirm_delete_account_modal .text-red-700{
            color:#fecaca!important;
        }
        [data-theme="dark"] #confirm_delete_record_modal .text-yellow-600{
            color:#fbbf24!important;
        }
        [data-theme="dark"] #confirm_delete_record_modal .text-yellow-800{
            color:#fbbf24!important;
        }
        [data-theme="dark"] #confirm_delete_record_modal .text-yellow-700{
            color:#fde68a!important;
        }

        /* Prevent page-level horizontal scroll while allowing table scroll */
        body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* Ensure main content wrapper doesn't cause horizontal scroll */
        #page-container {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Table container: horizontal scroll only when needed */
        .overflow-x-auto {
            width: 100%;
            overflow-x: auto;
        }

        /* Force scrollbars to be visible */
        .overflow-x-auto.overflow-y-auto {
            scrollbar-width: thin; /* For Firefox */
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent; /* thumb track */
        }

        /* For Webkit browsers (Chrome, Safari, Edge) */
        .overflow-x-auto.overflow-y-auto::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .overflow-x-auto.overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 4px;
        }

        .overflow-x-auto.overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 4px;
        }

        .overflow-x-auto.overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.7);
        }

        /* Collapsible sidebar styles */
        #sidebar {
            transition: width 0.3s ease, min-width 0.3s ease, max-width 0.3s ease;
        }

        #sidebar.collapsed {
            width: 80px !important;
            min-width: 80px !important;
            max-width: 80px !important;
        }

        #sidebar.collapsed .menu-text,
        #sidebar.collapsed #admin-name,
        #sidebar.collapsed #admin-role,
        #sidebar.collapsed #collapse-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
        }

        /* Avatar section when collapsed - match the image spacing */
        #sidebar.collapsed #avatar-section {
            padding: 1rem 0.5rem;
        }

        #sidebar.collapsed #avatar-container {
            margin-bottom: 0;
        }

        #sidebar.collapsed #avatar-circle {
            width: 48px !important;
            height: 48px !important;
            ring-width: 2px;
        }

        #sidebar.collapsed #avatar-initials {
            font-size: 1.25rem;
        }

        /* Menu items when collapsed - centered with proper spacing */
        #sidebar.collapsed #menu-list {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        #sidebar.collapsed #menu-list li {
            margin-bottom: 0.5rem;
        }

        #sidebar.collapsed #menu-list a,
        #sidebar.collapsed ul.menu a,
        #sidebar.collapsed ul.menu button {
            justify-content: center;
            padding: 0.75rem 0;
        }

        #sidebar.collapsed #collapse-btn {
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        #sidebar.collapsed #collapse-icon {
            transform: rotate(180deg);
        }

        /* Mobile hamburger button */
        #mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background-color: #6D28D9;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: background-color 0.2s ease;
        }
        #mobile-menu-btn:hover {
            background-color: #5B21B6;
        }
        #mobile-menu-btn svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Mobile sidebar overlay/backdrop */
        #sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        #sidebar-backdrop.active {
            opacity: 1;
        }

        /* Mobile sidebar styles */
        @media (max-width: 768px) {
            #mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #sidebar-backdrop {
                display: block;
                pointer-events: none;
                opacity: 0;
            }
            #sidebar-backdrop.active {
                pointer-events: auto;
                opacity: 1;
            }

            #sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                width: 280px !important;
                min-width: 280px !important;
                max-width: 280px !important;
                border-radius: 0 !important;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease !important;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            /* Hide collapse button on mobile */
            #sidebar #collapse-btn {
                display: none;
            }

            /* Override collapsed state on mobile */
            #sidebar.collapsed {
                width: 280px !important;
                min-width: 280px !important;
                max-width: 280px !important;
            }
            #sidebar.collapsed .menu-text,
            #sidebar.collapsed #admin-name,
            #sidebar.collapsed #admin-role {
                opacity: 1;
                width: auto;
            }
            #sidebar.collapsed #avatar-circle {
                width: 6rem !important;
                height: 6rem !important;
            }
            #sidebar.collapsed #avatar-initials {
                font-size: 1.875rem;
            }
            #sidebar.collapsed #menu-list a,
            #sidebar.collapsed ul.menu a,
            #sidebar.collapsed ul.menu button {
                justify-content: flex-start;
                padding: 0.75rem 0.5rem;
            }

            /* Adjust main content for mobile */
            .flex.p-4.gap-4.min-h-screen {
                padding-top: 4.5rem;
            }

            /* Mobile close button styling */
            #mobile-close-btn {
                display: flex;
            }
        }

        /* Hide mobile close button on desktop */
        @media (min-width: 769px) {
            #mobile-close-btn {
                display: none !important;
            }
        }
        </style>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-custom">
<?php
    $BASE_PATH = rtrim(parse_url(url('/'), PHP_URL_PATH) ?? '', '/');
    $fullName = trim(auth('admin')->user()->name ?? 'Admin');
    
    // Check if name starts with "admin" (case-insensitive)
    $initials = '';
    if (preg_match('/^admin(.+)/i', $fullName, $matches)) {
        // Extract the part after "admin"
        $afterAdmin = trim($matches[1]);
        if (!empty($afterAdmin)) {
            $initials = mb_strtoupper(mb_substr($afterAdmin, 0, 1));
        }
    }
    
    // Fallback: use standard first letter logic if no "admin" prefix
    if (!$initials) {
        $nameWords = $fullName !== '' ? preg_split('/\s+/', $fullName) : [];
        if (!empty($nameWords)) {
            $initials = mb_strtoupper(mb_substr($nameWords[0], 0, 1));
            if (isset($nameWords[1]) && mb_strlen($nameWords[1]) > 0) {
                $initials .= mb_strtoupper(mb_substr($nameWords[1], 0, 1));
            }
        }
    }
    
    if (!$initials)
        $initials = 'AD';
?>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" aria-label="Open menu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Sidebar Backdrop -->
    <div id="sidebar-backdrop"></div>

    <div class="flex p-4 gap-4 min-h-screen">
        <!-- Sidebar -->
         <aside id="sidebar" class="flex flex-col bg-white rounded-2xl p-4 shadow-sm sticky top-4 self-start h-[calc(100vh-2rem)] overflow-hidden transition-all duration-300" style="width: 200px; min-width: 200px; max-width: 200px;">
            <!-- Mobile Close Button -->
            <button id="mobile-close-btn" class="md:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-gray-100 transition-colors z-10" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <!-- Profile Section -->
            <div id="avatar-section" class="flex flex-col items-center text-center p-4 border-b border-gray-200 transition-all duration-300">
                <div id="avatar-container" class="avatar placeholder mb-3 transition-all duration-300">
                    <div id="avatar-circle" class="w-24 h-24 rounded-full ring ring-[#6D28D9] ring-offset-2 ring-offset-base-100 bg-[#6D28D9] text-white flex items-center justify-center select-none transition-all duration-300" 
                         title="<?php echo e($fullName); ?>" 
                         aria-label="<?php echo e($fullName); ?>">
                        <span id="avatar-initials" class="text-3xl font-bold leading-none transition-all duration-300"><?php echo e($initials); ?></span>
                    </div>
                </div>
                <h2 id="admin-name" class="font-bold text-lg transition-opacity duration-300"><?php echo e($fullName); ?></h2>
                <p id="admin-role" class="text-sm text-gray-500 transition-opacity duration-300">Administrator</p>
            </div>

            <!-- Main Navigation -->
            <ul id="menu-list" class="menu p-0 my-4 flex-grow transition-all duration-300">
                <li>
                    <a class="py-3 pl-2 transition-all duration-300" id="nav-dashboard" onclick="showPage('dashboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2 transition-all duration-300" id="nav-submission" onclick="showPage('submission')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="menu-text">Submission</span>
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2 transition-all duration-300" id="nav-data-management" onclick="showPage('data-management')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span class="menu-text">Data Management</span>
                    </a>
                </li>
            </ul>

            <!-- Bottom Navigation -->
            <ul class="menu p-0 transition-all duration-300">
                <li>
                    <a class="py-3 pl-2 pr-0 w-full text-left flex items-center gap-2 min-h-0 transition-all duration-300" 
                       id="nav-settings" 
                       onclick="showPage('settings')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.096 2.572-1.065z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span class="menu-text">Settings</span>
                    </a>
                </li>
                <li>
                    <form id="logout-form-visible" 
                          action="<?php echo e(route('admin.logout')); ?>" 
                          method="POST" 
                          class="m-0 p-0 w-full flex" 
                          novalidate>
                        <?php echo csrf_field(); ?>
                        <button id="logout-button-visible" 
                                type="button" 
                                class="py-3 pl-2 pr-0 w-full text-left flex items-center gap-2 min-h-0 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="menu-text">Log Out</span>
                        </button>
                    </form>
                </li>
            </ul>
            
            <!-- Collapse button -->
            <button id="collapse-btn" class="btn btn-ghost btn-sm w-full mt-2">
                <svg id="collapse-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <span id="collapse-text">Hide</span>
            </button>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col gap-6 min-w-0" id="page-container">
            
            <!-- Include Page Partials -->
            <?php echo $__env->make('partials.admin.dashboard-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('partials.admin.submission-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('partials.admin.data-management-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('partials.admin.settings-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </main>
    </div>

    <!-- Include Modals -->
    <?php echo $__env->make('partials.admin.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Scripts -->
    <script>
        // Global variables
        var activeRow = null;
        var allSubmissions = []; // Store all submissions data
        var BASE_PATH = <?php echo json_encode($BASE_PATH, 15, 512) ?>;
        
        // Request cache to prevent duplicate API calls
        const requestCache = new Map();
        const pendingRequests = new Map();
        
        // Smart fetch with caching, deduplication, and retry logic
        async function smartFetch(url, options = {}, cacheTime = 5000) {
            const cacheKey = url + JSON.stringify(options);
            
            // Return cached response if available and not expired
            if (requestCache.has(cacheKey)) {
                const cached = requestCache.get(cacheKey);
                if (Date.now() - cached.timestamp < cacheTime) {
                    return cached.response.clone();
                }
                requestCache.delete(cacheKey);
            }
            
            // Deduplicate concurrent requests to same endpoint
            if (pendingRequests.has(cacheKey)) {
                return pendingRequests.get(cacheKey);
            }
            
            // Make request with retry logic
            const fetchPromise = (async () => {
                let lastError;
                for (let attempt = 0; attempt < 3; attempt++) {
                    try {
                        const response = await fetch(url, {
                            ...options,
                            signal: AbortSignal.timeout(15000) // 15 second timeout
                        });
                        
                        if (!response.ok && response.status >= 500) {
                            throw new Error(`Server error: ${response.status}`);
                        }
                        
                        // Cache successful GET requests
                        if (!options.method || options.method === 'GET') {
                            requestCache.set(cacheKey, {
                                response: response.clone(),
                                timestamp: Date.now()
                            });
                        }
                        
                        return response;
                    } catch (error) {
                        lastError = error;
                        if (attempt < 2) {
                            await new Promise(resolve => setTimeout(resolve, 500 * Math.pow(2, attempt)));
                        }
                    }
                }
                throw lastError;
            })();
            
            pendingRequests.set(cacheKey, fetchPromise);
            
            try {
                const result = await fetchPromise;
                return result;
            } finally {
                pendingRequests.delete(cacheKey);
            }
        }
        
        // Helper function to get current date/time in Philippine timezone (Asia/Manila, UTC+8)
        function getPhilippineDate(dateInput = null) {
            if (!dateInput) {
                // Return current time in Philippine timezone
                const now = new Date();
                const phTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
                return phTime;
            }
            
            // Parse input date - assume it's UTC from database
            const date = new Date(dateInput);
            
            // Convert to Philippine timezone (UTC+8)
            const phTime = new Date(date.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            return phTime;
        }
        
        // ==================== CSRF TOKEN SETUP ====================
        // CSRF token cache to reduce meta tag lookups
        let csrfTokenCache = null;
        let csrfTokenExpiry = 0;
        
        // Simple helper to get CSRF token from cache or meta tag
        function getCsrfToken() {
            if (csrfTokenCache && Date.now() < csrfTokenExpiry) {
                return csrfTokenCache;
            }
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            const token = metaTag ? metaTag.getAttribute('content') : '';
            if (token) {
                csrfTokenCache = token;
                csrfTokenExpiry = Date.now() + 60000;
            }
            return token;
        }
        
        // Auto-refresh CSRF token every 10 minutes
        setInterval(async () => {
            try {
                const response = await smartFetch(`${BASE_PATH}/api/refresh-csrf`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
                }, 0);
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const data = await response.json();
                if (data.token) {
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', data.token);
                        csrfTokenCache = data.token;
                        csrfTokenExpiry = Date.now() + 60000;
                    }
                }
            } catch (e) {
                console.warn('[CSRF] Failed to auto-refresh token:', e.message);
            }
        }, 10 * 60 * 1000); // Every 10 minutes
        
        // CSRF Cookie Helper Functions
        const cookieCache = new Map();
        let cookieCacheExpiry = 0;
        
        function getCookie(name) {
            if (Date.now() > cookieCacheExpiry) {
                cookieCache.clear();
                const cookies = document.cookie.split(';');
                cookies.forEach(cookie => {
                    const [key, value] = cookie.trim().split('=');
                    if (key) cookieCache.set(key, value);
                });
                cookieCacheExpiry = Date.now() + 5000;
            }
            return cookieCache.get(name) || null;
        }
        
        let lastCsrfFetch = 0;
        let csrfFetchPromise = null;
        
        async function ensureCsrfCookie() {
            if (csrfFetchPromise) return csrfFetchPromise;
            const now = Date.now();
            if (getCookie('XSRF-TOKEN') && now - lastCsrfFetch < 30000) return true;
            
            try {
                csrfFetchPromise = (async () => {
                    const response = await smartFetch(`${BASE_PATH}/api/csrf-cookie`, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
                    }, 0);
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    await new Promise(resolve => setTimeout(resolve, 50));
                    cookieCache.clear();
                    cookieCacheExpiry = 0;
                    lastCsrfFetch = Date.now();
                    return getCookie('XSRF-TOKEN') ? true : false;
                })();
                return await csrfFetchPromise;
            } catch (e) {
                console.error('[CSRF] Error:', e.message);
                return false;
            } finally {
                csrfFetchPromise = null;
            }
        }
        
        var hoursSortDirection = 'desc'; // 'asc' or 'desc'
        var studentIdSortDirection = 'desc'; // 'asc' or 'desc'
        var dateSortDirection = 'desc'; // 'asc' or 'desc'
        var studentNameSortDirection = 'desc'; // 'asc' or 'desc'
        var eventNameSortDirection = 'desc'; // 'asc' or 'desc'
        var organizationSortDirection = 'desc'; // 'asc' or 'desc'
        var statusSortDirection = 'desc'; // 'asc' or 'desc'
        var currentSortBy = null; // 'hours', 'studentid', 'date', 'studentname', 'eventname', 'organization', 'status'

        // Load dashboard statistics
        async function loadDashboardStats() {
            // Ensure CSRF cookie exists before making request
            await ensureCsrfCookie();
            
            smartFetch('/admin/api/dashboard-stats', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                credentials: 'same-origin'
            }, 30000)
            .then(async function(response) {
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok) {
                    throw response;
                }
                // Check if response is JSON (not redirected to login page)
                if (!contentType.includes('application/json')) {
                    console.warn('loadDashboardStats: non-JSON response', { 
                        status: response.status, 
                        url: response.url, 
                        contentType: contentType 
                    });
                    // Likely session expired, redirect to login
                    try { 
                        window.location.replace(BASE_PATH + '/admin/login'); 
                    } catch(_) { 
                        window.location.href = BASE_PATH + '/admin/login'; 
                    }
                    return Promise.reject(new Error('Non-JSON response'));
                }
                return response.json();
            })
            .then(function(result) {
                if (result.success && result.data) {
                    // Update the counts in the dashboard
                    document.getElementById('pending-requests-count').textContent = result.data.pending;
                    document.getElementById('accepted-requests-count').textContent = result.data.verified_this_week;
                    document.getElementById('rejected-requests-count').textContent = result.data.rejected_this_week;
                    
                    // Update the donut chart
                    updatePendingRequestsChart(result.data.pending);
                } else {
                    console.warn('Invalid dashboard stats format');
                }
            })
            .catch(function(error) {
                console.error('Error loading dashboard stats:', error);
            });
        }

        // Refresh submissions with visual feedback
        function refreshSubmissions() {
            var refreshBtn = document.getElementById('refresh-submissions-btn');
            var refreshIcon = document.getElementById('refresh-icon');
            
            if (refreshBtn && refreshIcon) {
                refreshBtn.disabled = true;
                refreshIcon.style.animation = 'spin 1s linear infinite';
                
                // Add CSS animation if not exists
                if (!document.getElementById('refresh-spin-style')) {
                    var style = document.createElement('style');
                    style.id = 'refresh-spin-style';
                    style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
                    document.head.appendChild(style);
                }
            }
            
            // Call loadSubmissions with loading indicator
            loadSubmissions(true);
            
            // Re-enable button after 2 seconds
            setTimeout(function() {
                if (refreshBtn && refreshIcon) {
                    refreshBtn.disabled = false;
                    refreshIcon.style.animation = '';
                }
            }, 2000);
        }

        // Chart instances
        let yearlyApprovedChart = null;
        let yearlyRejectedChart = null;

        // Initialize yearly charts
        function initYearlyCharts() {
            const approvedCanvas = document.getElementById('yearlyApprovedChart');
            const rejectedCanvas = document.getElementById('yearlyRejectedChart');
            
            if (!approvedCanvas || !rejectedCanvas) return;
            
            const approvedCtx = approvedCanvas.getContext('2d');
            const rejectedCtx = rejectedCanvas.getContext('2d');
            
            // Destroy existing charts if they exist
            if (yearlyApprovedChart) yearlyApprovedChart.destroy();
            if (yearlyRejectedChart) yearlyRejectedChart.destroy();
            
            // Detect current theme
            const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColor = isDarkTheme ? '#ffffff' : '#2B3674';
            const gridColor = isDarkTheme ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.06)';
            const borderColor = isDarkTheme ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.12)';
            const tooltipBg = isDarkTheme ? '#1f2937' : '#ffffff';
            const tooltipText = isDarkTheme ? '#ffffff' : '#111827';
            const tooltipBorder = isDarkTheme ? '#374151' : '#e5e7eb';
            
            // Create approved chart (green)
            yearlyApprovedChart = new Chart(approvedCtx, {
                type: 'bar',
                data: {
                    labels: ['2022', '2023', '2024', '2025', '2026'],
                    datasets: [{
                        label: 'Approved Records',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: '#10B981',
                        borderRadius: 8,
                        barThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: tooltipBg,
                            titleColor: tooltipText,
                            bodyColor: tooltipText,
                            borderColor: tooltipBorder,
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                color: textColor,
                                stepSize: 1
                            },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        }
                    }
                }
            });
            
            // Create rejected chart (red)
            yearlyRejectedChart = new Chart(rejectedCtx, {
                type: 'bar',
                data: {
                    labels: ['2022', '2023', '2024', '2025', '2026'],
                    datasets: [{
                        label: 'Rejected Records',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: '#EF4444',
                        borderRadius: 8,
                        barThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: tooltipBg,
                            titleColor: tooltipText,
                            bodyColor: tooltipText,
                            borderColor: tooltipBorder,
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                color: textColor,
                                stepSize: 1
                            },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        }
                    }
                }
            });
        }

        // Update yearly charts with data
        function updateYearlyCharts(submissions) {
            if (!yearlyApprovedChart || !yearlyRejectedChart || !submissions) return;
            
            // Create maps for approved and rejected records by year
            const approvedByYear = new Map();
            const rejectedByYear = new Map();
            
            submissions.forEach(function(record) {
                try {
                    const dateStr = record.approved_at || record.rejected_at || record.updated_at || record.created_at;
                    if (!dateStr) return;
                    
                    const date = getPhilippineDate(dateStr);
                    if (isNaN(date.getTime())) return;
                    
                    const year = date.getFullYear();
                    
                    // Only count Approved status (super admin approved)
                    if (record.status === 'Approved') {
                        approvedByYear.set(year, (approvedByYear.get(year) || 0) + 1);
                    } else if (record.status === 'Rejected') {
                        rejectedByYear.set(year, (rejectedByYear.get(year) || 0) + 1);
                    }
                } catch (e) {
                    console.warn('Error processing record for yearly chart:', e);
                }
            });
            
            // Get sorted years
            const allYears = new Set([...approvedByYear.keys(), ...rejectedByYear.keys()]);
            const sortedYears = Array.from(allYears).sort((a, b) => a - b);
            
            // If no data, use default years
            const yearLabels = sortedYears.length > 0 ? sortedYears : [2022, 2023, 2024, 2025, 2026];
            const approvedData = yearLabels.map(year => approvedByYear.get(year) || 0);
            const rejectedData = yearLabels.map(year => rejectedByYear.get(year) || 0);
            
            // Update charts
            yearlyApprovedChart.data.labels = yearLabels.map(String);
            yearlyApprovedChart.data.datasets[0].data = approvedData;
            yearlyApprovedChart.update();
            
            yearlyRejectedChart.data.labels = yearLabels.map(String);
            yearlyRejectedChart.data.datasets[0].data = rejectedData;
            yearlyRejectedChart.update();
        }

        // Load submissions from database
        var lastSubmissionsData = null; // Store last data to detect changes
        var isLoadingSubmissions = false; // Prevent concurrent requests
        
        async function loadSubmissions(showLoading = true) {
            // Prevent concurrent requests
            if (isLoadingSubmissions) {
                console.log('Already loading submissions, skipping...');
                return;
            }
            
            var tbody = document.getElementById('submission-table-body');
            
            // Only show loading state on initial load or explicit refresh
            if (showLoading && !lastSubmissionsData) {
                tbody.innerHTML = '<tr id="loading-row"><td colspan="7" class="text-center py-8">' +
                    '<span class="loading loading-spinner loading-lg text-primary-purple"></span>' +
                    '<p class="mt-2 text-text-muted">Loading submissions...</p></td></tr>';
            }
            
            isLoadingSubmissions = true;
            
            // Ensure CSRF cookie exists before making request
            await ensureCsrfCookie();
            
            // Fetch submissions from API with caching
            smartFetch(`/admin/api/submissions`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                credentials: 'same-origin'
            }, 20000)
            .then(async function(response) {
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok) {
                    throw response;
                }
                // Check if response is JSON (not redirected to login page)
                if (!contentType.includes('application/json')) {
                    console.warn('loadSubmissions: non-JSON response', { 
                        status: response.status, 
                        url: response.url, 
                        contentType: contentType 
                    });
                    // Likely session expired, redirect to login
                    try { 
                        window.location.replace(BASE_PATH + '/admin/login'); 
                    } catch(_) { 
                        window.location.href = BASE_PATH + '/admin/login'; 
                    }
                    return Promise.reject(new Error('Non-JSON response'));
                }
                return response.json();
            })
            .then(function(result) {
                isLoadingSubmissions = false;
                
                if (result.success && result.data) {
                    // Always update the data when we get a successful response
                    lastSubmissionsData = result.data;
                    allSubmissions = result.data;
                    renderSubmissions(result.data);
                    updateWeeklySummaryFromData(result.data);
                    updateYearlyCharts(result.data);
                } else {
                    throw new Error('Invalid response format');
                }
            })
            .catch(function(error) {
                isLoadingSubmissions = false;
                console.error('Error loading submissions:', error);
                
                // Only show error if we have no cached data AND it's the first load
                if (!lastSubmissionsData && showLoading) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">' +
                        'Failed to load submissions. Please refresh the page.</td></tr>';
                }
                // If we have cached data, silently keep using it - no toast notification
                // The data will automatically update on next successful refresh
            });
        }
        
        // Render submissions in the table
        function renderSubmissions(submissions, sortBy = null) {
            var tbody = document.getElementById('submission-table-body');
            
            if (!submissions || submissions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-text-muted">' +
                    'No submissions found.</td></tr>';
                return;
            }
            
            // Sort if requested
            var sortedSubmissions = submissions.slice(); // Create a copy
            if (sortBy === 'hours') {
                sortedSubmissions.sort(function(a, b) {
                    var ha = parseInt(a.hours_rendered) || 0;
                    var hb = parseInt(b.hours_rendered) || 0;
                    return hoursSortDirection === 'asc' ? ha - hb : hb - ha;
                });
            } else if (sortBy === 'studentid') {
                sortedSubmissions.sort(function(a, b) {
                    var idA = (a.student_id || '').toString();
                    var idB = (b.student_id || '').toString();
                    return studentIdSortDirection === 'asc' ? idA.localeCompare(idB) : idB.localeCompare(idA);
                });
            } else if (sortBy === 'date') {
                sortedSubmissions.sort(function(a, b) {
                    var dateA = getPhilippineDate(a.date || 0);
                    var dateB = getPhilippineDate(b.date || 0);
                    return dateSortDirection === 'asc' ? dateA - dateB : dateB - dateA;
                });
            } else if (sortBy === 'studentname') {
                sortedSubmissions.sort(function(a, b) {
                    var nameA = (a.student_name || '').toString().toLowerCase();
                    var nameB = (b.student_name || '').toString().toLowerCase();
                    return studentNameSortDirection === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });
            } else if (sortBy === 'eventname') {
                sortedSubmissions.sort(function(a, b) {
                    var eventA = (a.event_name || '').toString().toLowerCase();
                    var eventB = (b.event_name || '').toString().toLowerCase();
                    return eventNameSortDirection === 'asc' ? eventA.localeCompare(eventB) : eventB.localeCompare(eventA);
                });
            } else if (sortBy === 'organization') {
                sortedSubmissions.sort(function(a, b) {
                    var orgA = (a.organization || '').toString().toLowerCase();
                    var orgB = (b.organization || '').toString().toLowerCase();
                    return organizationSortDirection === 'asc' ? orgA.localeCompare(orgB) : orgB.localeCompare(orgA);
                });
            } else if (sortBy === 'status') {
                sortedSubmissions.sort(function(a, b) {
                    var statusA = (a.status || '').toString().toLowerCase();
                    var statusB = (b.status || '').toString().toLowerCase();
                    return statusSortDirection === 'asc' ? statusA.localeCompare(statusB) : statusB.localeCompare(statusA);
                });
            }
            
            var html = '';
            sortedSubmissions.forEach(function(record) {
                var status = record.status || 'Pending';
                var isPending = status === 'Pending';
                var isVerified = status === 'Verified';
                var isApproved = status === 'Approved';
                var isRejected = status === 'Rejected';
                
                // Pending tab: Only show records with "Pending" status
                // Archived tab: Show Verified, Approved, and Rejected records (no action buttons)
                var dataStatus = isPending ? 'Pending' : 'Archived';
                var dataArchiveStatus = isPending ? '' : status;
                
                var dateStr = record.date ? formatDate(record.date) : 'â€”';
                
                // Get action date based on status - use the actual timestamp fields
                var actionDateStr = '';
                var actionTimestamp = null;
                
                if (isRejected && record.rejected_at) {
                    actionTimestamp = record.rejected_at;
                } else if (isApproved && record.approved_at) {
                    actionTimestamp = record.approved_at;
                } else if (isVerified && record.verified_at) {
                    actionTimestamp = record.verified_at;
                } else if (record.updated_at) {
                    // Fallback to updated_at if specific timestamp not available
                    actionTimestamp = record.updated_at;
                }
                
                // Format the action date in Philippine timezone
                if (actionTimestamp) {
                    actionDateStr = formatDate(actionTimestamp);
                }
                
                html += '<tr data-status="' + dataStatus + '" ' +
                        (dataArchiveStatus ? 'data-archive-status="' + dataArchiveStatus + '" ' : '') +
                        'data-record-id="' + record.id + '" ' +
                        'data-venue="' + (record.venue || '') + '" ' +
                        'data-organization="' + (record.organization || '') + '" ' +
                        'data-supervisor-name="' + (record.supervisor_name || '') + '" ' +
                        'data-action-date="' + actionDateStr + '" ' +
                        'data-rejection-reason="' + (record.rejection_reason || '') + '" ' +
                        'class="hover cursor-pointer" onclick="openDetailsModal(this)">' +
                        '<td class="text-center" style="min-width: 70px; width: 70px; white-space: nowrap;">' + (record.student_id || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 140px; width: 140px; white-space: nowrap;">' + (record.student_name || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 110px; width: 110px; white-space: nowrap;">' + (record.event_name || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 140px; width: 140px; white-space: nowrap;">' + 
                            '<div class="flex flex-col items-center">' +
                                '<span class="font-medium">' + (record.organization || 'â€”') + '</span>' +
                                '<span class="text-xs text-gray-500">' + (record.supervisor_name || 'â€”') + '</span>' +
                            '</div>' +
                        '</td>' +
                        '<td class="text-center" style="min-width: 50px; width: 50px; white-space: nowrap;">' + (record.hours_rendered || 0) + ' hours</td>' +
                        '<td class="text-center" style="min-width: 80px; width: 80px; white-space: nowrap;">' + dateStr + '</td>' +
                        '<td class="text-center" style="min-width: 180px; width: 180px;">';
                
                // Admin workflow:
                // - Pending tab: Show Verify/Reject buttons ONLY for Pending records
                // - Archived tab: Show status badge ONLY (Verified, Approved, Rejected) - NO action buttons
                if (isPending) {
                    // Only Pending records show action buttons
                    html += '<div class="flex justify-center items-center space-x-2">' +
                            '<button class="btn btn-action btn-action-verify" onclick="openVerifyModal(this,event)">Verify</button>' +
                            '<button class="btn btn-action btn-action-reject" onclick="openRejectModal(this,event)">Reject</button>' +
                            '</div>';
                } else if (isVerified) {
                    // Verified records show badge with date
                    html += '<div class="flex flex-col items-center gap-1">' +
                            '<span class="scms-badge scms-badge--verified">Verified</span>';
                    // Always show date if actionDateStr exists
                    if (actionDateStr) {
                        html += '<span class="text-xs text-gray-500">' + actionDateStr + '</span>';
                    }
                    html += '</div>';
                } else if (isApproved) {
                    // Approved records show badge with date
                    html += '<div class="flex flex-col items-center gap-1">' +
                            '<span class="scms-badge scms-badge--approved">Approved</span>';
                    // Always show date if actionDateStr exists
                    if (actionDateStr) {
                        html += '<span class="text-xs text-gray-500">' + actionDateStr + '</span>';
                    }
                    html += '</div>';
                } else if (isRejected) {
                    // Rejected records show badge with date
                    html += '<div class="flex flex-col items-center gap-1">' +
                            '<span class="scms-badge scms-badge--rejected">Rejected</span>';
                    // Always show date if actionDateStr exists
                    if (actionDateStr) {
                        html += '<span class="text-xs text-gray-500">' + actionDateStr + '</span>';
                    }
                    html += '</div>';
                }
                
                html += '</td></tr>';
            });
            
            tbody.innerHTML = html;
            
            // Apply current filter
            var activeTab = document.querySelector('.custom-tab-active');
            if (activeTab) {
                filterSubmissions(activeTab.textContent.trim(), activeTab);
            }
        }
        
        // Format date helper using Philippine timezone
        function formatDate(dateStr) {
            try {
                // Convert to Philippine timezone
                var date = getPhilippineDate(dateStr);
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                var year = date.getFullYear();
                // Return in MM-DD-YYYY format
                return month + '-' + day + '-' + year;
            } catch (e) {
                console.error('Date formatting error:', e);
                return dateStr;
            }
        }
        
        // Update monthly summary from loaded data using Philippine timezone
        function updateWeeklySummaryFromData(submissions) {
            var now = getPhilippineDate();
            var startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            var endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
            
            var pending = 0, accepted = 0, rejected = 0;
            
            submissions.forEach(function(record) {
                var recordDate = getPhilippineDate(record.updated_at || record.created_at);
                var isThisMonth = recordDate >= startOfMonth && recordDate <= endOfMonth;
                
                if (record.status === 'Pending') {
                    pending++;
                } else if (record.status === 'Verified' && isThisMonth) {
                    accepted++;
                } else if (record.status === 'Rejected' && isThisMonth) {
                    rejected++;
                }
            });
            
            document.getElementById('pending-requests-count').textContent = pending;
            document.getElementById('accepted-requests-count').textContent = accepted;
            document.getElementById('rejected-requests-count').textContent = rejected;
            
            // Update the donut chart
            if (pendingRequestsChartInstance) {
                var totalCapacity = 50;
                pendingRequestsChartInstance.data.datasets[0].data = [pending, Math.max(0, totalCapacity - pending)];
                pendingRequestsChartInstance.update();
                document.getElementById('pending-requests-label').textContent = pending;
            }
        }

        // Show status modal with filtered records
        window.showStatusModal = function(status) {
            var modal = document.getElementById('status_records_modal');
            var modalTitle = document.getElementById('status-modal-title');
            var modalSubtitle = document.getElementById('status-modal-subtitle');
            var modalIcon = document.getElementById('status-modal-icon');
            var tableBody = document.getElementById('status-modal-table-body');
            var emptyState = document.getElementById('status-modal-empty');
            var totalCount = document.getElementById('status-modal-total');
            var totalHours = document.getElementById('status-modal-hours');
            
            // Filter records by status
            // Pending: Show all pending (no date filter)
            // Verified/Rejected: Show only this month (resets at end of month)
            var now = getPhilippineDate();
            var startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1); // First day of current month
            var endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59); // Last day of current month
            
            var filteredRecords = allSubmissions.filter(function(r) {
                var recordDate = getPhilippineDate(r.updated_at || r.created_at);
                var isThisMonth = recordDate >= startOfMonth && recordDate <= endOfMonth;
                
                if (status === 'Pending') {
                    // Show ALL pending requests (no time restriction)
                    return r.status === 'Pending';
                } else if (status === 'Verified') {
                    // Show only verified requests from this month
                    return r.status === 'Verified' && isThisMonth;
                } else if (status === 'Rejected') {
                    // Show only rejected requests from this month
                    return r.status === 'Rejected' && isThisMonth;
                }
                return false;
            });
            
            // Update modal header based on status
            var iconSvg = '';
            var bgColorClass = '';
            
            if (status === 'Pending') {
                iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                bgColorClass = 'bg-gradient-pending';
                modalTitle.textContent = filteredRecords.length + ' Pending Requests';
                modalSubtitle.textContent = 'All pending social contract requests awaiting review';
            } else if (status === 'Verified') {
                iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
                bgColorClass = 'bg-gradient-accepted';
                modalTitle.textContent = filteredRecords.length + ' Verified Requests';
                modalSubtitle.textContent = 'All verified requests this month';
            } else if (status === 'Rejected') {
                iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
                bgColorClass = 'bg-gradient-rejected';
                modalTitle.textContent = filteredRecords.length + ' Rejected Requests';
                modalSubtitle.textContent = 'All rejected requests this month';
            }
            
            modalIcon.innerHTML = iconSvg;
            modalIcon.className = bgColorClass + ' p-3 rounded-full flex items-center justify-center';
            
            // Clear table body
            tableBody.innerHTML = '';
            
            // Calculate total hours
            var hours = filteredRecords.reduce(function(sum, r) {
                return sum + (parseInt(r.hours_rendered) || 0);
            }, 0);
            
            if (filteredRecords.length === 0) {
                // Show empty state with appropriate message
                var emptyText = document.getElementById('status-modal-empty-text');
                if (status === 'Pending') {
                    emptyText.textContent = 'There are no pending requests awaiting review.';
                } else if (status === 'Verified') {
                    emptyText.textContent = 'There are no verified requests this month.';
                } else if (status === 'Rejected') {
                    emptyText.textContent = 'There are no rejected requests this month.';
                }
                emptyState.classList.remove('hidden');
                tableBody.closest('.overflow-x-auto').classList.add('hidden');
            } else {
                // Hide empty state and show table
                emptyState.classList.add('hidden');
                tableBody.closest('.overflow-x-auto').classList.remove('hidden');
                
                // Populate table
                filteredRecords.forEach(function(rec) {
                    var row = document.createElement('tr');
                    var statusBadge = '';
                    
                    if (rec.status === 'Pending') {
                        statusBadge = '<span class="badge badge-warning text-white">Pending</span>';
                    } else if (rec.status === 'Verified') {
                        statusBadge = '<span class="badge badge-info text-white">Verified</span>';
                    } else if (rec.status === 'Rejected') {
                        statusBadge = '<span class="badge badge-error text-white">Rejected</span>';
                    } else if (rec.status === 'Approved') {
                        statusBadge = '<span class="badge badge-success text-white">Approved</span>';
                    }
                    
                    row.innerHTML = 
                        '<td class="text-center">' + (rec.student_id || '-') + '</td>' +
                        '<td class="text-center">' + (rec.student_name || '-') + '</td>' +
                        '<td class="text-center">' + formatDate(rec.date) + '</td>' +
                        '<td class="text-center">' + (rec.event_name || '-') + '</td>' +
                        '<td class="text-center">' + (rec.venue || '-') + '</td>' +
                        '<td class="text-center">' + (rec.hours_rendered || 0) + ' hours</td>' +
                        '<td class="text-center">' + statusBadge + '</td>';
                    tableBody.appendChild(row);
                });
            }
            
            // Update summary
            totalCount.textContent = filteredRecords.length;
            totalHours.textContent = hours + ' hours';
            
            // Show modal
            modal.showModal();
        };

        // Toast notification function
        function showToast(m, t) {
            t = t || 'success';
            try {
                var r = document.getElementById('toast-root');
                if (!r) return alert(m);
                
                var e = document.createElement('div');
                e.className = 'scms-toast scms-toast--' + t;
                e.setAttribute('role', 'status');
                e.setAttribute('aria-live', 'polite');
                e.style.pointerEvents = 'auto';
                e.innerHTML = '<span class="scms-toast__msg">' + ((m || '').replace(/</g, '&lt;')) + '</span>' +
                    '<button class="scms-toast__close" aria-label="Close">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                    '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>' +
                    '</svg></button>' +
                    '<div class="scms-toast__progress"></div>';
                
                var c = e.querySelector('.scms-toast__close');
                var p = e.querySelector('.scms-toast__progress');
                
                if (p) p.style.animation = 'scms-toast-progress 3500ms linear forwards';
                
                var rm = null;
                var remove = function() {
                    try { clearTimeout(rm); } catch(_) {}
                    e.style.opacity = '0';
                    e.style.transform = 'translateY(6px)';
                    setTimeout(function() { e.remove(); }, 180);
                };
                
                c.addEventListener('click', remove, {passive: true});
                r.appendChild(e);
                e.style.opacity = '0';
                e.style.transform = 'translateY(6px)';
                
                requestAnimationFrame(function() {
                    e.style.transition = 'opacity .18s ease, transform .18s ease';
                    e.style.opacity = '1';
                    e.style.transform = 'translateY(0)';
                });
                
                rm = setTimeout(remove, 3500);
            } catch(_) {
                try { alert(m); } catch {}
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility(i) {
            var inp = document.getElementById(i);
            if (inp) inp.type = inp.type === "password" ? "text" : "password";
        }

        // Show/hide pages
        function showPage(p) {
            document.querySelectorAll('aside a').forEach(function(a) {
                a.classList.remove('active-nav');
            });
            document.querySelectorAll('.page-content').forEach(function(pg) {
                pg.classList.add('hidden');
            });
            
            var np = document.getElementById(p + '-page');
            if (np) np.classList.remove('hidden');
            
            var nl = document.getElementById('nav-' + p);
            if (nl) nl.classList.add('active-nav');
            
            // Save current page to localStorage for admin
            try {
                localStorage.setItem('scms_admin_current_page', p);
            } catch(_) {}
            
            // Load submissions when showing submission page
            if (p === 'submission') {
                loadSubmissions();
                
                // Restore saved tab or default to pending
                setTimeout(function() {
                    var savedTab = 'pending';
                    try {
                        savedTab = localStorage.getItem('scms_admin_current_tab') || 'pending';
                    } catch(_) {}
                    
                    // Find the tab element based on saved tab
                    var tabs = document.querySelectorAll('.custom-tab');
                    var targetTab = null;
                    
                    tabs.forEach(function(tab) {
                        var tabText = tab.textContent.trim().toLowerCase();
                        if (tabText === savedTab || tabText === savedTab.replace('-', ' ')) {
                            targetTab = tab;
                        }
                    });
                    
                    // If no matching tab found, use first tab
                    if (!targetTab && tabs.length > 0) {
                        targetTab = tabs[0];
                    }
                    
                    if (targetTab) {
                        targetTab.classList.add('custom-tab-active');
                        filterSubmissions(savedTab, targetTab);
                    }
                }, 100);
            }
            
            // Load data management when showing data management page
            if (p === 'data-management') {
                loadDataManagement();
            }
        }

        // Global variable to track current status filter
        var currentStatusFilter = 'All';

        // Filter submissions by status
        function filterSubmissions(s, t) {
            document.querySelectorAll('.custom-tab').forEach(function(tb) {
                tb.classList.remove('custom-tab-active');
            });
            t.classList.add('custom-tab-active');
            
            // Save current tab for admin
            try {
                localStorage.setItem('scms_admin_current_tab', s);
            } catch(_) {}
            
            // Update header based on tab
            var actionLabel = document.getElementById('action-label');
            var statusWrapper = document.getElementById('status-header-wrapper');
            
            if (s === 'Archived') {
                actionLabel.classList.add('hidden');
                statusWrapper.classList.remove('hidden');
            } else {
                actionLabel.classList.remove('hidden');
                statusWrapper.classList.add('hidden');
            }
            
            // Reset status filter when switching tabs
            currentStatusFilter = 'All';
            
            var st = document.getElementById('search-input').value.toLowerCase();
            var rs = document.querySelectorAll('#submission-table-body tr');
            
            rs.forEach(function(r) {
                var rs = r.dataset.status;
                var id = r.cells[0].textContent.toLowerCase();
                var sn = r.cells[1].textContent.toLowerCase();
                var en = r.cells[2].textContent.toLowerCase();
                var sb = r.cells[3].textContent.toLowerCase();
                var hr = r.cells[4].textContent.toLowerCase();
                var dt = r.cells[5].textContent.toLowerCase();
                var ms = id.includes(st) || sn.includes(st) || en.includes(st) || 
                         sb.includes(st) || hr.includes(st) || dt.includes(st);
                
                if (rs === s && ms) {
                    r.classList.remove('hidden');
                } else {
                    r.classList.add('hidden');
                }
            });
        }

        // Filter table by status (for Archived tab)
        function filterTableByStatus(status, event) {
            if (event) event.preventDefault();
            
            currentStatusFilter = status;
            
            var st = document.getElementById('search-input').value.toLowerCase();
            var rs = document.querySelectorAll('#submission-table-body tr');
            
            rs.forEach(function(r) {
                var rowStatus = r.dataset.status;
                var archiveStatus = r.dataset.archiveStatus;
                
                // Only filter rows that are in the Archived tab
                if (rowStatus !== 'Archived') {
                    return;
                }
                
                var id = r.cells[0].textContent.toLowerCase();
                var sn = r.cells[1].textContent.toLowerCase();
                var en = r.cells[2].textContent.toLowerCase();
                var sb = r.cells[3].textContent.toLowerCase();
                var hr = r.cells[4].textContent.toLowerCase();
                var dt = r.cells[5].textContent.toLowerCase();
                var ms = id.includes(st) || sn.includes(st) || en.includes(st) || 
                         sb.includes(st) || hr.includes(st) || dt.includes(st);
                
                // Apply both status filter and search filter
                var statusMatch = status === 'All' || archiveStatus === status;
                
                if (statusMatch && ms) {
                    r.classList.remove('hidden');
                } else {
                    r.classList.add('hidden');
                }
            });
        }

        // Open verify modal
        function openVerifyModal(b, e) {
            e.stopPropagation();
            activeRow = b.closest('tr');
            document.getElementById('verify_modal').showModal();
        }

        // Open reject modal
        function openRejectModal(b, e) {
            e.stopPropagation();
            activeRow = b.closest('tr');
            // Clear previous rejection reason
            document.getElementById('reject-reason-textarea').value = '';
            document.getElementById('reject_modal').showModal();
        }

        // Open details modal
        function openDetailsModal(r) {
            activeRow = r;
            var s = r.dataset.status;
            var v = r.dataset.venue;
            var org = r.dataset.organization;
            var supervisorName = r.dataset.supervisorName || '';
            var en = r.cells[2].textContent;
            var dt = r.cells[5].textContent;
            var hr = r.cells[4].textContent;
            var actionDate = r.dataset.actionDate || ''; // Get the action date
            
            // Debug: Log the action date and supervisor name
            console.log('Modal opened - Status:', s, 'Archive Status:', r.dataset.archiveStatus, 'Action Date:', actionDate, 'Supervisor:', supervisorName, 'Organization:', org);
            
            document.getElementById('details-event-name').value = en;
            document.getElementById('details-supervisor-name').value = supervisorName;
            document.getElementById('details-venue').value = v;
            document.getElementById('details-date').value = dt;
            document.getElementById('details-hours-rendered').value = hr;
            document.getElementById('details-organizing-committee').value = org;
            
            var ss = document.getElementById('details-status-section');
            var ab = document.getElementById('details-action-buttons');
            var sb = document.getElementById('details-status-badge');
            var ad = document.getElementById('details-action-date');
            var reasonContainer = document.getElementById('details-reason-container');
            var reasonText = document.getElementById('details-reason-text');
            
            sb.innerHTML = '';
            ab.innerHTML = '';
            ad.textContent = ''; // Clear action date
            reasonContainer.classList.add('hidden'); // Hide rejection reason by default
            reasonText.textContent = '';
            
            if (s === 'Pending') {
                ss.classList.add('hidden');
                ab.classList.remove('hidden');
                ab.innerHTML = '<button class="btn btn-action btn-action-verify flex-1" onclick="handleDetailsVerify()">Verify</button>' +
                               '<button class="btn btn-action btn-action-reject flex-1" onclick="handleDetailsReject()">Reject</button>';
            } else {
                ss.classList.remove('hidden');
                ab.classList.add('hidden');
                var as = r.dataset.archiveStatus;
                
                // Add the status badge
                if (as === 'Verified') {
                    sb.innerHTML = '<span class="status-badge verified">Verified</span>';
                } else if (as === 'Approved') {
                    sb.innerHTML = '<span class="status-badge approved">Approved</span>';
                } else if (as === 'Rejected') {
                    sb.innerHTML = '<span class="status-badge rejected">Rejected</span>';
                    
                    // Show rejection reason if available
                    var rejectionReason = r.dataset.rejectionReason || '';
                    if (rejectionReason) {
                        reasonContainer.classList.remove('hidden');
                        reasonText.textContent = rejectionReason;
                    }
                }
                
                // Add the action date on the right
                if (actionDate) {
                    ad.textContent = actionDate;
                }
            }
            
            document.getElementById('submission_details_modal').showModal();
        }

        // Handle verify from details modal
        function handleDetailsVerify() {
            document.getElementById('submission_details_modal').close();
            document.getElementById('verify_modal').showModal();
        }

        // Handle reject from details modal
        function handleDetailsReject() {
            document.getElementById('submission_details_modal').close();
            document.getElementById('reject_modal').showModal();
        }

        // Initialize theme toggle
        function initThemeToggle() {
            try {
                var tg = document.getElementById('theme-toggle');
                var lb = document.getElementById('theme-label');
                var ap = function(m) {
                    document.documentElement.setAttribute('data-theme', m);
                    try {
                        localStorage.setItem('scms_admin_theme', m);
                    } catch(_) {}
                    if (lb) lb.textContent = (m === 'dark') ? 'Dark theme' : 'Light theme';
                    if (tg) tg.checked = (m === 'dark');
                };
                
                var sv = 'light';
                try {
                    sv = (localStorage.getItem('scms_admin_theme') === 'dark') ? 'dark' : 'light';
                } catch(_) {}
                
                ap(sv);
                
                if (tg) {
                    tg.addEventListener('change', function() {
                        ap(tg.checked ? 'dark' : 'light');
                        // Reinitialize charts with new theme colors
                        if (typeof initYearlyCharts === 'function') {
                            initYearlyCharts();
                            // Re-update with current data if available
                            if (typeof submissions !== 'undefined' && submissions) {
                                updateYearlyCharts(submissions);
                            }
                        }
                        // Regenerate activity calendar with new theme colors
                        if (typeof generateActivityCalendar === 'function') {
                            generateActivityCalendar();
                        }
                    });
                }
            } catch(_) {}
        }

        // Attach logout handler
        function attachLogoutHandler() {
            try {
                var f = document.getElementById('logout-form-visible');
                var b = document.getElementById('logout-button-visible');
                if (!f || !b) return;
                
                var cm = document.querySelector('meta[name="csrf-token"]');
                var cs = cm ? cm.getAttribute('content') : '';
                
                b.replaceWith(b.cloneNode(true));
                var fb = document.getElementById('logout-button-visible');
                if (!fb) return;
                
                fb.addEventListener('click', async function(e) {
                    e.preventDefault();
                    try {
                        // Ensure CSRF cookie exists
                        await ensureCsrfCookie();
                        
                        await fetch(f.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': cs,
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({'_token': cs}).toString(),
                            keepalive: true
                        });
                    } catch(err) {
                        console.error('Logout error:', err);
                    } finally {
                        // Always redirect to login page
                        try {
                            window.location.replace(<?php echo json_encode(route('admin.login'), 15, 512) ?>);
                        } catch(_) {
                            window.location.href = <?php echo json_encode(route('admin.login'), 15, 512) ?>;
                        }
                    }
                }, {passive: true});
            } catch(_) {}
        }

        // DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Restore saved page for admin, default to dashboard
            var savedPage = 'dashboard';
            try {
                savedPage = localStorage.getItem('scms_admin_current_page') || 'dashboard';
            } catch(_) {}
            
            showPage(savedPage);
            initThemeToggle();
            attachLogoutHandler();
            initYearlyCharts();
            loadDashboardStats(); // Load dashboard statistics
            generateActivityCalendar(); // Load activity calendar with API data
            loadSubmissions(); // Load initial submissions data
            initPendingRequestsChart();
            
            // Add resize listener to scroll calendar to right
            window.addEventListener('resize', function() {
                scrollCalendarToRight();
            });
            
            // Helper function to reset all indicators (double arrow for inactive)
            function resetAllSortIndicators() {
                document.getElementById('hours-sort-indicator').textContent = 'â‡…';
                document.getElementById('studentid-sort-indicator').textContent = 'â‡…';
                document.getElementById('date-sort-indicator').textContent = 'â‡…';
                document.getElementById('studentname-sort-indicator').textContent = 'â‡…';
                document.getElementById('eventname-sort-indicator').textContent = 'â‡…';
                document.getElementById('organization-sort-indicator').textContent = 'â‡…';
                var statusIndicator = document.getElementById('status-sort-indicator');
                if (statusIndicator) statusIndicator.textContent = 'â‡…';
            }
            
            // Hours sort toggle event listener
            var hoursSortToggle = document.getElementById('hours-sort-toggle');
            var hoursSortIndicator = document.getElementById('hours-sort-indicator');
            if (hoursSortToggle && hoursSortIndicator) {
                hoursSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'hours';
                    hoursSortDirection = hoursSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    hoursSortIndicator.textContent = hoursSortDirection === 'asc' ? 'â†‘' : 'â†“';
                    renderSubmissions(allSubmissions, 'hours');
                });
            }
            
            // Student ID sort toggle event listener
            var studentIdSortToggle = document.getElementById('studentid-sort-toggle');
            var studentIdSortIndicator = document.getElementById('studentid-sort-indicator');
            if (studentIdSortToggle && studentIdSortIndicator) {
                studentIdSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'studentid';
                    studentIdSortDirection = studentIdSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    studentIdSortIndicator.textContent = studentIdSortDirection === 'asc' ? 'â†‘' : 'â†“';
                    renderSubmissions(allSubmissions, 'studentid');
                });
            }
            
            // Date sort toggle event listener
            var dateSortToggle = document.getElementById('date-sort-toggle');
            var dateSortIndicator = document.getElementById('date-sort-indicator');
            if (dateSortToggle && dateSortIndicator) {
                dateSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'date';
                    dateSortDirection = dateSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    dateSortIndicator.textContent = dateSortDirection === 'asc' ? 'â†‘' : 'â†“';
                    renderSubmissions(allSubmissions, 'date');
                });
            }
            
            // Student Name sort toggle event listener
            var studentNameSortToggle = document.getElementById('studentname-sort-toggle');
            var studentNameSortIndicator = document.getElementById('studentname-sort-indicator');
            if (studentNameSortToggle && studentNameSortIndicator) {
                studentNameSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'studentname';
                    studentNameSortDirection = studentNameSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    studentNameSortIndicator.textContent = studentNameSortDirection === 'asc' ? 'â†‘' : 'â†“';
                    renderSubmissions(allSubmissions, 'studentname');
                });
            }
            
            // Event Name sort toggle event listener
            var eventNameSortToggle = document.getElementById('eventname-sort-toggle');
            var eventNameSortIndicator = document.getElementById('eventname-sort-indicator');
            if (eventNameSortToggle && eventNameSortIndicator) {
                eventNameSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'eventname';
                    eventNameSortDirection = eventNameSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    eventNameSortIndicator.textContent = eventNameSortDirection === 'asc' ? 'â†‘' : 'â†“';
                    renderSubmissions(allSubmissions, 'eventname');
                });
            }
            
            // Organization sort toggle event listener
            var organizationSortToggle = document.getElementById('organization-sort-toggle');
            var organizationSortIndicator = document.getElementById('organization-sort-indicator');
            if (organizationSortToggle && organizationSortIndicator) {
                organizationSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'organization';
                    organizationSortDirection = organizationSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    organizationSortIndicator.textContent = organizationSortDirection === 'asc' ? 'â†‘' : 'â†“';
                    renderSubmissions(allSubmissions, 'organization');
                });
            }
            
            // Status sort toggle event listener (for Archived tab)
            var statusSortToggle = document.getElementById('status-sort-toggle');
            var statusSortIndicator = document.getElementById('status-sort-indicator');
            if (statusSortToggle && statusSortIndicator) {
                statusSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'status';
                    statusSortDirection = statusSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    statusSortIndicator.textContent = statusSortDirection === 'asc' ? 'â†‘' : 'â†“';
                    renderSubmissions(allSubmissions, 'status');
                });
            }
            
            // Auto-refresh removed - use manual refresh buttons instead
            
            // Fix dropdown positioning
            var dropdownBtn = document.querySelector('#status-filter-dropdown [role="button"]');
            if (dropdownBtn) {
                dropdownBtn.addEventListener('click', function() {
                    setTimeout(function() {
                        var dropdown = document.querySelector('#status-filter-dropdown .dropdown-content');
                        if (dropdown) {
                            var btnRect = dropdownBtn.getBoundingClientRect();
                            dropdown.style.position = 'fixed';
                            dropdown.style.left = (btnRect.left - 100) + 'px';
                            dropdown.style.top = (btnRect.bottom + 5) + 'px';
                        }
                    }, 10);
                });
            }
            
            // Confirm verify button handler
            document.getElementById('confirm-verify-btn').addEventListener('click', async function() {
                if (activeRow) {
                    var recordId = activeRow.dataset.recordId;
                    
                    try {
                        // Make API call to verify the submission
                        const response = await fetch(`${BASE_PATH}/admin/api/submissions/${recordId}/verify`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });
                        
                        // Check if response is HTML (redirect to login) instead of JSON
                        const contentType = response.headers.get('content-type') || '';
                        if (!contentType.includes('application/json')) {
                            console.warn('Non-JSON response received, session may have expired');
                            // Session expired, silently ignore
                            return;
                        }
                        
                        const data = await response.json();
                        
                        // Check for authentication error
                        if (data.unauthenticated || response.status === 401 || response.status === 419) {
                            console.warn('Session expired, silently ignoring verify');
                            return;
                        }
                        
                        if (data.success) {
                            showToast('Submission has been verified successfully.', 'success');
                            document.getElementById('verify_modal').close();
                            activeRow = null;
                            
                            // Reload submissions to get fresh data from database
                            loadSubmissions();
                            generateActivityCalendar(); // Refresh calendar to show new activity
                            loadDashboardStats(); // Refresh dashboard statistics
                        } else {
                            showToast(data.message || 'Failed to verify submission.', 'error');
                        }
                    } catch (error) {
                        console.error('Error verifying submission:', error);
                        // Only show error if it's not an authentication/parsing issue
                        if (!error.message.includes('Unexpected token') && 
                            !error.message.includes('JSON') && 
                            !error.message.includes('Session')) {
                            showToast('Failed to verify submission. Please try again.', 'error');
                        }
                    }
                }
            });
            
            // Reset reject modal function
            function resetRejectModal() {
                var rejectionReasonSelect = document.getElementById('rejection-reason-select');
                var otherReasonLabel = document.getElementById('other-reason-label');
                var rejectionReasonTextarea = document.getElementById('reject-reason-textarea');
                
                rejectionReasonSelect.value = "";
                otherReasonLabel.classList.add('hidden');
                rejectionReasonTextarea.value = '';
                rejectionReasonSelect.classList.remove('border-red-500');
                rejectionReasonTextarea.classList.remove('border-red-500');
            }
            
            // Make resetRejectModal globally accessible
            window.resetRejectModal = resetRejectModal;
            
            // Rejection reason dropdown change handler
            var rejectionReasonSelect = document.getElementById('rejection-reason-select');
            var otherReasonLabel = document.getElementById('other-reason-label');
            var rejectionReasonTextarea = document.getElementById('reject-reason-textarea');
            
            if (rejectionReasonSelect) {
                rejectionReasonSelect.addEventListener('change', function() {
                    if (this.value === 'Others') {
                        otherReasonLabel.classList.remove('hidden');
                    } else {
                        otherReasonLabel.classList.add('hidden');
                        rejectionReasonTextarea.value = '';
                    }
                });
            }
            
            // Confirm reject button handler
            document.getElementById('confirm-reject-btn').addEventListener('click', async function() {
                if (activeRow) {
                    var recordId = activeRow.dataset.recordId;
                    var rejectionReasonSelect = document.getElementById('rejection-reason-select');
                    var rejectionReasonTextarea = document.getElementById('reject-reason-textarea');
                    var selectedReason = rejectionReasonSelect.value;
                    var reason = '';
                    
                    // Validate that a reason is selected
                    if (!selectedReason) {
                        showToast('Please select a rejection reason.', 'error');
                        rejectionReasonSelect.classList.add('border-red-500');
                        rejectionReasonSelect.focus();
                        return;
                    }
                    
                    // If "Others" is selected, validate the textarea
                    if (selectedReason === 'Others') {
                        var otherReason = rejectionReasonTextarea.value.trim();
                        if (!otherReason) {
                            showToast('Please specify the reason for rejection.', 'error');
                            rejectionReasonTextarea.classList.add('border-red-500');
                            rejectionReasonTextarea.focus();
                            return;
                        }
                        reason = otherReason;
                    } else {
                        // Use the predefined reason
                        reason = selectedReason;
                    }
                    
                    try {
                        // Disable button to prevent double submission
                        this.disabled = true;
                        this.textContent = 'Rejecting...';
                        
                        // Make API call to reject the submission with reason
                        const response = await fetch(`${BASE_PATH}/admin/api/submissions/${recordId}/reject`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ reason: reason })
                        });
                        
                        // Check if response is HTML (redirect to login) instead of JSON
                        const contentType = response.headers.get('content-type') || '';
                        if (!contentType.includes('application/json')) {
                            console.warn('Non-JSON response received, session may have expired');
                            // Session expired, silently ignore
                            return;
                        }
                        
                        const data = await response.json();
                        
                        // Check for authentication error
                        if (data.unauthenticated || response.status === 401 || response.status === 419) {
                            console.warn('Session expired, silently ignoring reject');
                            return;
                        }
                        
                        if (data.success) {
                            showToast('Submission has been rejected.', 'success');
                            document.getElementById('reject_modal').close();
                            resetRejectModal(); // Clear the form
                            activeRow = null;
                            
                            // Reload submissions to get fresh data from database
                            loadSubmissions();
                            generateActivityCalendar(); // Refresh calendar to show new activity
                            loadDashboardStats(); // Refresh dashboard statistics
                        } else {
                            showToast(data.message || 'Failed to reject submission.', 'error');
                        }
                    } catch (error) {
                        console.error('Error rejecting submission:', error);
                        // Only show error if it's not an authentication/parsing issue
                        if (!error.message.includes('Unexpected token') && 
                            !error.message.includes('JSON') && 
                            !error.message.includes('Session')) {
                            showToast('Failed to reject submission. Please try again.', 'error');
                        }
                    } finally {
                        // Re-enable button and restore text
                        this.disabled = false;
                        this.textContent = 'Yes, reject';
                    }
                }
            });
            
            // Search input handler
            document.getElementById('search-input').addEventListener('keyup', function() {
                var at = document.querySelector('.custom-tab-active');
                var as = at.textContent.trim();
                
                // If on Archived tab and there's a status filter active, use filterTableByStatus
                if (as === 'Archived' && currentStatusFilter !== 'All') {
                    filterTableByStatus(currentStatusFilter, null);
                } else {
                    filterSubmissions(as, at);
                }
            });
            
            // Name change handler
            var snb = document.getElementById('save-name-button');
            if (snb) {
                snb.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    // Re-query the input field each time button is clicked to ensure we get current value
                    var nameInput = document.getElementById('admin-name-input');
                    
                    if (!nameInput) {
                        console.error('admin-name input not found');
                        showToast('Name input field not found.', 'error');
                        return;
                    }
                    
                    // Get the actual current value from the input using getAttribute as fallback
                    var inputValue = nameInput.value || nameInput.getAttribute('value') || '';
                    console.log('Input element:', nameInput);
                    console.log('Raw input value:', inputValue, 'Type:', typeof inputValue);
                    console.log('Input value property:', nameInput.value);
                    console.log('Input getAttribute:', nameInput.getAttribute('value'));
                    
                    if (!inputValue || inputValue.trim() === '') {
                        showToast('Please enter a name in the field.', 'error');
                        return;
                    }
                    
                    var newName = String(inputValue).trim();
                    console.log('Original value:', nameInput.value);
                    console.log('Trimmed value:', newName);
                    console.log('Lowercase:', newName.toLowerCase());
                    console.log('Starts with admin?:', newName.toLowerCase().startsWith('admin'));
                
                    // Ensure name starts with "admin" (case-insensitive check)
                    if (!newName.toLowerCase().startsWith('admin')) {
                        console.log('Failed: does not start with admin');
                        showToast('Admin name must start with "admin".', 'error');
                        return;
                    }
                    
                    // Get the part after "admin" (preserve original input)
                    var nameSuffix = newName.substring(5);
                    console.log('Name suffix:', nameSuffix);
                    console.log('Suffix length:', nameSuffix.length);
                    
                    if (nameSuffix.length === 0) {
                        console.log('Failed: suffix is empty');
                        showToast('Please add a name after "admin" (e.g., adminJohn).', 'error');
                        return;
                    }
                    
                    // Keep the name as entered (but ensure it starts with lowercase "admin")
                    newName = 'admin' + nameSuffix;
                    console.log('Final name to send:', newName);
                
                    try { 
                        snb.disabled = true;
                        snb.textContent = 'Updating...';
                        
                        var response = await fetch(`${BASE_PATH}/admin/api/settings/update-name`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ name: newName })
                        });
                        
                        var data = await response.json();
                        
                        if (response.ok && data.success) {
                            showToast(data.message || 'Name updated successfully!', 'success');
                            // Update the name in the sidebar
                            location.reload();
                        } else {
                            showToast(data.message || 'Failed to update name.', 'error');
                        }
                    } catch (error) {
                        console.error('Error updating name:', error);
                        showToast('Failed to update name. Please try again.', 'error');
                    } finally {
                        snb.disabled = false;
                        snb.textContent = 'Update Name';
                    }
                });
            }
            
            // Password change handler with email verification
            var spb = document.getElementById('save-password-button');
            var pcf = document.getElementById('password-change-form');
            
            if (spb && pcf) {
                spb.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    if (!pcf.checkValidity()) {
                        pcf.reportValidity();
                        return;
                    }
                
                var currentPassword = document.getElementById('current-password').value;
                var newPassword = document.getElementById('new-password').value;
                var confirmPassword = document.getElementById('confirm-password').value;
                
                if (newPassword !== confirmPassword) {
                    showToast('New password and confirm password do not match.', 'error');
                    return;
                }
                
                if (newPassword.length < 8) {
                    showToast('New password must be at least 8 characters long.', 'error');
                    return;
                }
                
                try {
                    spb.disabled = true;
                    spb.textContent = 'Sending...';
                    
                    var response = await fetch(`${BASE_PATH}/admin/api/settings/request-password-change`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            current_password: currentPassword,
                            new_password: newPassword,
                            new_password_confirmation: confirmPassword
                        })
                    });
                    
                    var data = await response.json();
                    
                    if (response.ok && data.success) {
                        showToast(data.message, 'success');
                        pcf.reset();
                    } else {
                        showToast(data.message || 'Failed to process password change request.', 'error');
                    }
                } catch (error) {
                    console.error('Error requesting password change:', error);
                    showToast('Failed to process password change request. Please try again.', 'error');
                } finally {
                    spb.disabled = false;
                    spb.textContent = 'Request Password Change';
                }
                });
            }
            
            // Auto-refresh removed - use manual refresh buttons instead
        });

        // Calendar year management using Philippine timezone
        var currentCalendarYear = getPhilippineDate().getFullYear();
        var activityDataCache = {}; // Cache for activity data by year

        // Generate Activity Calendar (GitHub-style) - Year-based (Jan-Dec)
        function generateActivityCalendar() {
            var container = document.getElementById('activity-calendar');
            if (!container) return;
            
            // Update year display
            var yearDisplay = document.getElementById('calendar-year');
            if (yearDisplay) {
                yearDisplay.textContent = currentCalendarYear;
            }
            
            // Disable next button if viewing current year
            var currentYear = getPhilippineDate().getFullYear();
            var nextBtn = document.getElementById('next-year-btn');
            if (nextBtn) {
                nextBtn.disabled = (currentCalendarYear >= currentYear);
                if (nextBtn.disabled) {
                    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
            
            // Load activity data for the year
            loadActivityDataForYear(currentCalendarYear, function(activityData) {
                var startDate = new Date(currentCalendarYear, 0, 1); // Jan 1
                var endDate = new Date(currentCalendarYear, 11, 31); // Dec 31
                var today = new Date();
                
                renderCalendar(startDate, endDate, today, activityData);
            });
        }

        // Load activity data from API for a specific year
        function loadActivityDataForYear(year, callback) {
            // Check cache first
            if (activityDataCache[year]) {
                callback(activityDataCache[year]);
                return;
            }
            
            // Show loading state
            var container = document.getElementById('activity-calendar');
            if (container) {
                container.innerHTML = '<div class="text-center text-text-muted py-8">Loading calendar...</div>';
            }
            
            // Fetch from API
            fetch(`${BASE_PATH}/admin/api/activity-calendar?year=` + year, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                console.log('Activity calendar response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status + ' ' + response.statusText);
                }
                return response.json();
            })
            .then(function(result) {
                console.log('Activity calendar result:', result);
                if (result.success) {
                    var activityData = result.data || {};
                    activityDataCache[year] = activityData; // Cache the data
                    callback(activityData);
                } else {
                    throw new Error(result.message || 'Failed to load activity data');
                }
            })
            .catch(function(error) {
                console.error('Failed to load activity calendar:', error);
                if (container) {
                    container.innerHTML = '<div class="text-center text-error py-8">Failed to load calendar: ' + error.message + '</div>';
                }
            });
        }

        // Render the calendar grid
        function renderCalendar(startDate, endDate, today, activityData) {
            var container = document.getElementById('activity-calendar');
            if (!container) return;
            
            // Get color based on activity level (purple palette)
            function getColor(level) {
                // Detect dark theme
                const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
                
                if (isDarkTheme) {
                    // Dark theme - purple palette (5 levels: 0-4)
                    if (level === 0) return '#2c354aff'; // No activity
                    if (level === 1) return '#411b7a'; // 1-2 updates
                    if (level === 2) return '#5A3590'; // 3-4 updates
                    if (level === 3) return '#804ED6'; // 5-6 updates
                    return '#A770FF'; // 7+ updates (level 4)
                } else {
                    // Light theme - original colors (5 levels: 0-4)
                    if (level === 0) return '#E5E7EB'; // No activity
                    if (level === 1) return '#E5D4FF'; // 1-2 updates
                    if (level === 2) return '#C9A9FF'; // 3-4 updates
                    if (level === 3) return '#A475FF'; // 5-6 updates
                    return '#6D28D9'; // 7+ updates (level 4)
                }
            }
            
            // Use Philippines timezone (UTC+8)
            var philippinesOffset = 8 * 60; // 8 hours in minutes
            var localOffset = today.getTimezoneOffset();
            var offsetDiff = philippinesOffset + localOffset;
            var philippinesToday = new Date(today.getTime() + (offsetDiff * 60 * 1000));
            
            var html = '<div class="flex gap-2">';
            
            // Day labels column - use rem values to match Tailwind exactly
            // h-3 = 0.75rem (12px), mb-1 = 0.25rem (4px)
            html += '<div class="flex flex-col text-xs text-text-muted pr-2" style="padding-top: 18px;">';
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem;"></div>'; // Sunday - Row 0
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem; line-height: 0.75rem;">Mon</div>'; // Monday - Row 1
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem;"></div>'; // Tuesday - Row 2
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem; line-height: 0.75rem;">Wed</div>'; // Wednesday - Row 3
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem;"></div>'; // Thursday - Row 4
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem; line-height: 0.75rem;">Fri</div>'; // Friday - Row 5
            html += '<div style="height: 0.75rem;"></div>'; // Saturday - Row 6
            html += '</div>';
            
            // Calendar grid container
            html += '<div class="flex-1 overflow-x-auto">';
            html += '<div class="flex gap-4">'; // Gap between months
            
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Loop through each month of the year
            for (var monthIndex = 0; monthIndex < 12; monthIndex++) {
                // Skip future months using Philippines time
                var currentMonth = philippinesToday.getMonth();
                if (monthIndex > currentMonth) {
                    continue; // Skip this month
                }
                
                // Build calendar dates
                var monthStart = new Date(currentCalendarYear, monthIndex, 1);
                var monthEnd = new Date(currentCalendarYear, monthIndex + 1, 0); // Last day of month
                
                // Find the Sunday before or on the first day of the month
                var firstSunday = new Date(monthStart);
                firstSunday.setDate(firstSunday.getDate() - firstSunday.getDay());
                
                // Build weeks for this month
                var monthWeeks = [];
                var currentWeekStart = new Date(firstSunday);
                
                // Continue until we've covered the entire month
                while (currentWeekStart <= monthEnd) {
                    var week = [];
                    for (var dayOffset = 0; dayOffset < 7; dayOffset++) {
                        var dayDate = new Date(currentWeekStart.getFullYear(), currentWeekStart.getMonth(), currentWeekStart.getDate() + dayOffset);
                        week.push(dayDate);
                    }
                    monthWeeks.push(week);
                    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
                    
                    // Stop if the week is entirely in the next month
                    if (week[0].getMonth() > monthIndex && week[6].getMonth() > monthIndex) {
                        break;
                    }
                }
                
                // Render this month
                html += '<div class="inline-flex flex-col">';
                
                // Month label
                html += '<div class="text-xs font-semibold text-text-muted text-center mb-2 h-4">' + months[monthIndex] + '</div>';
                
                // Grid rows for this month
                for (var dayIndex = 0; dayIndex < 7; dayIndex++) {
                    // Remove mb-1 from last row (Saturday) to match day labels
                    var rowClass = dayIndex === 6 ? 'flex gap-1' : 'flex gap-1 mb-1';
                    html += '<div class="' + rowClass + '">';
                    
                    for (var w = 0; w < monthWeeks.length; w++) {
                        var date = monthWeeks[w][dayIndex];
                        
                        // Create date string for data lookup
                        var year = date.getFullYear();
                        var month = String(date.getMonth() + 1).padStart(2, '0');
                        var day = String(date.getDate()).padStart(2, '0');
                        var dateStr = year + '-' + month + '-' + day;
                        
                        var level = activityData[dateStr] || 0;
                        
                        // Check if this date is today in Philippines timezone
                        var isToday = date.getFullYear() === philippinesToday.getFullYear() && 
                                     date.getMonth() === philippinesToday.getMonth() && 
                                     date.getDate() === philippinesToday.getDate();
                        var isFuture = date > philippinesToday;
                        var isInYear = date.getFullYear() === currentCalendarYear;
                        var isInThisMonth = date.getMonth() === monthIndex;
                        
                        // Set color - use gray for future dates or dates not in this month, otherwise use activity color
                        var color;
                        if (isFuture || !isInYear || !isInThisMonth) {
                            // Use theme-aware gray color for future/out of range dates
                            const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
                             color = isDarkTheme ? '#303d54ff' : '#E5E7EB';
                        } else {
                            color = getColor(level);
                        }
                        
                        var title = dateStr + ': ' + level + ' update' + (level !== 1 ? 's' : '');
                        
                        var borderClass = isToday ? 'ring-2 ring-primary-purple ring-offset-1' : '';
                        var opacity = (isFuture || !isInYear || !isInThisMonth) ? 'opacity-30' : '';
                        var cursor = (!isFuture && isInYear && isInThisMonth && level > 0) ? 'cursor-pointer' : 'cursor-default';
                        
                        html += '<div class="w-3 h-3 rounded-sm transition-all hover:ring-2 hover:ring-primary-purple hover:ring-offset-1 ' + 
                                cursor + ' ' + borderClass + ' ' + opacity + '" ' +
                                'style="background-color: ' + color + ';" ' +
                                'title="' + title + '" ' +
                                'data-date="' + dateStr + '" ' +
                                'data-count="' + level + '" ' +
                                ((!isFuture && isInYear && isInThisMonth && level > 0) ? 'onclick="showActivityDetails(\'' + dateStr + '\')"' : '') +
                                '></div>';
                    }
                    
                    html += '</div>';
                }
                
                html += '</div>'; // End month column
            }
            
            html += '</div>'; // End months flex container
            html += '</div>'; // End overflow container
            html += '</div>'; // End main flex container
            container.innerHTML = html;
            
            // Scroll to the right after rendering
            scrollCalendarToRight();
        }
        
        // Scroll calendar to the rightmost position
        function scrollCalendarToRight() {
            var container = document.getElementById('activity-calendar');
            if (container) {
                // Find the inner overflow container
                var innerContainer = container.querySelector('.overflow-x-auto');
                var targetContainer = innerContainer || container;
                
                // Use setTimeout to ensure DOM is fully rendered
                setTimeout(function() {
                    targetContainer.scrollLeft = targetContainer.scrollWidth;
                }, 100);
            }
        }

        // Change calendar year
        function changeCalendarYear(delta) {
            var newYear = currentCalendarYear + delta;
            var currentYear = new Date().getFullYear();
            
            // Don't allow future years
            if (newYear > currentYear) {
                return;
            }
            
            currentCalendarYear = newYear;
            generateActivityCalendar();
        }

        // Show activity details for a specific date
        function showActivityDetails(dateStr) {
            var modal = document.getElementById('activity_details_modal');
            var dateHeader = document.getElementById('activity-date-header');
            var content = document.getElementById('activity-details-content');
            var loading = document.getElementById('activity-loading');
            var noData = document.getElementById('activity-no-data');
            
            if (!modal || !dateHeader || !content || !loading || !noData) return;
            
            // Format date for display
            var date = new Date(dateStr + 'T00:00:00');
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateHeader.textContent = date.toLocaleDateString('en-US', options);
            
            // Reset states
            content.innerHTML = '';
            content.classList.add('hidden');
            loading.classList.remove('hidden');
            noData.classList.add('hidden');
            
            // Open modal
            modal.showModal();
            
            // Fetch activity details
            fetch(`${BASE_PATH}/admin/api/activity-details?date=` + dateStr, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(result) {
                loading.classList.add('hidden');
                
                if (result.success && result.data && result.data.length > 0) {
                    content.classList.remove('hidden');
                    var html = '';
                    
                    result.data.forEach(function(activity) {
                        var time = new Date(activity.created_at);
                        var timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
                        var timeStr = time.toLocaleTimeString('en-US', timeOptions);
                        
                        // Badge with proper colors (verified = blue, rejected = red)
                        var actionBadge = '';
                        var actionText = '';
                        if (activity.action === 'verified' || activity.action === 'verified_submission') {
                            actionBadge = '<span class="badge badge-sm badge-info text-white">Verified</span>';
                            actionText = 'Verified';
                        } else if (activity.action === 'rejected' || activity.action === 'rejected_submission') {
                            actionBadge = '<span class="badge badge-sm badge-error text-white">Rejected</span>';
                            actionText = 'Rejected';
                        }
                        
                        html += '<div class="bg-base-100 rounded-lg p-4 border border-base-300 hover:shadow-md transition-shadow">';
                        html += '<div class="flex items-center justify-between mb-2">';
                        html += '<span class="text-xs font-medium text-text-muted">' + timeStr + '</span>';
                        html += '<div class="flex flex-col items-end gap-1">';
                        html += actionBadge;
                        if (activity.admin_name) {
                            html += '<span class="text-xs text-text-muted">' + actionText + ' by Admin ' + activity.admin_name + '</span>';
                        }
                        html += '</div>';
                        html += '</div>';
                        
                        html += '<p class="text-sm font-semibold text-text-header mb-2">' + (activity.description || 'Activity recorded') + '</p>';
                        
                        if (activity.student_id || activity.student_name) {
                            html += '<div class="flex items-center gap-2 text-sm text-text-muted">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
                            html += '</svg>';
                            html += '<span>';
                            if (activity.student_id) {
                                html += '<span class="font-medium">' + activity.student_id + '</span>';
                            }
                            if (activity.student_name) {
                                html += (activity.student_id ? ' - ' : '') + activity.student_name;
                            }
                            html += '</span>';
                            html += '</div>';
                        }
                        
                        if (activity.venue) {
                            html += '<div class="flex items-center gap-2 text-sm text-text-muted mt-1">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />';
                            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
                            html += '</svg>';
                            html += '<span>' + activity.venue + '</span>';
                            html += '</div>';
                        }
                        
                        html += '</div>';
                    });
                    
                    content.innerHTML = html;
                } else {
                    noData.classList.remove('hidden');
                }
            })
            .catch(function(error) {
                console.error('Failed to load activity details:', error);
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            });
        }

        // Generate sample activity data (will be replaced with API call)
        function generateSampleActivityData(startDate, endDate) {
            var data = {};
            var current = new Date(startDate);
            
            while (current <= endDate) {
                var dateStr = current.toISOString().split('T')[0];
                // Random activity level (0-4), with higher probability for lower values
                var random = Math.random();
                if (random < 0.3) {
                    data[dateStr] = 0;
                } else if (random < 0.6) {
                    data[dateStr] = 1;
                } else if (random < 0.8) {
                    data[dateStr] = 2;
                } else if (random < 0.95) {
                    data[dateStr] = 3;
                } else {
                    data[dateStr] = 4;
                }
                current.setDate(current.getDate() + 1);
            }
            
            return data;
        }

        // Initialize Pending Requests Donut Chart
        var pendingRequestsChartInstance = null;
        function initPendingRequestsChart(pendingCount) {
            var canvas = document.getElementById('pendingRequestsChart');
            if (!canvas) return;
            
            var ctx = canvas.getContext('2d');
            
            // Destroy existing chart if any
            if (pendingRequestsChartInstance) {
                pendingRequestsChartInstance.destroy();
            }
            
            // Default to 0 if not provided
            if (typeof pendingCount === 'undefined') {
                pendingCount = 0;
            }
            
            var totalCapacity = 50; // Example total capacity
            var percentage = Math.round((pendingCount / totalCapacity) * 100);
            
            pendingRequestsChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [pendingCount, Math.max(0, totalCapacity - pendingCount)],
                        backgroundColor: ['#FFFFFF', 'rgba(255, 255, 255, 0.2)'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
            
            // Update label
            document.getElementById('pending-requests-label').textContent = pendingCount;
        }

        // Update chart with new data
        function updatePendingRequestsChart(pendingCount) {
            initPendingRequestsChart(pendingCount);
        }

        // ====== DATA MANAGEMENT FUNCTIONS ======
        
        function loadDataManagement() {
            loadRejectedRecords();
            loadInactiveAccounts();
        }

        async function loadRejectedRecords() {
            try {
                const response = await fetch('<?php echo e(route("admin.data-management.rejected-records")); ?>', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                const tbody = document.getElementById('rejected-records-table');
                if (!data.records || data.records.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-4">No rejected records found</td></tr>';
                    document.getElementById('delete-all-eligible-records-btn').disabled = true;
                    return;
                }
                
                let eligibleCount = 0;
                tbody.innerHTML = data.records.map(record => {
                    const isEligible = record.days_since_rejection >= 7;
                    if (isEligible) eligibleCount++;
                    
                    return `
                        <tr class="${isEligible ? 'bg-red-50' : ''}">
                            <td class="text-center">${record.student_name}</td>
                            <td class="text-center">${record.event_name}</td>
                            <td class="text-center">${record.rejected_at}</td>
                            <td class="text-center">
                                <span class="font-bold ${isEligible ? 'text-red-600' : 'text-gray-600'}">
                                    ${record.days_since_rejection} days
                                </span>
                            </td>
                            <td class="text-center">
                                ${isEligible 
                                    ? '<span class="badge badge-error text-white">Ready</span>' 
                                    : `<span class="badge badge-warning">${record.deletion_status}</span>`
                                }
                            </td>
                            <td class="text-center">
                                ${isEligible 
                                    ? `<button onclick="showDeleteRecordModal(${record.id})" class="btn btn-sm btn-error text-white">Delete</button>`
                                    : '<span class="text-gray-400">Not eligible</span>'
                                }
                            </td>
                        </tr>
                    `;
                }).join('');
                
                document.getElementById('delete-all-eligible-records-btn').disabled = eligibleCount === 0;
            } catch (error) {
                console.error('Error loading rejected records:', error);
                showToast('Failed to load rejected records', 'error');
            }
        }

        async function loadInactiveAccounts() {
            try {
                const response = await fetch('<?php echo e(route("admin.data-management.inactive-accounts")); ?>', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                const tbody = document.getElementById('inactive-accounts-table');
                if (!data.accounts || data.accounts.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-500 py-4">No inactive accounts found</td></tr>';
                    document.getElementById('delete-all-eligible-accounts-btn').disabled = true;
                    return;
                }
                
                let eligibleCount = 0;
                tbody.innerHTML = data.accounts.map(account => {
                    const isEligible = account.days_inactive >= 7;
                    if (isEligible) eligibleCount++;
                    
                    return `
                        <tr class="${isEligible ? 'bg-red-50' : ''}">
                            <td class="text-center">${account.name}</td>
                            <td class="text-center">${account.student_id || 'â€”'}</td>
                            <td class="text-center">${account.email}</td>
                            <td class="text-center">${account.inactive_at}</td>
                            <td class="text-center">
                                <span class="font-bold ${isEligible ? 'text-red-600' : 'text-gray-600'}">
                                    ${account.days_inactive} days
                                </span>
                            </td>
                            <td class="text-center">
                                ${isEligible 
                                    ? '<span class="badge badge-error text-white">Ready</span>' 
                                    : `<span class="badge badge-warning">${account.time_until_deletion}</span>`
                                }
                            </td>
                            <td class="text-center">
                                ${isEligible 
                                    ? `<button onclick="showDeleteAccountModal(${account.id})" class="btn btn-sm btn-error text-white">Delete</button>`
                                    : '<span class="text-gray-400">Not eligible</span>'
                                }
                            </td>
                        </tr>
                    `;
                }).join('');
                
                document.getElementById('delete-all-eligible-accounts-btn').disabled = eligibleCount === 0;
            } catch (error) {
                console.error('Error loading inactive accounts:', error);
                showToast('Failed to load inactive accounts', 'error');
            }
        }

        let pendingRecordId = null;
        let pendingAccountId = null;

        function showDeleteRecordModal(recordId) {
            pendingRecordId = recordId;
            document.getElementById('confirm_delete_record_modal').showModal();
        }

        function showDeleteAccountModal(accountId) {
            pendingAccountId = accountId;
            document.getElementById('confirm_delete_account_modal').showModal();
        }

        function confirmDeleteRecord() {
            document.getElementById('confirm_delete_record_modal').close();
            if (pendingRecordId) {
                deleteRecord(pendingRecordId);
                pendingRecordId = null;
            }
        }

        function confirmDeleteAccount() {
            document.getElementById('confirm_delete_account_modal').close();
            if (pendingAccountId) {
                deleteAccount(pendingAccountId);
                pendingAccountId = null;
            }
        }

        // Helper function to ensure session is alive and get fresh CSRF token
        async function ensureSessionAndGetToken() {
            try {
                // Get fresh CSRF token directly - this also validates session
                const response = await fetch('/refresh-csrf', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.token) {
                        // Update the meta tag
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) {
                            metaTag.setAttribute('content', data.token);
                        }
                        
                        // Also update axios default if available
                        if (window.axios && window.axios.defaults && window.axios.defaults.headers) {
                            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = data.token;
                        }
                        
                        console.log('Session validated and CSRF token refreshed');
                        return data.token;
                    }
                }
                
                console.warn('Failed to get CSRF token from server');
            } catch (error) {
                console.error('Failed to ensure session and token:', error);
            }
            return null;
        }
        
        // Initialize - get fresh token on page load
        document.addEventListener('DOMContentLoaded', async function() {
            await ensureSessionAndGetToken();
        });

        async function deleteRecord(recordId) {
            try {
                console.log('=== Starting delete record process ===');
                console.log('Record ID:', recordId);
                
                // Show loading toast
                showToast('Deleting record...', 'info');
                
                // Ensure session is alive and get fresh token
                let csrfToken = await ensureSessionAndGetToken();
                
                // Fallback to meta tag if refresh failed
                if (!csrfToken) {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
                }
                
                console.log('CSRF Token available:', csrfToken ? 'Yes' : 'No');
                
                if (!csrfToken) {
                    showToast('Security token not found. Refreshing page...', 'error');
                    setTimeout(() => window.location.reload(), 1500);
                    return;
                }
                
                const url = `<?php echo e(url('/admin/data-management/records')); ?>/${recordId}`;
                console.log('Calling DELETE:', url);
                
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include',
                    cache: 'no-cache'
                });
                
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                // Handle authentication errors first
                if (response.status === 401 || response.status === 403) {
                    console.error('Authentication failed - redirecting to login');
                    showToast('Session expired. Redirecting to login...', 'error');
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route("admin.login")); ?>';
                    }, 1500);
                    return;
                }
                
                // Handle CSRF token mismatch
                if (response.status === 419) {
                    console.error('CSRF token mismatch (419) - Session may have expired');
                    showToast('Session expired. Refreshing page...', 'error');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                    return;
                }
                
                // Handle other errors
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    showToast(`Failed to delete record (Error ${response.status})`, 'error');
                    return;
                }
                
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    showToast('Record deleted successfully', 'success');
                    loadRejectedRecords();
                } else if (data.unauthenticated) {
                    showToast('Session expired. Refreshing page...', 'error');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message || 'Failed to delete record', 'error');
                }
            } catch (error) {
                console.error('Error deleting record:', error);
                showToast('Network error. Please check your connection.', 'error');
            }
        }

        async function deleteAccount(accountId) {
            try {
                // Ensure session is alive and get fresh token
                let csrfToken = await ensureSessionAndGetToken();
                
                // Fallback to meta tag if refresh failed
                if (!csrfToken) {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
                }
                
                if (!csrfToken) {
                    showToast('Security token not found. Please refresh the page.', 'error');
                    return;
                }
                
                const response = await fetch(`<?php echo e(url('/admin/data-management/accounts')); ?>/${accountId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache'
                });
                
                if (!response.ok) {
                    if (response.status === 419) {
                        // Retry with fresh session and token
                        await new Promise(resolve => setTimeout(resolve, 500));
                        const newToken = await ensureSessionAndGetToken();
                        if (newToken) {
                            const retryResponse = await fetch(`<?php echo e(url('/admin/data-management/accounts')); ?>/${accountId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': newToken,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                credentials: 'same-origin',
                                cache: 'no-cache'
                            });
                            if (retryResponse.ok) {
                                const retryData = await retryResponse.json();
                                if (retryData.success) {
                                    showToast('Account deleted successfully', 'success');
                                    loadInactiveAccounts();
                                    return;
                                }
                            }
                        }
                        showToast('Session expired. Please refresh the page.', 'error');
                        setTimeout(() => window.location.reload(), 2000);
                        return;
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                if (data.success) {
                    showToast('Account deleted successfully', 'success');
                    loadInactiveAccounts();
                } else {
                    showToast(data.message || 'Failed to delete account', 'error');
                }
            } catch (error) {
                console.error('Error deleting account:', error);
                showToast('Failed to delete account: ' + error.message, 'error');
            }
        }

        function confirmDeleteAllRecords() {
            document.getElementById('confirm_delete_all_records_modal').close();
            deleteAllEligibleRecords();
        }

        async function deleteAllEligibleRecords() {
            try {
                // Ensure session is alive and get fresh token
                let csrfToken = await ensureSessionAndGetToken();
                
                // Fallback to meta tag if refresh failed
                if (!csrfToken) {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
                }
                
                if (!csrfToken) {
                    showToast('Security token not found. Please refresh the page.', 'error');
                    return;
                }
                
                const response = await fetch('<?php echo e(route("admin.data-management.delete-all-records")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache'
                });
                
                if (!response.ok) {
                    if (response.status === 419) {
                        // Retry with fresh session and token
                        await new Promise(resolve => setTimeout(resolve, 500));
                        const newToken = await ensureSessionAndGetToken();
                        if (newToken) {
                            const retryResponse = await fetch('<?php echo e(route("admin.data-management.delete-all-records")); ?>', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': newToken,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                credentials: 'same-origin',
                                cache: 'no-cache'
                            });
                            if (retryResponse.ok) {
                                const retryData = await retryResponse.json();
                                if (retryData.success) {
                                    showToast(`${retryData.count} record(s) deleted successfully`, 'success');
                                    loadRejectedRecords();
                                    return;
                                }
                            }
                        }
                        showToast('Session expired. Please refresh the page.', 'error');
                        setTimeout(() => window.location.reload(), 2000);
                        return;
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                if (data.success) {
                    showToast(`${data.count} record(s) deleted successfully`, 'success');
                    loadRejectedRecords();
                } else {
                    showToast(data.message || 'Failed to delete records', 'error');
                }
            } catch (error) {
                console.error('Error deleting records:', error);
                showToast('Failed to delete records', 'error');
            }
        }

        function confirmDeleteAllAccounts() {
            document.getElementById('confirm_delete_all_accounts_modal').close();
            deleteAllEligibleAccounts();
        }

        async function deleteAllEligibleAccounts() {
            try {
                // Ensure session is alive and get fresh token
                let csrfToken = await ensureSessionAndGetToken();
                
                // Fallback to meta tag if refresh failed
                if (!csrfToken) {
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;
                }
                
                if (!csrfToken) {
                    showToast('Security token not found. Please refresh the page.', 'error');
                    return;
                }
                
                const response = await fetch('<?php echo e(route("admin.data-management.delete-all-accounts")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache'
                });
                
                if (!response.ok) {
                    if (response.status === 419) {
                        // Retry with fresh session and token
                        await new Promise(resolve => setTimeout(resolve, 500));
                        const newToken = await ensureSessionAndGetToken();
                        if (newToken) {
                            const retryResponse = await fetch('<?php echo e(route("admin.data-management.delete-all-accounts")); ?>', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': newToken,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                credentials: 'same-origin',
                                cache: 'no-cache'
                            });
                            if (retryResponse.ok) {
                                const retryData = await retryResponse.json();
                                if (retryData.success) {
                                    showToast(`${retryData.count} account(s) deleted successfully`, 'success');
                                    loadInactiveAccounts();
                                    return;
                                }
                            }
                        }
                        showToast('Session expired. Please refresh the page.', 'error');
                        setTimeout(() => window.location.reload(), 2000);
                        return;
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                if (data.success) {
                    showToast(`${data.count} account(s) deleted successfully`, 'success');
                    loadInactiveAccounts();
                } else {
                    showToast(data.message || 'Failed to delete accounts', 'error');
                }
            } catch (error) {
                console.error('Error deleting accounts:', error);
                showToast('Failed to delete accounts: ' + error.message, 'error');
            }
        }
    </script>

    <!-- Session Keeper: Keeps session alive and CSRF token fresh -->
    <script src="<?php echo e(asset('js/session-keeper.js')); ?>"></script>
    <script>
        // Initialize Session Keeper for Admin Dashboard
        if (window.SessionKeeper) {
            SessionKeeper.init({
                debug: false, // Set to true for debugging
                autoRefreshEnabled: true,
                dataRefreshInterval: 30 * 1000, // Refresh data every 30 seconds
                onDataRefresh: function() {
                    // Refresh submissions and stats automatically
                    console.log('[Admin Dashboard] Auto-refreshing data...');
                    if (typeof fetchSubmissions === 'function') {
                        fetchSubmissions();
                    }
                    if (typeof fetchDashboardStats === 'function') {
                        fetchDashboardStats();
                    }
                }
            });
        }
    </script>

    <!-- Additional Keep-Alive Scripts (Fallback & Simplicity) -->
    <script>
        // Aggressive CSRF and session refresh
        async function refreshCsrf() {
            try {
                const res = await fetch('/refresh-csrf', { 
                    credentials: 'include',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag && data.token) {
                    metaTag.content = data.token;
                }
                
                if (window.axios && data.token) {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = data.token;
                }
                console.log('[Keep-Alive] CSRF token refreshed');
            } catch (e) {
                console.warn('[Keep-Alive] CSRF refresh failed', e);
            }
        }

        // Aggressive keep-alive ping
        async function keepAlive() {
            try {
                await fetch('/keep-alive', { 
                    credentials: 'include',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                console.log('[Keep-Alive] Session ping successful');
            } catch (e) {
                console.warn('[Keep-Alive] Session ping failed', e);
            }
        }

        // Run VERY frequently to prevent session expiry
        setInterval(refreshCsrf, 2 * 60 * 1000); // Every 2 minutes
        setInterval(keepAlive, 1 * 60 * 1000);   // Every 1 minute
        
        // Run immediately on page load
        refreshCsrf();
        keepAlive();
        
        // Sidebar collapse functionality
        (function() {
            const sidebar = document.getElementById('sidebar');
            const collapseBtn = document.getElementById('collapse-btn');
            const collapseText = document.getElementById('collapse-text');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileCloseBtn = document.getElementById('mobile-close-btn');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            
            // Check if mobile view
            function isMobile() {
                return window.innerWidth <= 768;
            }
            
            // Load saved state from localStorage (only for desktop)
            const savedState = localStorage.getItem('scms_admin_sidebar_collapsed');
            if (savedState === 'true' && !isMobile()) {
                sidebar.classList.add('collapsed');
                collapseText.textContent = 'Show';
            }
            
            // Toggle collapse on button click (desktop)
            collapseBtn.addEventListener('click', function() {
                if (isMobile()) return; // Don't collapse on mobile
                
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                collapseText.textContent = isCollapsed ? 'Show' : 'Hide';
                
                // Save state to localStorage
                localStorage.setItem('scms_admin_sidebar_collapsed', isCollapsed);
            });
            
            // Mobile menu toggle
            function openMobileSidebar() {
                sidebar.classList.add('mobile-open');
                sidebarBackdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeMobileSidebar() {
                sidebar.classList.remove('mobile-open');
                sidebarBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Mobile menu button click
            mobileMenuBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('mobile-open')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            });
            
            // Mobile close button click
            mobileCloseBtn.addEventListener('click', function() {
                closeMobileSidebar();
            });
            
            // Close sidebar when clicking backdrop
            sidebarBackdrop.addEventListener('click', function() {
                closeMobileSidebar();
            });
            
            // Close sidebar when clicking a nav link on mobile
            const navLinks = sidebar.querySelectorAll('#menu-list a, ul.menu a, ul.menu button');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (isMobile()) {
                        closeMobileSidebar();
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    // Switching to desktop: close mobile sidebar and restore collapse state
                    closeMobileSidebar();
                    const savedState = localStorage.getItem('scms_admin_sidebar_collapsed');
                    if (savedState === 'true') {
                        sidebar.classList.add('collapsed');
                        collapseText.textContent = 'Show';
                    }
                }
            });
        })();
    </script>
</body>
</html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/dashboards/admin.blade.php ENDPATH**/ ?>