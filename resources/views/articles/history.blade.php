<aside class="mt-6">
    @foreach ($history as $year => $months)
    <h4 class="mt-2">{{ $year }}</h4>
    @foreach ($months as $month)
    <div>
        <a href="{{ $month->url }}" class="mt-1 mr-1 inline-flex items-center text-xs font-medium leading-4 capitalize">{{ $month->published_month->isoFormat('MMMM') }} ({{ $month->articles_count }})</a>
    </div>
    @endforeach
    @endforeach
</aside>
