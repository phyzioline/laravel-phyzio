@extends('web.help.layout')

@section('help_content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="font-weight-bold" style="color: #36415a;">
                {{ __('Search Results for') }}: "{{ $query }}"
            </h2>
            <p class="text-muted">{{ count($results) }} {{ __('articles found') }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(count($results) > 0)
                <div class="list-group shadow-sm">
                    @foreach($results as $result)
                        <a href="{{ route('help.article', ['category' => $result['category_slug'], 'article' => $result['article_slug']]) }}" class="list-group-item list-group-item-action p-4 border-left-0 border-right-0">
                            <div class="d-flex w-100 justify-content-between mb-2">
                                <h5 class="mb-1 font-weight-bold text-dark">{{ $result['title'] }}</h5>
                                <small class="text-muted"><i class="las la-folder mr-1"></i> {{ $result['category_title'] }}</small>
                            </div>
                            <p class="mb-1 text-muted">{{ $result['excerpt'] }}...</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('web/assets/images/no-data.svg') }}" alt="No Results" class="mb-4" style="max-width: 200px; opacity: 0.5;">
                    <h4 class="text-muted mb-3">{{ __('No results found') }}</h4>
                    <p class="text-muted mb-4">{{ __('Try adjusting your search terms or browse by category.') }}</p>
                    <a href="{{ route('help.index') }}" class="btn btn-primary px-4 rounded-pill" style="background-color: #02767F; border-color: #02767F;">
                        {{ __('Browse Categories') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
