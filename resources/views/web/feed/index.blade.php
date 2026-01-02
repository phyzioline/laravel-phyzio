<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phyzioline - Professional Social Ecosystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f3f4f6; /* Gray-100 */
        }
        
        /* Mobile App Container Simulation */
        #app-container {
            max-width: 480px; /* Mobile width constraint */
            margin: 0 auto;
            background-color: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            position: relative;
            padding-bottom: 80px; /* Space for bottom nav */
        }

        /* Hide scrollbar for smooth horizontal scrolling */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Chart Container Styling - Mandatory per instructions */
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: 250px;
            max-height: 300px;
            margin: 0 auto;
        }

        /* Animations */
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }

        @keyframes pulse-skeleton {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        .skeleton {
            background-color: #e5e7eb;
            animation: pulse-skeleton 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Custom Scrollbar for desktop view of the container */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</head>
<body class="bg-gray-100 text-slate-800">

    <!-- Desktop context wrapper (centering the mobile app) -->
    <div class="hidden md:flex flex-col items-center justify-center min-h-screen p-4">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold text-teal-700">Phyzioline Ecosystem</h1>
            <p class="text-gray-500">{{ __('Interactive Mobile Experience') }}</p>
        </div>
        <!-- The Actual App Container -->
        <div id="mobile-frame-wrapper" class="w-full max-w-[480px] h-[850px] overflow-hidden rounded-3xl border-8 border-gray-800 bg-white shadow-2xl relative">
            <!-- App Content Injected Here -->
            <div id="desktop-app-container" class="w-full h-full overflow-y-auto no-scrollbar bg-gray-50"></div>
        </div>
    </div>

    <!-- Mobile View (Direct Render) -->
    <div id="app-container" class="md:hidden">
        <!-- Content injected via JS -->
    </div>

    <script>
        // --- DATA & STATE ---
        const state = {
            currentUser: {
                id: '{{ auth()->id() }}',
                name: '{{ auth()->user()->name }}',
                role: '{{ auth()->user()->type }}', // admin, vendor, company, therapist
                avatar: '{{ auth()->user()->profile_photo_url }}',
                verified: {{ auth()->user()->is_verified ? 'true' : 'false' }} 
            },
            currentTab: 'feed',
            posts: [
                @foreach($feedItems as $item)
                {
                    id: '{{ $item->id }}',
                    type: '{{ $item->type }}', // post, product, job, course, therapist
                    author: { 
                        name: '{{ $item->sourceable ? $item->sourceable->name : "System" }}', 
                        role: '{{ $item->sourceable_type == "App\\Models\\User" ? $item->sourceable->type : "system" }}', 
                        avatar: '{{ $item->sourceable && method_exists($item->sourceable, "profile_photo_url") ? $item->sourceable->profile_photo_url : asset("images/logo.png") }}', 
                        verified: true 
                    },
                    timestamp: '{{ $item->created_at->diffForHumans() }}',
                    content: { 
                        text: '{{ \Illuminate\Support\Str::limit($item->description, 150) }}',
                        // Add specific fields based on type if available in your model or generic description
                        @if($item->type == 'product' && $item->sourceable)
                            title: '{{ $item->sourceable->product_name_en ?? $item->title }}',
                            price: '{{ $item->sourceable->product_price ?? "" }} EGP',
                        @elseif($item->type == 'course' && $item->sourceable)
                            title: '{{ $item->sourceable->course_name_en ?? $item->title }}',
                            duration: '{{ $item->sourceable->duration ?? "" }}',
                        @elseif($item->type == 'job' && $item->sourceable)
                             location: '{{ $item->sourceable->job_location ?? "" }}',
                             salary: '{{ $item->sourceable->salary_range ?? "" }}',
                        @endif
                    },
                    media: {{ $item->media_url ? json_encode(['type' => 'image', 'url' => $item->media_url]) : 'null' }},
                     metrics: { likes: {{ $item->likes_count ?? 0 }}, comments: {{ $item->comments_count ?? 0 }} },
                    action: { 
                        label: '{{ $item->action_text ?? "View Details" }}', 
                        type: 'view',
                        link: '{{ $item->action_link }}'
                    }
                },
                @endforeach
            ]
        };

        // --- RENDER FUNCTIONS ---

        function renderApp() {
            // Determine container based on view mode (Desktop vs Mobile)
            const isDesktop = window.matchMedia("(min-width: 768px)").matches;
            const containerId = isDesktop ? 'desktop-app-container' : 'app-container';
            const root = document.getElementById(containerId);
            
            if (!root) return;

            root.innerHTML = '';
            // Ensure proper styling classes are on the container
            root.className = "bg-gray-50 min-h-screen pb-20 font-sans text-right";
            root.dir = "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}";
            @if(app()->getLocale() != 'ar')
                root.classList.remove('text-right');
                root.classList.add('text-left');
            @endif

            // 1. Header
            root.appendChild(createHeader());

            // 2. Main Content Area
            const mainContent = document.createElement('main');
            mainContent.id = 'main-content';
            mainContent.className = 'w-full max-w-lg mx-auto';
            root.appendChild(mainContent);

            // 3. Bottom Nav
            // For desktop view, we need to ensure the nav is positioned relative to the container frame, not the window
            const nav = createBottomNav();
            if(isDesktop) {
                nav.style.position = 'absolute';
                nav.style.bottom = '0';
                nav.style.width = '100%';
                nav.classList.remove('fixed');
            }
            root.appendChild(nav);

            // 4. Modals Container
            const modalContainer = document.createElement('div');
            modalContainer.id = 'modal-container';
            // Ensure modal container is within the root for desktop frame clipping
            if(isDesktop) {
                 modalContainer.className = "absolute inset-0 pointer-events-none z-50"; 
                 // We'll handle pointer events in children
            }
            root.appendChild(modalContainer);

            // Initial Render
            navigateTo(state.currentTab);
        }

        function createHeader() {
            const header = document.createElement('header');
            header.className = "sticky top-0 z-40 bg-white/95 backdrop-blur shadow-sm px-4 py-3 flex justify-between items-center";
            header.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 relative">
                        🔔
                        <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-teal-600 tracking-tight">Phyzioline</h1>
                    <div class="w-6 h-6 bg-teal-600 rounded-md flex items-center justify-center text-white text-xs font-bold">P</div>
                </div>
            `;
            return header;
        }

        function createBottomNav() {
            const nav = document.createElement('nav');
            nav.className = "fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 z-50 flex justify-around items-center max-w-[480px] mx-auto";
            
            const tabs = [
                { id: 'profile', icon: '👤', label: '{{ __("Profile") }}' },
                { id: 'jobs', icon: '💼', label: '{{ __("Jobs") }}' },
                { id: 'create', icon: '➕', label: '{{ __("Post") }}', highlight: true },
                { id: 'shop', icon: '🛍️', label: '{{ __("Shop") }}' },
                { id: 'feed', icon: '🏠', label: '{{ __("Home") }}' },
            ];

            nav.innerHTML = tabs.map(tab => `
                <button onclick="window.navigateTo('${tab.id}')" class="flex flex-col items-center gap-1 p-1 ${state.currentTab === tab.id ? 'text-teal-600' : 'text-gray-400'}">
                    <div class="${tab.highlight ? 'bg-teal-600 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg -mt-4 border-4 border-gray-50' : 'text-2xl'}">
                        ${tab.icon}
                    </div>
                    <span class="text-[10px] font-medium ${tab.highlight ? 'mt-1' : ''}">${tab.label}</span>
                </button>
            `).join('');
            return nav;
        }

        window.navigateTo = function(tabId) {
            state.currentTab = tabId;
            // Update Nav UI
            const isDesktop = window.matchMedia("(min-width: 768px)").matches;
            const existingNav = isDesktop ? document.getElementById('desktop-app-container').querySelector('nav') : document.querySelector('nav');
            
            if(existingNav) {
                const newNav = createBottomNav();
                if(isDesktop) {
                    newNav.style.position = 'absolute';
                    newNav.style.bottom = '0';
                    newNav.style.width = '100%';
                    newNav.classList.remove('fixed');
                }
                existingNav.replaceWith(newNav);
            }

            const main = document.getElementById('main-content');
            if(main) {
                main.innerHTML = '';

                if (tabId === 'create') {
                     openCreateModal();
                    // Return to previous tab visually? Or stay on create? 
                    // User flow typically: click create -> modal -> close -> stay on feed.
                    // But here 'create' is a tab. Let's redirect back to feed after opening modal.
                    state.currentTab = 'feed'; // visual reset
                    return; 
                }

                if (tabId === 'feed') renderFeed(main);
                else if (tabId === 'shop') renderShop(main);
                else if (tabId === 'jobs') renderJobs(main);
                else if (tabId === 'profile') renderProfile(main);
            }
        }

        // --- FEED TAB ---

        function renderFeed(container) {
            // Intro
            const intro = document.createElement('div');
            intro.className = "px-4 py-3 bg-teal-50 border-b border-teal-100 mb-2";
            intro.innerHTML = `
                <p class="text-xs text-teal-800 font-medium text-center">
                    {{ __('Welcome to Phyzioline Community') }}
                </p>
            `;
            container.appendChild(intro);

            // Stories / Quick Actions Bar
            const stories = document.createElement('div');
            stories.className = "flex gap-3 overflow-x-auto p-4 no-scrollbar bg-white mb-2 shadow-sm";
            stories.innerHTML = `
                <div class="flex flex-col items-center gap-1 min-w-[64px]">
                    <div class="w-16 h-16 rounded-full border-2 border-dashed border-teal-400 flex items-center justify-center bg-gray-50 text-2xl cursor-pointer" onclick="openCreateModal()">➕</div>
                    <span class="text-xs font-medium truncate w-full text-center">{{ __('Add') }}</span>
                </div>
                 <!-- Static Stories for Demo -->
                ${['Dr. Mohamed', 'Hope Clinic', 'Spot Sports', 'OrthoCare'].map(name => `
                    <div class="flex flex-col items-center gap-1 min-w-[64px]">
                        <div class="w-16 h-16 rounded-full p-[2px] bg-gradient-to-tr from-yellow-400 to-teal-500">
                            <div class="w-full h-full rounded-full bg-white border-2 border-white overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name=${name}&background=random" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <span class="text-xs text-gray-600 truncate w-full text-center">${name}</span>
                    </div>
                `).join('')}
            `;
            container.appendChild(stories);

            // Posts
            if(state.posts.length > 0) {
                state.posts.forEach(post => {
                    container.appendChild(createPostCard(post));
                });
            } else {
                 container.innerHTML += `<div class="p-8 text-center text-gray-400">{{ __('No updates yet') }}</div>`;
            }
            
            // Loading Spinner (Visual only)
            const spinner = document.createElement('div');
            spinner.className = "py-8 flex justify-center";
            spinner.innerHTML = `<div class="w-6 h-6 border-2 border-gray-300 border-t-teal-600 rounded-full animate-spin"></div>`;
            container.appendChild(spinner);
        }

        function createPostCard(post) {
            const card = document.createElement('div');
            card.className = "bg-white mb-3 shadow-sm animate-fade-in";
            
            // Header
            let roleBadge = '';
            if (post.author.role === 'therapist') roleBadge = '<span class="bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("Therapist") }}</span>';
            if (post.author.role === 'vendor') roleBadge = '<span class="bg-orange-100 text-orange-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("Store") }}</span>';
            if (post.author.role === 'company') roleBadge = '<span class="bg-purple-100 text-purple-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("Company") }}</span>';
            if (post.author.role === 'admin' || post.author.role === 'system') roleBadge = '<span class="bg-teal-100 text-teal-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("System") }}</span>';

            const headerHtml = `
                <div class="flex justify-between items-center p-3">
                    <div class="flex items-center gap-2 cursor-pointer">
                        <img src="${post.author.avatar}" class="w-10 h-10 rounded-full border border-gray-100 object-cover">
                        <div class="flex flex-col">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-gray-900">${post.author.name}</span>
                                ${post.author.verified ? '<span class="text-blue-500 text-xs mr-1">☑️</span>' : ''}
                                ${roleBadge}
                            </div>
                            <span class="text-xs text-gray-400">${post.timestamp}</span>
                        </div>
                    </div>
                    <button class="text-gray-400">⋮</button>
                </div>
            `;

            // Content
            let contentHtml = `<div class="px-3 pb-2 text-sm text-gray-800 leading-relaxed">${post.content.text}</div>`;
            
            // Specific Layouts based on type
            if (post.type === 'product') {
                contentHtml += `
                    <div class="bg-gray-50 p-3 mx-3 mb-2 rounded-lg flex gap-3 border border-gray-100">
                        <div class="w-1/3 bg-gray-200 rounded h-20 overflow-hidden">
                             ${post.media ? `<img src="${post.media.url}" class="w-full h-full object-cover">` : ''}
                        </div>
                        <div class="flex-1 flex flex-col justify-center">
                            <h3 class="font-bold text-gray-800">${post.content.title || ''}</h3>
                            <div class="text-teal-600 font-bold mt-1">${post.content.price || ''}</div>
                        </div>
                    </div>
                `;
            } else if (post.type === 'job') {
                contentHtml += `
                    <div class="mx-3 mb-2 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-center gap-2 mb-2 text-blue-800 font-bold">
                            💼 ${post.content.location || ''}
                        </div>
                        <div class="text-sm text-gray-600">${post.content.salary || ''}</div>
                    </div>
                `;
            } else if (post.type === 'course') {
                contentHtml += `
                    <div class="relative mx-3 mb-2 rounded-lg overflow-hidden h-40 group">
                        ${post.media ? `<img src="${post.media.url}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-purple-100"></div>`}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-3">
                            <h3 class="text-white font-bold">${post.content.title || ''}</h3>
                            <span class="text-gray-200 text-xs">⏱ ${post.content.duration || ''}</span>
                        </div>
                    </div>
                `;
            } else if (post.media && post.media.type === 'image') {
                 contentHtml += `
                     <div class="relative mx-3 mb-2 rounded-lg overflow-hidden h-60">
                        <img src="${post.media.url}" class="w-full h-full object-cover">
                     </div>
                 `;
            }

            // Action Button
            let actionBtn = '';
            if (post.action.link) {
                let btnColor = 'bg-teal-600 hover:bg-teal-700';
                if (post.type === 'book_visit') btnColor = 'bg-blue-600 hover:bg-blue-700';
                
                actionBtn = `
                    <div class="px-3 pb-2">
                        <a href="${post.action.link}" class="w-full block text-center ${btnColor} text-white font-medium py-2 rounded-lg shadow-sm active:scale-[0.98] transition-transform">
                            ${post.action.label}
                        </a>
                    </div>
                `;
            }

            // Footer (Engagement)
            const footerHtml = `
                <div class="px-3 py-2 border-t border-gray-100 flex justify-between items-center text-gray-500">
                    <div class="flex gap-4">
                        <button class="flex items-center gap-1 hover:text-red-500 transition-colors">
                            <span>❤️</span> <span class="text-xs font-medium">${post.metrics.likes}</span>
                        </button>
                    </div>
                </div>
            `;

            card.innerHTML = headerHtml + contentHtml + actionBtn + footerHtml;
            return card;
        }

        // --- PROFILE TAB (Analytics Demo) ---

        function renderProfile(container) {
            container.innerHTML = `
                <div class="bg-white p-6 shadow-sm mb-2">
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-teal-400 to-blue-500 mb-2">
                            <img src="${state.currentUser.avatar}" class="w-full h-full rounded-full border-2 border-white">
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-1">
                            ${state.currentUser.name} <span class="text-blue-500 text-sm">☑️</span>
                        </h2>
                        <span class="text-sm text-gray-500 mb-4">${state.currentUser.role}</span>
                        
                        <div class="flex gap-2 w-full mb-4">
                            <a href="{{ route('profile.show', auth()->id()) }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-2 rounded-lg font-medium text-sm">{{ __('Profile') }}</a>
                        </div>
                    </div>
                </div>

                <!-- ANALYTICS SECTION -->
                <div class="bg-white p-4 shadow-sm mb-20">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        📊 {{ __('Dashboard') }}
                    </h3>
                    
                    <div class="mb-6">
                         <div class="chart-container">
                            <canvas id="visitsChart"></canvas>
                        </div>
                    </div>
                </div>
            `;

            // Initialize Chart
            setTimeout(() => {
                const ctx = document.getElementById('visitsChart');
                if(ctx){
                    new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Activity',
                                data: [12, 19, 15, 25, 22, 30],
                                borderColor: '#0d9488', // Teal-600
                                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                             plugins: { legend: { display: false } }, // Hide legend to save space
                            scales: {
                                y: { beginAtZero: true, grid: { display: false } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
            }, 100);
        }

        // --- SHOP & JOBS TABS (Placeholders) ---
        function renderShop(container) {
             // Redirect or render partial
             container.innerHTML = `<div class="p-8 text-center">{{ __('Loading Shop...') }}</div>`;
             window.location.href = "{{ route('web.shop.show.' . app()->getLocale()) }}";
        }

        function renderJobs(container) {
             container.innerHTML = `<div class="p-8 text-center">{{ __('Loading Jobs...') }}</div>`;
             window.location.href = "{{ route('web.jobs.index.' . app()->getLocale()) }}";
        }


        // --- INTERACTION MODALS ---

        function openCreateModal() {
            const modal = document.getElementById('modal-container');
            // Ensure pointer events are enabled for this child
            modal.innerHTML = `
                <div class="fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/50 backdrop-blur-sm animate-fade-in pointer-events-auto">
                    <div class="bg-white w-full max-w-[480px] rounded-t-2xl sm:rounded-2xl p-5 shadow-2xl">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-lg text-gray-800">{{ __('Create Post') }}</h3>
                            <button onclick="document.getElementById('modal-container').innerHTML=''" class="text-gray-400 text-xl">✕</button>
                        </div>
                        
                        <form action="{{ route('feed.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <textarea name="content" class="w-full bg-gray-50 p-3 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 mb-4 h-32" placeholder="{{ __('Share your thoughts...') }}"></textarea>
                            <input type="file" name="image" class="mb-4">
                            <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-teal-600/20">{{ __('Publish Now') }}</button>
                        </form>
                    </div>
                </div>
            `;
        }

        // --- INIT ---
        window.addEventListener('load', () => {
             renderApp();
             
             // Handle resize to switch modes
             window.addEventListener('resize', renderApp);
        });

    </script>
</body>
</html>
