<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phyzioline - Professional Social Ecosystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f3f4f6;
        }
        
        #app-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            position: relative;
            padding-bottom: 80px;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .chart-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: 250px;
            max-height: 300px;
            margin: 0 auto;
        }

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

    <!-- Desktop context wrapper -->
    <div class="hidden md:flex flex-col items-center justify-center min-h-screen p-4">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold text-teal-700">Phyzioline Ecosystem</h1>
            <p class="text-gray-500">{{ __('Interactive Mobile Experience') }}</p>
        </div>
        <div id="mobile-frame-wrapper" class="w-full max-w-[480px] h-[850px] overflow-hidden rounded-3xl border-8 border-gray-800 bg-white shadow-2xl relative">
            <div id="desktop-app-container" class="w-full h-full overflow-y-auto no-scrollbar bg-gray-50"></div>
        </div>
    </div>

    <!-- Mobile View -->
    <div id="app-container" class="md:hidden">
        <!-- Content injected via JS -->
    </div>

    <script>
        // --- DATA & STATE ---
        const state = {
            currentUser: {
                id: '{{ auth()->id() }}',
                name: '{{ auth()->user()->name }}',
                role: '{{ auth()->user()->type }}',
                avatar: '{{ auth()->user()->profile_photo ?? "https://placehold.co/150x150/02767F/white?text=" . substr(auth()->user()->name, 0, 1) }}',
                verified: {{ auth()->user()->is_verified ? 'true' : 'false' }}
            },
            currentTab: 'feed',
            posts: [
                @foreach($feedItems as $item)
                {
                    id: '{{ $item->id }}',
                    type: '{{ $item->type }}',
                    author: { 
                        name: '{{ $item->sourceable ? $item->sourceable->name : "Phyzioline System" }}', 
                        role: '{{ $item->sourceable_type == "App\\Models\\User" ? $item->sourceable->type : "admin" }}',
                        avatar: '{{ $item->sourceable && isset($item->sourceable->profile_photo) ? $item->sourceable->profile_photo : "https://placehold.co/100x100/02767F/white?text=P" }}',
                        verified: true 
                    },
                    timestamp: '{{ $item->created_at->diffForHumans() }}',
                    content: { 
                        text: `{!! addslashes($item->description) !!}`,
                        @if($item->type == 'product' && $item->sourceable)
                            title: '{{ $item->sourceable->product_name_ar ?? $item->sourceable->product_name_en ?? $item->title }}',
                            price: '{{ number_format($item->sourceable->product_price ?? 0, 0) }} {{ __("EGP") }}',
                        @elseif($item->type == 'course' && $item->sourceable)
                            title: '{{ $item->sourceable->course_name_ar ?? $item->sourceable->course_name_en ?? "" }}',
                            duration: '{{ $item->sourceable->duration ?? "" }}',
                        @elseif($item->type == 'job' && $item->sourceable)
                            location: '{{ $item->sourceable->job_location ?? "" }}',
                            salary: '{{ $item->sourceable->salary_range ?? "" }}',
                        @endif
                    },
                    media: {{ $item->media_url ? json_encode(['type' => 'image', 'url' => $item->media_url]) : 'null' }},
                    metrics: { likes: {{ $item->likes_count ?? 0 }}, comments: {{ $item->comments_count ?? 0 }} },
                    action: { 
                        label: '{{ $item->action_text ?? __("View Details") }}', 
                        type: '{{ $item->type }}',
                        link: '{{ $item->action_link ?? "#" }}'
                    }
                },
                @endforeach
            ]
        };

        // --- RENDER FUNCTIONS ---

        function renderApp() {
            const isDesktop = window.matchMedia("(min-width: 768px)").matches;
            const containerId = isDesktop ? 'desktop-app-container' : 'app-container';
            const root = document.getElementById(containerId);
            
            if (!root) return;

            root.innerHTML = '';
            root.className = "bg-gray-50 min-h-screen pb-20 font-sans {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}";
            root.dir = "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}";

            root.appendChild(createHeader());

            const mainContent = document.createElement('main');
            mainContent.id = 'main-content';
            mainContent.className = 'w-full max-w-lg mx-auto';
            root.appendChild(mainContent);

            const nav = createBottomNav();
            if(isDesktop) {
                nav.style.position = 'absolute';
                nav.style.bottom = '0';
                nav.style.width = '100%';
                nav.classList.remove('fixed');
            }
            root.appendChild(nav);

            const modalContainer = document.createElement('div');
            modalContainer.id = 'modal-container';
            if(isDesktop) {
                modalContainer.className = "absolute inset-0 pointer-events-none z-50";
            }
            root.appendChild(modalContainer);

            navigateTo(state.currentTab);
        }

        function createHeader() {
            const header = document.createElement('header');
            header.className = "sticky top-0 z-40 bg-white/95 backdrop-blur shadow-sm px-4 py-3 flex justify-between items-center";
            header.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 relative">
                        🔔
                        <span class="absolute top-0 {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }} w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                        💬
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
            
            const tabs = {{ app()->getLocale() == 'ar' ? '[
                { id: "profile", icon: "👤", label: "حسابي" },
                { id: "jobs", icon: "💼", label: "وظائف" },
                { id: "create", icon: "➕", label: "نشر", highlight: true },
                { id: "shop", icon: "🛍️", label: "المتجر" },
                { id: "feed", icon: "🏠", label: "الرئيسية" }
            ]' : '[
                { id: "feed", icon: "🏠", label: "Home" },
                { id: "shop", icon: "🛍️", label: "Shop" },
                { id: "create", icon: "➕", label: "Create", highlight: true },
                { id: "jobs", icon: "💼", label: "Jobs" },
                { id: "profile", icon: "👤", label: "Profile" }
            ]' }};

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
            const nav = document.querySelector('nav');
            if(nav) nav.replaceWith(createBottomNav());

            const main = document.getElementById('main-content');
            main.innerHTML = '';

            if (tabId === 'create') {
                openCreateModal();
                return;
            }

            if (tabId === 'feed') renderFeed(main);
            else if (tabId === 'shop') window.location.href = '{{ route("web.shop.show." . app()->getLocale()) }}';
            else if (tabId === 'jobs') window.location.href = '{{ route("web.jobs.index." . app()->getLocale()) }}';
            else if (tabId === 'profile') window.location.href = '{{ route("web.profile.index." . app()->getLocale()) }}';
            
            window.scrollTo(0, 0);
        }

        // --- FEED TAB ---

        function renderFeed(container) {
            const intro = document.createElement('div');
            intro.className = "px-4 py-3 bg-teal-50 border-b border-teal-100 mb-2";
            intro.innerHTML = `
                <p class="text-xs text-teal-800 font-medium text-center">
                    👋 {{ __('Welcome to Phyzioline community. Discover latest products, jobs, and expert insights.') }}
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
                <a href="{{ route('feed.index.' . app()->getLocale()) }}" class="flex flex-col items-center gap-1 min-w-[64px]">
                    <div class="w-16 h-16 rounded-full p-[2px] bg-gradient-to-tr from-teal-400 to-teal-500">
                        <div class="w-full h-full rounded-full bg-white border-2 border-white overflow-hidden flex items-center justify-center text-2xl">
                            🏠
                        </div>
                    </div>
                    <span class="text-xs text-gray-600 truncate w-full text-center">{{ __('All') }}</span>
                </a>
                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'product']) }}" class="flex flex-col items-center gap-1 min-w-[64px]">
                    <div class="w-16 h-16 rounded-full p-[2px] {{ request('type') == 'product' ? 'bg-gradient-to-tr from-yellow-400 to-teal-500' : 'bg-gray-200' }}">
                        <div class="w-full h-full rounded-full bg-white border-2 border-white overflow-hidden flex items-center justify-center text-2xl">
                            🛍️
                        </div>
                    </div>
                    <span class="text-xs text-gray-600 truncate w-full text-center">{{ __('Products') }}</span>
                </a>
                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'job']) }}" class="flex flex-col items-center gap-1 min-w-[64px]">
                    <div class="w-16 h-16 rounded-full p-[2px] {{ request('type') == 'job' ? 'bg-gradient-to-tr from-yellow-400 to-teal-500' : 'bg-gray-200' }}">
                        <div class="w-full h-full rounded-full bg-white border-2 border-white overflow-hidden flex items-center justify-center text-2xl">
                            💼
                        </div>
                    </div>
                    <span class="text-xs text-gray-600 truncate w-full text-center">{{ __('Jobs') }}</span>
                </a>
                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'course']) }}" class="flex flex-col items-center gap-1 min-w-[64px]">
                    <div class="w-16 h-16 rounded-full p-[2px] {{ request('type') == 'course' ? 'bg-gradient-to-tr from-yellow-400 to-teal-500' : 'bg-gray-200' }}">
                        <div class="w-full h-full rounded-full bg-white border-2 border-white overflow-hidden flex items-center justify-center text-2xl">
                            📚
                        </div>
                    </div>
                    <span class="text-xs text-gray-600 truncate w-full text-center">{{ __('Courses') }}</span>
                </a>
                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'therapist']) }}" class="flex flex-col items-center gap-1 min-w-[64px]">
                    <div class="w-16 h-16 rounded-full p-[2px] {{ request('type') == 'therapist' ? 'bg-gradient-to-tr from-yellow-400 to-teal-500' : 'bg-gray-200' }}">
                        <div class="w-full h-full rounded-full bg-white border-2 border-white overflow-hidden flex items-center justify-center text-2xl">
                            🩺
                        </div>
                    </div>
                    <span class="text-xs text-gray-600 truncate w-full text-center">{{ __('Therapists') }}</span>
                </a>
            `;
            container.appendChild(stories);

            // Posts
            state.posts.forEach(post => {
                container.appendChild(createPostCard(post));
            });
            
            @if($feedItems->isEmpty())
            const empty = document.createElement('div');
            empty.className = "text-center py-12";
            empty.innerHTML = `
                <div class="text-6xl mb-4">📭</div>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('No posts yet') }}</h3>
                <p class="text-gray-500 text-sm">{{ __('Be the first to share something!') }}</p>
            `;
            container.appendChild(empty);
            @endif
        }

        function createPostCard(post) {
            const card = document.createElement('div');
            card.className = "bg-white mb-3 shadow-sm animate-fade-in";
            
            let roleBadge = '';
            if (post.author.role === 'therapist') roleBadge = '<span class="bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("Therapist") }}</span>';
            if (post.author.role === 'vendor') roleBadge = '<span class="bg-orange-100 text-orange-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("Vendor") }}</span>';
            if (post.author.role === 'company') roleBadge = '<span class="bg-purple-100 text-purple-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("Company") }}</span>';
            if (post.author.role === 'admin') roleBadge = '<span class="bg-teal-100 text-teal-700 text-[10px] px-1.5 py-0.5 rounded mr-2">{{ __("System") }}</span>';

            const headerHtml = `
                <div class="flex justify-between items-center p-3">
                    <div class="flex items-center gap-2">
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

            let contentHtml = `<div class="px-3 pb-2 text-sm text-gray-800 leading-relaxed">${post.content.text}</div>`;
            
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
                        ${post.media ? `<img src="${post.media.url}" class="w-full h-full object-cover">` : '<div class="w-full h-full bg-purple-200"></div>'}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-3">
                            <h3 class="text-white font-bold">${post.content.title || ''}</h3>
                            <span class="text-gray-200 text-xs">⏱ ${post.content.duration || ''}</span>
                        </div>
                    </div>
                `;
            } else if (post.media) {
                contentHtml += `
                    <div class="w-full bg-black flex items-center justify-center relative overflow-hidden rounded">
                        <img src="${post.media.url}" class="w-full object-cover">
                    </div>
                `;
            }

            let actionBtn = '';
            if (post.action && post.action.link !== '#') {
                let btnColor = 'bg-teal-600 hover:bg-teal-700';
                if (post.type === 'job') btnColor = 'bg-blue-600 hover:bg-blue-700';
                if (post.type === 'product') btnColor = 'bg-orange-500 hover:bg-orange-600';

                actionBtn = `
                    <div class="px-3 pb-2">
                        <a href="${post.action.link}" class="block w-full ${btnColor} text-white font-medium py-2 rounded-lg shadow-sm text-center">
                            ${post.action.label}
                        </a>
                    </div>
                `;
            }

            const footerHtml = `
                <div class="px-3 py-2 border-t border-gray-100 flex justify-between items-center text-gray-500">
                    <div class="flex gap-4">
                        <form action="{{ url('/') }}/{{ app()->getLocale() }}/feed/${post.id}/like" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 hover:text-red-500 transition-colors">
                                <span>❤️</span> <span class="text-xs font-medium">${post.metrics.likes}</span>
                            </button>
                        </form>
                        <button class="flex items-center gap-1 hover:text-blue-500 transition-colors">
                            <span>💬</span> <span class="text-xs font-medium">${post.metrics.comments}</span>
                        </button>
                        <button class="hover:text-teal-500">🚀</button>
                    </div>
                    <button class="hover:text-yellow-500">🔖</button>
                </div>
            `;

            card.innerHTML = headerHtml + contentHtml + actionBtn + footerHtml;
            return card;
        }

        function openCreateModal() {
            const modal = document.getElementById('modal-container');
            modal.className = "pointer-events-auto";
            modal.innerHTML = `
                <div class="fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/50 backdrop-blur-sm animate-fade-in">
                    <div class="bg-white w-full max-w-[480px] rounded-t-2xl sm:rounded-2xl p-5 shadow-2xl">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-lg text-gray-800">{{ __('Create New Post') }}</h3>
                            <button onclick="document.getElementById('modal-container').innerHTML='';document.getElementById('modal-container').className=''" class="text-gray-400 text-xl">✕</button>
                        </div>
                        
                        <form action="{{ route('feed.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <textarea name="description" class="w-full bg-gray-50 p-3 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 mb-4 h-32" placeholder="{{ __('Share your thoughts...') }}" required></textarea>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Add Image') }}</label>
                                <input type="file" name="media" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                            </div>
                            
                            <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-teal-600/20">{{ __('Post Now') }}</button>
                        </form>
                    </div>
                </div>
            `;
        }

        // --- INIT ---
        window.addEventListener('load', () => {
            renderApp();
        });

        // Responsive re-render
        window.addEventListener('resize', () => {
            renderApp();
        });
    </script>
</body>
</html>
