{{-- Advanced Filters Panel --}}
<div class="filters-panel bg-white border-bottom shadow-sm p-3 mb-2">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0 fw-bold text-muted small">{{ __('Filters') }}</h6>
        <button class="btn btn-link btn-sm p-0 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#advancedFilters" style="color: #02767F;">
            <i class="bi bi-funnel"></i> {{ __('Advanced') }}
        </button>
    </div>

    {{-- Quick Filters (Always Visible) --}}
    <div class="d-flex gap-2 overflow-auto no-scrollbar pb-2">
        <a href="{{ route('feed.index.' . app()->getLocale()) }}" 
           class="btn btn-sm {{ !request()->has('type') ? 'btn-primary' : 'btn-outline-secondary' }}" 
           style="{{ !request()->has('type') ? 'background: #02767F; border-color: #02767F;' : '' }}">
            {{ __('All') }}
        </a>
        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'product']) }}" 
           class="btn btn-sm {{ request('type') == 'product' ? 'btn-primary' : 'btn-outline-secondary' }}"
           style="{{ request('type') == 'product' ? 'background: #02767F; border-color: #02767F;' : '' }}">
            🛍️ {{ __('Products') }}
        </a>
        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'job']) }}" 
           class="btn btn-sm {{ request('type') == 'job' ? 'btn-primary' : 'btn-outline-secondary' }}"
           style="{{ request('type') == 'job' ? 'background: #02767F; border-color: #02767F;' : '' }}">
            💼 {{ __('Jobs') }}
        </a>
        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'course']) }}" 
           class="btn btn-sm {{ request('type') == 'course' ? 'btn-primary' : 'btn-outline-secondary' }}"
           style="{{ request('type') == 'course' ? 'background: #02767F; border-color: #02767F;' : '' }}">
            📚 {{ __('Courses') }}
        </a>
    </div>

    {{-- Advanced Filters (Collapsible) --}}
    <div class="collapse mt-3" id="advancedFilters">
        <form id="advancedFilterForm" method="GET" action="{{ route('feed.index.' . app()->getLocale()) }}">
            <div class="row g-3">
                {{-- Date Range --}}
                <div class="col-md-6">
                    <label class="form-label small fw-bold">{{ __('Date Range') }}</label>
                    <select name="date_range" class="form-select form-select-sm">
                        <option value="">{{ __('All Time') }}</option>
                        <option value="today">{{ __('Today') }}</option>
                        <option value="week">{{ __('This Week') }}</option>
                        <option value="month">{{ __('This Month') }}</option>
                        <option value="3months">{{ __('Last 3 Months') }}</option>
                    </select>
                </div>

                {{-- Author Types --}}
                <div class="col-md-6">
                    <label class="form-label small fw-bold">{{ __('Author Type') }}</label>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="author_types[]" value="therapist" id="author_therapist">
                            <label class="form-check-label small" for="author_therapist">{{ __('Therapists') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="author_types[]" value="vendor" id="author_vendor">
                            <label class="form-check-label small" for="author_vendor">{{ __('Vendors') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="author_types[]" value="company" id="author_company">
                            <label class="form-check-label small" for="author_company">{{ __('Companies') }}</label>
                        </div>
                    </div>
                </div>

                {{-- Engagement Level --}}
                <div class="col-md-6">
                    <label class="form-label small fw-bold">{{ __('Min Likes') }}</label>
                    <input type="number" name="min_likes" class="form-control form-control-sm" min="0" placeholder="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">{{ __('Min Comments') }}</label>
                    <input type="number" name="min_comments" class="form-control form-control-sm" min="0" placeholder="0">
                </div>

                {{-- Search --}}
                <div class="col-12">
                    <label class="form-label small fw-bold">{{ __('Search Keywords') }}</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('Search in title or description...') }}">
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-sm" style="background: #02767F; color: white;">
                    <i class="bi bi-funnel-fill"></i> {{ __('Apply Filters') }}
                </button>
                <a href="{{ route('feed.index.' . app()->getLocale()) }}" class="btn btn-sm btn-outline-secondary">
                    {{ __('Clear All') }}
                </a>
                <button type="button" class="btn btn-sm btn-outline-primary ms-auto" onclick="saveCurrentFilter()">
                    <i class="bi bi-bookmark-plus"></i> {{ __('Save') }}
                </button>
            </div>
        </form>

        {{-- Saved Filters --}}
        @if(isset($savedFilters) && $savedFilters->count() > 0)
        <div class="mt-3 pt-3 border-top">
            <label class="form-label small fw-bold">{{ __('Saved Filters') }}</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($savedFilters as $filter)
                <div class="badge bg-light text-dark border d-flex align-items-center gap-2">
                    <a href="{{ route('feed.index.' . app()->getLocale(), ['filter_id' => $filter->id]) }}" 
                       class="text-decoration-none text-dark">
                        {{ $filter->filter_name }}
                        @if($filter->is_default)
                        <i class="bi bi-star-fill text-warning ms-1"></i>
                        @endif
                    </a>
                    <button type="button" class="btn-close btn-close-sm" style="font-size: 10px;" 
                            onclick="deleteFilter({{ $filter->id }})"></button>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function saveCurrentFilter() {
    const name = prompt('{{ __("Enter a name for this filter:") }}');
    if (!name) return;

    const form = document.getElementById('advancedFilterForm');
    const formData = new FormData(form);
    const criteria = {};

    // Build criteria object
    formData.forEach((value, key) => {
        if (key.includes('[]')) {
            const cleanKey = key.replace('[]', '');
            if (!criteria[cleanKey]) criteria[cleanKey] = [];
            criteria[cleanKey].push(value);
        } else {
            criteria[key] = value;
        }
    });

    // Send AJAX request to save filter
    fetch('{{ url("/" . app()->getLocale() . "/feed/filters") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: name,
            criteria: criteria
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('{{ __("Filter saved successfully!") }}');
            window.location.reload();
        }
    });
}

function deleteFilter(filterId) {
    if (!confirm('{{ __("Delete this filter?") }}')) return;

    fetch('{{ url("/" . app()->getLocale() . "/feed/filters") }}/' + filterId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
}
</script>
