<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Intern Management System')</title>
    
    <!-- Google Fonts: Fredoka One + Nunito -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Mobile Responsive Table Script & CSS -->
    <style>
        @media (min-width: 768px) {
            .mobile-cell-wrapper {
                display: contents;
            }
        }
        @media (max-width: 767px) {
            table.mobile-responsive-table, 
            table.mobile-responsive-table thead, 
            table.mobile-responsive-table tbody, 
            table.mobile-responsive-table th, 
            table.mobile-responsive-table td, 
            table.mobile-responsive-table tr {
                display: block;
                width: 100%;
            }
            table.mobile-responsive-table thead {
                display: none;
            }
            table.mobile-responsive-table tr {
                margin-bottom: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                background-color: #ffffff;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            table.mobile-responsive-table td {
                border: none !important;
                border-bottom: 1px solid #f3f4f6 !important;
                padding: 1rem !important;
                display: block !important;
                text-align: left !important;
            }
            table.mobile-responsive-table td:last-child {
                border-bottom: none !important;
            }
            .mobile-cell-wrapper {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                flex-wrap: wrap;
                width: 100%;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('table');
            tables.forEach(table => {
                table.classList.add('mobile-responsive-table');
                const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    if(row.querySelectorAll('td').length === 1 && row.querySelector('td').hasAttribute('colspan')) {
                        return;
                    }
                    
                    const cells = row.querySelectorAll('td');
                    cells.forEach((cell, index) => {
                        if(headers[index] && headers[index] !== '') {
                            const label = document.createElement('div');
                            label.className = 'md:hidden text-[0.7rem] font-black text-gray-400 uppercase tracking-wider mb-2 block w-full';
                            label.innerText = headers[index];
                            
                            const wrapper = document.createElement('div');
                            wrapper.className = 'mobile-cell-wrapper';
                            
                            while (cell.firstChild) {
                                wrapper.appendChild(cell.firstChild);
                            }
                            
                            cell.appendChild(label);
                            cell.appendChild(wrapper);
                        }
                    });
                });
            });
        });
    </script>
</head>
<body class="bg-background text-dark-navy antialiased">
    @yield('content')

    <!-- Global Toast Notifications -->
    <div x-data="{
            showSuccess: {{ session('success') ? 'true' : 'false' }},
            showError: {{ session('error') ? 'true' : 'false' }},
            successMsg: '{{ addslashes(session('success') ?? '') }}',
            errorMsg: '{{ addslashes(session('error') ?? '') }}'
        }" 
        x-init="
            if(showSuccess) { setTimeout(() => showSuccess = false, 2000); }
            if(showError) { setTimeout(() => showError = false, 2000); }
        "
        class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none">
        
        <!-- Success Toast -->
        <div x-show="showSuccess" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="bg-white border-l-4 border-green-500 shadow-2xl rounded-lg p-4 flex items-center gap-4 min-w-[280px] pointer-events-auto"
             style="display: none;">
            <div class="bg-green-100 text-green-600 rounded-full p-2 flex items-center justify-center">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 text-sm">Berhasil!</h4>
                <p class="text-xs text-gray-500 mt-0.5" x-text="successMsg"></p>
            </div>
        </div>

        <!-- Error Toast -->
        <div x-show="showError" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="bg-white border-l-4 border-red-500 shadow-2xl rounded-lg p-4 flex items-center gap-4 min-w-[280px] pointer-events-auto"
             style="display: none;">
            <div class="bg-red-100 text-red-600 rounded-full p-2 flex items-center justify-center">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 text-sm">Perhatian!</h4>
                <p class="text-xs text-gray-500 mt-0.5" x-text="errorMsg"></p>
            </div>
        </div>
    </div>
</body>
</html>
