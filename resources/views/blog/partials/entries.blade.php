@foreach($journals as $journal)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
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
                <h5 class="card-title">{{ $journal->title }}</h5>
            </div>
            <div>
                <button class="btn btn-sm btn-light me-1" data-bs-toggle="modal" data-bs-target="#editEntryModal" data-id="{{ $journal->id }}">
                    <i class="ri-edit-line"></i>
                </button>
                <form action="{{ route('blog.destroy', $journal->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light" onclick="return confirm('Are you sure?')">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </form>
            </div>
        </div>
        <p class="card-text text-muted mb-3">{{ Str::limit($journal->content, 300) }}</p>
        <div class="d-flex justify-content-between align-items-center">
            @if($journal->tags)
            <div class="d-flex gap-2 flex-wrap">
                @foreach(array_filter(explode(',', $journal->tags)) as $tag)
                <span class="badge bg-light text-dark">#{{ trim($tag) }}</span>
                @endforeach
            </div>
            @endif
            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#viewEntryModal" data-id="{{ $journal->id }}">Read More</a>
        </div>
    </div>
</div>
@endforeach

{{ $journals->links() }}