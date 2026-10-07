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
</body>
</html>
