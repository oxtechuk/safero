@props([
    'title' => '',
    'newUrl' => null,
    'newText' => 'New',
    'newColor' => 'bg-[#714B67]', // Authentic IsoOx Plum from Image 1
    'searchAction' => '',
    'searchValue' => '',
    'searchPlaceholder' => 'البحث...',
    'paginator' => null,
    'activeView' => 'list',
    'views' => ['list', 'kanban'],
    'badge' => null,
])

<div class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-2.5 sticky top-14 z-40 shadow-xs">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
        
        <!-- Left: Action Button + Title + Settings Cog -->
        <div class="flex items-center gap-3">
            @if($newUrl)
                <a href="{{ $newUrl }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-md {{ $newColor }} hover:brightness-110 text-white font-bold text-xs sm:text-sm shadow-xs transition transform active:scale-95">
                    <span class="font-en font-black tracking-wide">{{ $newText }}</span>
                </a>
            @endif

            <div class="flex items-center gap-2">
                <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>{{ $title }}</span>
                    @if($badge)
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $badge['class'] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $badge['label'] }}
                        </span>
                    @endif
                </h1>

                <!-- Settings Cog (IsoOx style) -->
                <button type="button" 
                        class="p-1 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" 
                        title="{{ __('إعدادات العرض والحقول') }}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Center & Right: Search, Filter Popover, Pager, View Switchers -->
        <div class="flex flex-wrap items-center justify-between lg:justify-end gap-2 sm:gap-3">
            
            <!-- Search Input with Integrated Dropdown Caret (IsoOx 17 Searchbox) -->
            <div class="relative flex-1 min-w-[220px] sm:w-80 max-w-md">
                <form method="GET" action="{{ $searchAction }}" class="relative m-0" id="IsoOxSearchForm">
                    <!-- Keep existing query params except search & page -->
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        @if(is_string($v) || is_numeric($v))
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach

                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <input type="text" 
                               name="search" 
                               value="{{ $searchValue }}" 
                               placeholder="{{ $searchPlaceholder }}" 
                               class="w-full pr-8 pl-8 py-1.5 rounded border border-slate-300 focus:border-slate-500 focus:ring-1 focus:ring-slate-400 outline-none text-xs bg-slate-50/50 hover:bg-white focus:bg-white transition placeholder:text-slate-400">

                        <!-- Filter Popover Toggle -->
                        <button type="button" 
                                onclick="toggleIsoOxFilterMenu()" 
                                class="absolute inset-y-0 left-0 pl-2.5 pr-1.5 flex items-center text-slate-400 hover:text-slate-700 transition" 
                                title="{{ __('خيارات التصفية والتجميع') }}">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Filter & Group By Dropdown Menu -->
                <div id="IsoOxFilterMenu" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-xl p-3 z-50 text-xs">
                    {{ $slot }}
                </div>
            </div>

            <!-- Pager (1-15 / 142 with < > controls) -->
            @if($paginator)
                <div class="flex items-center gap-1.5 text-xs text-slate-600 font-en select-none">
                    <span class="font-semibold text-slate-700">
                        @if($paginator->total() > 0)
                            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} / {{ $paginator->total() }}
                        @else
                            0-0 / 0
                        @endif
                    </span>

                    <div class="inline-flex rounded border border-slate-300 overflow-hidden bg-white shadow-2xs">
                        @if($paginator->onFirstPage())
                            <span class="px-2 py-1 text-slate-300 cursor-not-allowed bg-slate-50">
                                <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}" class="px-2 py-1 text-slate-600 hover:bg-slate-100 transition" title="{{ __('الصفحة السابقة') }}">
                                <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @endif

                        @if($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}" class="px-2 py-1 text-slate-600 hover:bg-slate-100 transition border-r border-slate-200" title="{{ __('الصفحة التالية') }}">
                                <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <span class="px-2 py-1 text-slate-300 cursor-not-allowed bg-slate-50 border-r border-slate-200">
                                <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- View Switchers (List, Kanban, Pivot, Graph, Activity) -->
            <div class="inline-flex items-center rounded border border-slate-300 bg-white p-0.5 shadow-2xs text-slate-500">
                <!-- List View Button -->
                <button type="button" 
                        onclick="switchIsoOxView('list')" 
                        id="viewBtnList"
                        class="p-1.5 rounded transition text-slate-700 bg-slate-100 border border-slate-300/80 shadow-2xs font-bold" 
                        title="{{ __('عرض القائمة') }}">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>
                    </svg>
                </button>

                <!-- Kanban View Button -->
                <button type="button" 
                        onclick="switchIsoOxView('kanban')" 
                        id="viewBtnKanban"
                        class="p-1.5 rounded transition text-slate-500 hover:text-slate-800 hover:bg-slate-50" 
                        title="{{ __('عرض البطاقات') }}">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 4h7v16H4zm9 0h7v10h-7z"/>
                    </svg>
                </button>

                <!-- Activity / Clock View Button -->
                <button type="button" 
                        class="p-1.5 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-50 transition hidden sm:inline-flex" 
                        title="{{ __('عرض الأنشطة والمهام') }}">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <!-- Column Settings / Toggle -->
                <button type="button" 
                        class="p-1.5 rounded text-slate-400 hover:text-slate-700 hover:bg-slate-50 transition border-r border-slate-200" 
                        title="{{ __('تخصيص الأعمدة') }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </button>
            </div>

        </div>

    </div>
</div>

<script>
    function toggleIsoOxFilterMenu() {
        const menu = document.getElementById('IsoOxFilterMenu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    function switchIsoOxView(view) {
        const listView = document.getElementById('IsoOxListView');
        const kanbanView = document.getElementById('IsoOxKanbanView');
        const btnList = document.getElementById('viewBtnList');
        const btnKanban = document.getElementById('viewBtnKanban');

        if (view === 'list') {
            if (listView) listView.classList.remove('hidden');
            if (kanbanView) kanbanView.classList.add('hidden');
            if (btnList) {
                btnList.className = 'p-1.5 rounded transition text-slate-700 bg-slate-100 border border-slate-300/80 shadow-2xs font-bold';
            }
            if (btnKanban) {
                btnKanban.className = 'p-1.5 rounded transition text-slate-500 hover:text-slate-800 hover:bg-slate-50';
            }
            localStorage.setItem('IsoOx_pref_view', 'list');
        } else if (view === 'kanban') {
            if (listView) listView.classList.add('hidden');
            if (kanbanView) kanbanView.classList.remove('hidden');
            if (btnList) {
                btnList.className = 'p-1.5 rounded transition text-slate-500 hover:text-slate-800 hover:bg-slate-50';
            }
            if (btnKanban) {
                btnKanban.className = 'p-1.5 rounded transition text-slate-700 bg-slate-100 border border-slate-300/80 shadow-2xs font-bold';
            }
            localStorage.setItem('IsoOx_pref_view', 'kanban');
        }
    }

    // Auto restore preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('IsoOx_pref_view');
        if (savedView === 'kanban' && document.getElementById('IsoOxKanbanView')) {
            switchIsoOxView('kanban');
        }

        // Close filter dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('IsoOxFilterMenu');
            const toggle = e.target.closest('[onclick="toggleIsoOxFilterMenu()"]');
            if (menu && !menu.contains(e.target) && !toggle && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });

        // Global Alt+A shortcut to open /app
        document.addEventListener('keydown', function(e) {
            if (e.altKey && (e.key === 'a' || e.key === 'A' || e.key === 'ش')) {
                e.preventDefault();
                window.location.href = "{{ route('apps') }}";
            }
        });
    });
</script>
