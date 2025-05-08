@extends('layouts.app')

@section('content')
<div class="d-flex flex-column vh-100">
    <!-- Header -->
    <header class="bg-white shadow-sm d-md-none">
        <div class="container-fluid py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h1 class="h4 mb-0 text-primary fw-bold font-pacifico">FocusMap</h1>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle" type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item hover-bg-white-10" href="#">Nouveau message</a></li>
                            <li><a class="dropdown-item hover-bg-white-10" href="#">Objectif terminé</a></li>
                            <li><a class="dropdown-item hover-bg-white-10" href="#">Rappel d'échéance</a></li>
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
                            <li><a class="dropdown-item hover-bg-white-10" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item hover-bg-white-10" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item hover-bg-white-10" href="{{ route('login.logout') }}" 
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
    <    <div class="d-flex flex-grow-1">
        <!-- Content Area -->
        <main class="flex-grow-1 p-4 overflow-auto main-content">
            <div class="container-fluid">
                <!-- Filters and Actions -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="h4 mb-0 font-pacifico text-primary">Mes Objectifs</h2>
                        <p class="text-muted small mb-0">{{ now()->translatedFormat('l, j F Y') }}</p>
                    </div>
                    <div class="d-flex">
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-secondary dropdown-toggle rounded-pill" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-sort me-1"></i> Trier par
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item hover-bg-white-10" href="#">Date d'échéance</a></li>
                                <li><a class="dropdown-item hover-bg-white-10" href="#">Progression</a></li>
                                <li><a class="dropdown-item hover-bg-white-10" href="#">Date de création</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createGoalModal">
                            <i class="fas fa-plus me-1"></i> Nouvel objectif
                        </button>
                    </div>
                </div>

                <!-- Objectives Grid -->
                <div class="row g-4">
                    @foreach($goals as $goal)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center bg-blue-100">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-bullseye"></i>
                                        </div>
                                        <h5 class="mb-0 text-primary">{{ $goal->title }}</h5>
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
                                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($goal->location, 20) }}</small>
                                        @endif
                                    </div>
                                    @if($goal->description)
                                        <p class="card-text mb-3">{{ Str::limit($goal->description, 100) }}</p>
                                    @endif
                                    <h6 class="card-subtitle mb-2 text-muted">Tâches:</h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($goal->tasks as $task)
                                            <li class="mb-2 d-flex align-items-center">
                                                <input type="checkbox" 
                                                       class="form-check-input me-2 task-checkbox custom-checkbox" 
                                                       data-task-id="{{ $task->id }}"
                                                       {{ $task->status === 'completed' ? 'checked' : '' }}>
                                                <span class="{{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                                    {{ $task->title }}
                                                    @if($task->due_date)
                                                        <small class="text-muted d-block">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</small>
                                                    @endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="far fa-edit"></i></a>
                                        <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Voulez-vous vraiment supprimer cet objectif ?');"><i class="far fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                    <button class="btn btn-link text-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#goalDetailsModal{{ $goal->id }}">Voir les détails</button>
                                </div>
                            </div>
                        </div>

                        <!-- Goal Details Modal -->
                        <div class="modal fade" id="goalDetailsModal{{ $goal->id }}" tabindex="-1" aria-labelledby="goalDetailsModalLabel{{ $goal->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content border-0 shadow bg-blue-100">
                                    <div class="modal-header border-0 pb-0 bg-purple-100">
                                        <h5 class="modal-title h5 font-pacifico text-primary" id="goalDetailsModalLabel{{ $goal->id }}">{{ $goal->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Description:</strong></p>
                                                <p>{{ $goal->description ?? 'Aucune description' }}</p>
                                                <p><strong>Visibilité:</strong> {{ $goal->visibility === 'public' ? 'Public' : 'Privé' }}</p>
                                                @if($goal->location)
                                                    <p><strong>Localisation:</strong> {{ $goal->location }}</p>
                                                    <p><strong>Coordonnées:</strong> {{ $goal->lat }}, {{ $goal->lng }}</p>
                                                @endif
                                                <p><strong>Progression:</strong> {{ $goal->progress }}%</p>
                                                <p><strong>Statut:</strong> {{ ucfirst($goal->status) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                @if($goal->location)
                                                    <div id="map{{ $goal->id }}" style="height: 200px; width: 100%; border-radius: 8px; margin-bottom: 10px; border: 1px solid #dee2e6;"></div>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h6 class="mb-3 text-primary">Tâches associées</h6>
                                        <div class="tasks-container">
                                            @foreach($goal->tasks as $task)
                                                <div class="task-item mb-3 p-3 border rounded bg-white shadow-sm hover-bg-white-10">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <input type="checkbox" 
                                                               class="form-check-input me-2 task-checkbox-modal custom-checkbox" 
                                                               data-task-id="{{ $task->id }}"
                                                               {{ $task->status === 'completed' ? 'checked' : '' }}>
                                                        <span class="{{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                                            {{ $task->title }}
                                                        </span>
                                                    </div>
                                                    @if($task->description)
                                                        <p class="small text-muted mb-2">{{ $task->description }}</p>
                                                    @endif
                                                    <div class="d-flex justify-content-between">
                                                        @if($task->due_date)
                                                            <small class="text-muted">Échéance: {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</small>
                                                        @endif
                                                        <small class="text-muted">Priorité: {{ ucfirst($task->priority) }}</small>
                                                    </div>
                                                    @if($task->calendarEvent)
                                                        <div class="mt-2 small text-muted">
                                                            <p>Événement: {{ $task->calendarEvent->title }}</p>
                                                            <p>Début: {{ \Carbon\Carbon::parse($task->calendarEvent->start_time)->format('d/m/Y H:i') }}</p>
                                                            <p>Fin: {{ \Carbon\Carbon::parse($task->calendarEvent->end_time)->format('d/m/Y H:i') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0 bg-purple-100">
                                        <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-outline-primary rounded-pill hover-bg-white-10"><i class="far fa-edit me-1"></i> Modifier l'objectif</a>
                                        <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger rounded-pill hover-bg-white-10" onclick="return confirm('Voulez-vous vraiment supprimer cet objectif ?');"><i class="far fa-trash-alt me-1"></i> Supprimer l'objectif</button>
                                        </form>
                                        <button type="button" class="btn btn-outline-secondary rounded-pill hover-bg-white-10" data-bs-dismiss="modal">Fermer</button>
                                    </div>
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
        <div class="modal-content border-0 shadow bg-blue-100">
            <div class="modal-header border-0 pb-0 bg-purple-100">
                <h5 class="modal-title h5 font-pacifico text-primary" id="createGoalModalLabel">Créer un nouvel objectif</h5>
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
                                        <input type="text" class="form-control rounded" id="lng" name="lngittiude" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tasks Section -->
                    <div class="mb-4">
                        <h6 class="h6 mb-3 d-flex align-items-center text-primary">
                            <i class="fas fa-tasks me-2"></i> Tâches associées
                        </h6>
                        <div id="tasks-container">
                            <div class="task-item mb-3 p-3 border rounded bg-white shadow-sm">
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
                        <button type="button" id="add-task" class="btn btn-sm btn-outline-secondary rounded-pill hover-bg-white-10">
                            <i class="fas fa-plus me-1"></i> Ajouter une tâche
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 bg-purple-100">
                    <button type="button" class="btn btn-outline-secondary rounded-pill hover-bg-white-10" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-pill hover-bg-white-10">Créer l'objectif</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Task Status Update -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle task checkbox changes in both main view and modal
    const checkboxes = document.querySelectorAll('.task-checkbox, .task-checkbox-modal');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const taskId = this.dataset.taskId;
            const newStatus = this.checked ? 'completed' : 'pending';

            fetch(`/tasks/${taskId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update all checkboxes with the same task ID
                    document.querySelectorAll(`.task-checkbox[data-task-id="${taskId}"], .task-checkbox-modal[data-task-id="${taskId}"]`).forEach(cb => {
                        cb.checked = newStatus === 'completed';
                    });
                    
                    // Update task text style
                    const taskSpans = document.querySelectorAll(`.task-checkbox[data-task-id="${taskId}"] + span, .task-checkbox-modal[data-task-id="${taskId}"] + span`);
                    taskSpans.forEach(span => {
                        span.classList.toggle('text-decoration-line-through', newStatus === 'completed');
                        span.classList.toggle('text-muted', newStatus === 'completed');
                    });

                    // Update progress circle
                    const card = this.closest('.card') || this.closest('.modal-content').querySelector('.card');
                    if (card) {
                        const progressCircle = card.querySelector('.progress-ring-circle');
                        const progressText = card.querySelector('.progress-ring + div');
                        if (progressCircle && progressText) {
                            progressCircle.setAttribute('stroke-dashoffset', 150.8 - (150.8 * data.progress / 100));
                            progressText.textContent = `${data.progress}%`;
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked; // Revert checkbox on error
            });
        });
    });

    // Handle task addition
    const addTaskBtn = document.getElementById('add-task');
    const tasksContainer = document.getElementById('tasks-container');
    let taskIndex = 1;

    addTaskBtn.addEventListener('click', () => {
        const taskHtml = `
            <div class="task-item mb-3 p-3 border rounded bg-white shadow-sm">
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <input type="text" class="form-control rounded" name="tasks[${taskIndex}][title]" placeholder="Titre de la tâche" required>
                    </div>
                    <div class="col-md-6">
                        <input type="date" class="form-control rounded" name="tasks[${taskIndex}][due_date]">
                    </div>
                </div>
                <textarea class="form-control rounded mb-2" name="tasks[${taskIndex}][description]" placeholder="Description" rows="2"></textarea>
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select class="form-select rounded" name="tasks[${taskIndex}][priority]">
                            <option value="low">Faible priorité</option>
                            <option value="medium" selected>Priorité moyenne</option>
                            <option value="high">Haute priorité</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input custom-checkbox" type="checkbox" name="tasks[${taskIndex}][has_event]" id="task${taskIndex}HasEvent">
                            <label class="form-check-label small" for="task${taskIndex}HasEvent">Ajouter un événement</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill remove-task float-end">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </div>
                </div>
                <div class="event-fields mt-2" style="display: none;">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="datetime-local" class="form-control rounded" name="tasks[${taskIndex}][event_start]">
                        </div>
                        <div class="col-md-6">
                            < emperor-local" class="form-control rounded" name="tasks[${taskIndex}][event_end]">
                        </div>
                    </div>
                </div>
            </div>`;
        tasksContainer.insertAdjacentHTML('beforeend', taskHtml);
        taskIndex++;
        bindRemoveTaskEvents();
        bindEventToggleEvents();
    });

    function bindRemoveTaskEvents() {
        document.querySelectorAll('.remove-task').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.task-item').remove();
            });
        });
    }

    function bindEventToggleEvents() {
        document.querySelectorAll('.form-check-input[name$="[has_event]"]').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const eventFields = checkbox.closest('.task-item').querySelector('.event-fields');
                eventFields.style.display = checkbox.checked ? 'block' : 'none';
            });
        });
    }

    bindRemoveTaskEvents();
    bindEventToggleEvents();
});
</script>
@endsection