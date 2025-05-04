@extends('layouts.app')

@section('content')
<div class="d-flex flex-column vh-100">
    <!-- Header -->
    <header class="bg-white shadow-sm d-md-none">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h1 class="h4 mb-0 text-primary fw-bold">FocusMap</h1>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle" type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#">Nouveau message</a></li>
                            <li><a class="dropdown-item" href="#">Objectif terminé</a></li>
                            <li><a class="dropdown-item" href="#">Rappel d'échéance</a></li>
                        </ul>
                    </div>
                    <div class="dropdown ms-3">
                        <button class="btn btn-light rounded-pill d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="ms-2 d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('login.logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </a>
                                <form id="logout-form" action="{{ route('login.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="d-flex flex-grow-1">
        <!-- Content Area -->
        <main class="flex-grow-1 p-4 overflow-auto">
            <div class="container-fluid">
                <!-- Filters and Actions -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="h4 mb-0">Mes Objectifs</h2>
                        <p class="text-muted small mb-0">{{ now()->translatedFormat('l, j F Y') }}</p>
                    </div>
                    <div class="d-flex">
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-sort me-1"></i> Trier par
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="#">Date d'échéance</a></li>
                                <li><a class="dropdown-item" href="#">Progression</a></li>
                                <li><a class="dropdown-item" href="#">Date de création</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGoalModal">
                            <i class="fas fa-plus me-1"></i> Nouvel objectif
                        </button>
                    </div>
                </div>

                <!-- Objectives Grid -->
                <div class="row g-4">
                    @foreach($goals as $goal)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-bullseye"></i>
                                        </div>
                                        <h5 class="mb-0">{{ $goal->title }}</h5>
                                    </div>
                                    <div class="position-relative" style="width: 56px; height: 56px;">
                                        <svg class="progress-ring" width="56" height="56">
                                            <circle class="text-light" stroke="currentColor" stroke-width="4" fill="transparent" r="24" cx="28" cy="28"/>
                                            <circle class="progress-ring-circle text-primary" stroke="currentColor" stroke-width="4" fill="transparent" r="24" cx="28" cy="28" 
                                                    stroke-dasharray="150.8" stroke-dashoffset="{{ 150.8 - (150.8 * $goal->progress / 100) }}"/>
                                        </svg>
                                        <div class="position-absolute top-50 start-50 translate-middle fw-bold small">{{ $goal->progress }}%</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                                            {{ $goal->visibility === 'public' ? 'Public' : 'Privé' }}
                                        </span>
                                        @if($goal->location)
                                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $goal->location }}</small>
                                        @endif
                                    </div>
                                    @if($goal->description)
                                        <p class="card-text mb-3">{{ $goal->description }}</p>
                                    @endif
                                    <h6 class="card-subtitle mb-2 text-muted">Tâches:</h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($goal->tasks as $task)
                                            <li class="mb-2 d-flex align-items-center">
                                                <input type="checkbox" 
                                                       class="form-check-input me-2 task-checkbox" 
                                                       data-task-id="{{ $task->id }}"
                                                       {{ $task->status === 'completed' ? 'checked' : '' }}>
                                                <span class="{{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                                    {{ $task->title }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-sm btn-outline-secondary"><i class="far fa-edit"></i></a>
                                        <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="far fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                    <a href="{{ route('goals.show', $goal->id) }}" class="text-primary text-decoration-none">Voir les détails</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <nav class="mt-4 d-flex justify-content-center">
                    {{ $goals->links() }}
                </nav>
            </div>
        </main>
    </div>
</div>

<!-- Create Goal Modal -->
<div class="modal fade" id="createGoalModal" tabindex="-1" aria-labelledby="createGoalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title h5" id="createGoalModalLabel">Créer un nouvel objectif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="goalForm" action="{{ route('goals.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="title" class="form-label small text-muted mb-1">Titre de l'objectif</label>
                                <input type="text" class="form-control rounded" id="title" name="title" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label small text-muted mb-1">Description</label>
                                <textarea class="form-control rounded" id="description" name="description" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="visibility" class="form-label small text-muted mb-1">Visibilité</label>
                                <select class="form-select rounded" id="visibility" name="visibility">
                                    <option value="private">Privé</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label small text-muted mb-1">Localisation</label>
                                <div id="map" style="height: 200px; width: 100%; border-radius: 8px; margin-bottom: 10px; border: 1px solid #dee2e6;"></div>
                                <input type="text" class="form-control rounded mb-2" id="location" name="location" placeholder="Adresse">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control rounded" id="lat" name="lat" placeholder="Latitude" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control rounded" id="lng" name="lng" placeholder="Longitude" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tasks Section -->
                    <div class="mb-4">
                        <h6 class="h6 mb-3 d-flex align-items-center">
                            <i class="fas fa-tasks me-2"></i>
                            Tâches associées
                        </h6>
                        <div id="tasks-container">
                            <div class="task-item mb-3 p-3 border rounded bg-white">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control rounded" name="tasks[0][title]" placeholder="Titre de la tâche" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control rounded" name="tasks[0][due_date]">
                                    </div>
                                </div>
                                <textarea class="form-control rounded mb-2" name="tasks[0][description]" placeholder="Description" rows="2"></textarea>
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-4">
                                        <select class="form-select rounded" name="tasks[0][priority]">
                                            <option value="low">Faible priorité</option>
                                            <option value="medium" selected>Priorité moyenne</option>
                                            <option value="high">Haute priorité</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input custom-checkbox" type="checkbox" name="tasks[0][has_event]" id="task0HasEvent">
                                            <label class="form-check-label small" for="task0HasEvent">Ajouter un événement</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill remove-task float-end">
                                            <i class="fas fa-trash me-1"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Event Fields (hidden by default) -->
                                <div class="event-fields mt-2" style="display: none;">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <input type="datetime-local" class="form-control rounded" name="tasks[0][event_start]">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="datetime-local" class="form-control rounded" name="tasks[0][event_end]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-task" class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="fas fa-plus me-1"></i> Ajouter une tâche
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-pill">Créer l'objectif</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Task checkbox handler
        $('.task-checkbox').change(function() {
            const taskId = $(this).data('task-id');
            const isDone = $(this).is(':checked');
            
            $.ajax({
                url: '/tasks/' + taskId,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: isDone ? 'completed' : 'pending'
                },
                success: function(response) {
                    // Update the UI
                    const checkbox = $('.task-checkbox[data-task-id="' + taskId + '"]');
                    const taskText = checkbox.next('span');
                    
                    if (isDone) {
                        taskText.addClass('text-decoration-line-through text-muted');
                    } else {
                        taskText.removeClass('text-decoration-line-through text-muted');
                    }
                    
                    // Update progress circle
                    const card = checkbox.closest('.card');
                    const progressCircle = card.find('.progress-ring-circle');
                    const progressText = card.find('.translate-middle');
                    
                    progressCircle.attr('stroke-dashoffset', 150.8 - (150.8 * response.progress / 100));
                    progressText.text(response.progress + '%');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    // Revert checkbox if error
                    $(this).prop('checked', !isDone);
                }
            });
        });
        
        // Add task functionality in modal
        let taskCount = 1;
        $('#add-task').click(function() {
            const newTaskHtml = `
                <div class="task-item mb-3 p-3 border rounded bg-white">
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control rounded" name="tasks[${taskCount}][title]" placeholder="Titre de la tâche" required>
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control rounded" name="tasks[${taskCount}][due_date]">
                        </div>
                    </div>
                    <textarea class="form-control rounded mb-2" name="tasks[${taskCount}][description]" placeholder="Description" rows="2"></textarea>
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <select class="form-select rounded" name="tasks[${taskCount}][priority]">
                                <option value="low">Faible priorité</option>
                                <option value="medium" selected>Priorité moyenne</option>
                                <option value="high">Haute priorité</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" name="tasks[${taskCount}][has_event]" id="task${taskCount}HasEvent">
                                <label class="form-check-label small" for="task${taskCount}HasEvent">Ajouter un événement</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill remove-task float-end">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                    
                    <!-- Event Fields (hidden by default) -->
                    <div class="event-fields mt-2" style="display: none;">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="datetime-local" class="form-control rounded" name="tasks[${taskCount}][event_start]">
                            </div>
                            <div class="col-md-6">
                                <input type="datetime-local" class="form-control rounded" name="tasks[${taskCount}][event_end]">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#tasks-container').append(newTaskHtml);
            taskCount++;
        });
        
        // Remove task
        $(document).on('click', '.remove-task', function() {
            $(this).closest('.task-item').remove();
        });
        
        // Toggle event fields
        $(document).on('change', '[name$="[has_event]"]', function() {
            const eventFields = $(this).closest('.task-item').find('.event-fields');
            if ($(this).is(':checked')) {
                eventFields.show();
            } else {
                eventFields.hide();
            }
        });
    });
</script>
@endsection