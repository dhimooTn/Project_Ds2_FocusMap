<div class="journal-entry">
    <div class="d-flex justify-content-between mb-4">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <small class="text-muted">
                {{ $journal->created_at->format('d M Y • H:i') }}
            </small>
            @if($journal->goal)
            <span class="badge bg-blue-100 text-blue-800">{{ $journal->goal->title }}</span>
            @endif
            @if($journal->motivation)
            <span class="badge bg-green-100 text-green-800">{{ $journal->motivation }}</span>
            @endif
        </div>
    </div>
    
    <h2 class="mb-4">{{ $journal->title }}</h2>
    
    <div class="content mb-4">
        {!! nl2br(e($journal->content)) !!}
    </div>
    
    @if($journal->tags)
    <div class="tags mb-4">
        <h6 class="text-muted mb-2">Tags :</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach(array_filter(explode(',', $journal->tags)) as $tag)
            <span class="badge bg-light text-dark">#{{ trim($tag) }}</span>
            @endforeach
        </div>
    </div>
    @endif
    
    @if($journal->hasMedia('journal-images'))
    <div class="journal-image mb-4">
        <img src="{{ $journal->getFirstMediaUrl('journal-images') }}" alt="Journal image" class="img-fluid rounded">
    </div>
    @endif
</div>