@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 h-100">
    <div class="row g-0 h-100">
        <!-- Main Blog Content -->
        <div class="col-md-9 p-4 h-100 overflow-auto bg-light">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Blog</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEntryModal">
                    <i class="ri-add-line me-2"></i>New Entry
                </button>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button class="btn filter-btn {{ !request()->has('filter') ? 'btn-primary' : 'btn-light' }}" 
                                data-filter="">
                            <i class="ri-time-line me-2"></i>All
                        </button>
                        <button class="btn filter-btn {{ request('filter') === 'today' ? 'btn-primary' : 'btn-light' }}" 
                                data-filter="today">
                            <i class="ri-calendar-line me-2"></i>Today
                        </button>
                        <button class="btn filter-btn {{ request('filter') === 'week' ? 'btn-primary' : 'btn-light' }}" 
                                data-filter="week">
                            <i class="ri-calendar-check-line me-2"></i>This Week
                        </button>
                        <button class="btn filter-btn {{ request('filter') === 'month' ? 'btn-primary' : 'btn-light' }}" 
                                data-filter="month">
                            <i class="ri-calendar-event-line me-2"></i>This Month
                        </button>
                        <div class="ms-auto">
                            <button class="btn btn-light">
                                <i class="ri-sort-desc me-2"></i>Newest
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Journal Entries Container -->
            <div id="journal-entries-container">
                @include('blog.partials.entries', ['journals' => $journals])
            </div>

            @if($journals->isEmpty())
            <div class="alert alert-info">
                You don't have any journal entries yet. Click "New Entry" to start.
            </div>
            @endif
        </div>
    </div>
</div>

<!-- New Entry Modal -->
<div class="modal fade" id="newEntryModal" tabindex="-1" aria-labelledby="newEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="newEntryModalLabel">New Journal Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="entry-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="entry-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="entry-content" class="form-label">Content</label>
                        <textarea class="form-control" id="entry-content" name="content" rows="8" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="entry-goal" class="form-label">Related Goal</label>
                            <select class="form-select" id="entry-goal" name="goal_id">
                                <option value="">No goal</option>
                                @foreach($goals as $goal)
                                <option value="{{ $goal->id }}">{{ $goal->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="entry-motivation" class="form-label">Motivation</label>
                            <select class="form-select" id="entry-motivation" name="motivation">
                                <option value="">Select motivation</option>
                                <option value="inspiration">Inspiration</option>
                                <option value="progrès">Progress</option>
                                <option value="défi">Challenge</option>
                                <option value="réussite">Success</option>
                                <option value="apprentissage">Learning</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="entry-tags" class="form-label">Tags (comma separated)</label>
                        <input type="text" class="form-control" id="entry-tags" name="tags">
                    </div>
                    <div class="mb-3">
                        <label for="entry-image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="entry-image" name="image">
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Entry Modal -->
<div class="modal fade" id="viewEntryModal" tabindex="-1" aria-labelledby="viewEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="viewEntryModalLabel">Entry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewEntryModalContent">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Entry Modal -->
<div class="modal fade" id="editEntryModal" tabindex="-1" aria-labelledby="editEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="editEntryModalLabel">Edit Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editJournalForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editEntryModalContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer bg-light justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter functionality
        $('.filter-btn').click(function(e) {
            e.preventDefault();
            const filter = $(this).data('filter');
            const container = $('#journal-entries-container');
            
            container.html('<div class="text-center py-5"><div class="spinner-border" role="status"></div></div>');
            
            $.ajax({
                url: "{{ route('blog.index') }}",
                type: "GET",
                data: { filter: filter },
                success: function(response) {
                    container.html(response.html);
                    $('.filter-btn').removeClass('btn-primary').addClass('btn-light');
                    $(`.filter-btn[data-filter="${filter}"]`).removeClass('btn-light').addClass('btn-primary');
                    
                    // Re-attach event handlers for the new content
                    attachModalEventHandlers();
                },
                error: function() {
                    container.html('<div class="alert alert-danger">Error loading entries</div>');
                }
            });
        });

        // Function to attach modal event handlers to dynamically added content
        function attachModalEventHandlers() {
            // View Modal handling
            $('.view-entry-btn').off('click').on('click', function() {
                var journalId = $(this).data('id');
                var modal = $('#viewEntryModal');
                
                modal.find('#viewEntryModalContent').html('<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>');
                
                $.ajax({
                    url: '/blog/' + journalId,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        modal.find('#viewEntryModalContent').html(response.html);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading entry:", error);
                        modal.find('#viewEntryModalContent').html('<div class="alert alert-danger">Error loading entry</div>');
                    }
                });
                
                modal.modal('show');
            });

            // Edit Modal handling
            $('.edit-entry-btn').off('click').on('click', function() {
                var journalId = $(this).data('id');
                var modal = $('#editEntryModal');
                
                modal.find('#editEntryModalContent').html('<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>');
                
                $.ajax({
                    url: '/blog/' + journalId + '/edit',
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        modal.find('#editEntryModalContent').html(response.html);
                        $('#editJournalForm').attr('action', '/blog/' + journalId);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading entry for edit:", error);
                        modal.find('#editEntryModalContent').html('<div class="alert alert-danger">Error loading entry</div>');
                    }
                });
                
                modal.modal('show');
            });
        }

        // Initial attachment of event handlers
        attachModalEventHandlers();
    });
</script>
@endpush
