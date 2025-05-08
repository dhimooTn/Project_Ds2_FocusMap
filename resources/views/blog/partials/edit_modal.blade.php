<div class="modal-body">
    <form id="editJournalForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="edit-title" class="form-label">Title</label>
            <input type="text" class="form-control" id="edit-title" name="title" value="{{ $journal->title }}" required>
        </div>
        <div class="mb-3">
            <label for="edit-content" class="form-label">Content</label>
            <textarea class="form-control" id="edit-content" name="content" rows="8" required>{{ $journal->content }}</textarea>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="edit-goal" class="form-label">Related Goal</label>
                <select class="form-select" id="edit-goal" name="goal_id">
                    <option value="">No goal</option>
                    @foreach($goals as $goal)
                    <option value="{{ $goal->id }}" {{ $journal->goal_id == $goal->id ? 'selected' : '' }}>{{ $goal->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="edit-motivation" class="form-label">Motivation</label>
                <select class="form-select" id="edit-motivation" name="motivation">
                    <option value="">Select motivation</option>
                    @foreach($motivations as $motivation)
                    <option value="{{ $motivation }}" {{ $journal->motivation == $motivation ? 'selected' : '' }}>{{ ucfirst($motivation) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="edit-tags" class="form-label">Tags (comma separated)</label>
            <input type="text" class="form-control" id="edit-tags" name="tags" value="{{ $journal->tags }}">
        </div>
        <div class="mb-3">
            <label for="edit-image" class="form-label">Image</label>
            <input type="file" class="form-control" id="edit-image" name="image">
            @if($journal->hasMedia('journal-images'))
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remove-image" name="remove_image" value="1">
                <label class="form-check-label" for="remove-image">Remove current image</label>
                <div class="mt-2">
                    <img src="{{ $journal->getFirstMediaUrl('journal-images') }}" alt="Current image" class="img-thumbnail" style="max-height: 150px;">
                </div>
            </div>
            @endif
        </div>
    </form>
</div>