@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <header class="py-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-4">
                <h2 class="h4 mb-0 d-none d-md-block">Tableau de bord</h2>
            </div>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createGoalModal">
                <i class="ri-add-line"></i>
                <span>Nouvel objectif</span>
            </button>
        </header>

        <!-- Rest of your existing content... -->

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
                            <i class="ri-list-check-2 me-2"></i>
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
                                            <i class="ri-delete-bin-line me-1"></i> Supprimer
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
                            <i class="ri-add-line me-1"></i> Ajouter une tâche
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
        <div class="pt-4">
            <!-- Welcome Section -->
            <div class="mb-5">
                <h1 class="h2">Bonjour, {{ $user->name }} 👋</h1>
                <p class="text-muted mb-0">Vous avez {{ $activeGoals->count() }} objectifs en cours et
                    {{ $todaysTasks->count() }} tâches pour aujourd'hui.</p>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="text-muted small mb-0">Objectifs actifs</h3>
                                <div
                                    class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-blue-100 text-primary">
                                    <i class="ri-flag-line"></i>
                                </div>
                            </div>
                            <p class="h3 fw-bold mb-1">{{ $activeGoals->count() }}</p>
                            <div class="d-flex align-items-center small">
                                <span class="text-success d-flex align-items-center me-2">
                                    <i class="ri-arrow-up-line me-1"></i>
                                    <!-- You would need to calculate this difference in the controller -->
                                    +2
                                </span>
                                <span class="text-muted">depuis le mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="text-muted small mb-0">Objectifs complétés</h3>
                                <div
                                    class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-green-100 text-success">
                                    <i class="ri-check-double-line"></i>
                                </div>
                            </div>
                            <p class="h3 fw-bold mb-1">{{ $completedGoals->count() }}</p>
                            <div class="d-flex align-items-center small">
                                <span class="text-success d-flex align-items-center me-2">
                                    <i class="ri-arrow-up-line me-1"></i>
                                    <!-- You would need to calculate this difference in the controller -->
                                    +5
                                </span>
                                <span class="text-muted">depuis le mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="text-muted small mb-0">Badges gagnés</h3>
                                <div
                                    class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-purple-100 text-secondary">
                                    <i class="ri-medal-line"></i>
                                </div>
                            </div>
                            <p class="h3 fw-bold mb-1">{{ $badges }}</p>
                            <div class="d-flex align-items-center small">
                                <span class="text-success d-flex align-items-center me-2">
                                    <i class="ri-arrow-up-line me-1"></i>
                                    <!-- You would need to calculate this difference in the controller -->
                                    +3
                                </span>
                                <span class="text-muted">depuis le mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="text-muted small mb-0">Progression moyenne</h3>
                                <div
                                    class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-yellow-100 text-warning">
                                    <i class="ri-bar-chart-line"></i>
                                </div>
                            </div>
                            <p class="h3 fw-bold mb-1">{{ round($averageProgress) }}%</p>
                            <div class="d-flex align-items-center small">
                                <span class="text-success d-flex align-items-center me-2">
                                    <i class="ri-arrow-up-line me-1"></i>
                                    <!-- You would need to calculate this difference in the controller -->
                                    +12%
                                </span>
                                <span class="text-muted">depuis le mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Sections -->
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Objectives Progress -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h2 class="h5 mb-0">Objectifs en cours</h2>
                            </div>

                            <!-- Progress items -->
                            @foreach($activeGoals as $goal)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="w-6 h-6 d-flex align-items-center justify-content-center rounded-circle bg-blue-100 text-primary me-3">
                                                <i class="ri-flag-line"></i>
                                            </div>
                                            <h3 class="h6 mb-0">{{ $goal->title }}</h3>
                                        </div>
                                        <span class="text-muted small">{{ $goal->progress }}%</span>
                                    </div>
                                    <div class="progress progress-thin">
                                        <div class="progress-bar bg-primary" style="width: {{ $goal->progress }}%"></div>
                                    </div>
                                </div>
                            @endforeach

                            <button
                                class="mt-4 text-primary font-medium d-flex align-items-center text-sm border-0 bg-transparent p-0">
                                <span>Voir tous les objectifs</span>
                                <div class="w-4 h-4 d-flex align-items-center justify-content-center ms-1">
                                    <i class="ri-arrow-right-line"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Today's Tasks -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h2 class="h5 mb-0">Tâches du jour</h2>
                                <span class="text-muted small">{{ date('d F Y') }}</span>
                            </div>

                            <!-- Task items -->
                            @foreach($todaysTasks as $task)
                                <div class="mb-3 d-flex">
                                    <input type="checkbox" class="form-check-input custom-checkbox mt-1 me-3" {{ $task->completed ? 'checked' : '' }}>
                                    <div>
                                        <h3 class="h6 mb-0 {{ $task->completed ? 'text-decoration-line-through' : '' }}">
                                            {{ $task->title }}</h3>
                                        <p
                                            class="text-muted small mb-1 {{ $task->completed ? 'text-decoration-line-through' : '' }}">
                                            Objectif: {{ $task->goal->title ?? 'N/A' }}</p>
                                        @if($task->due_time)
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="ri-time-line me-1"></i>
                                                <span>{{ $task->due_time }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <button
                                class="mt-4 text-primary font-medium d-flex align-items-center text-sm border-0 bg-transparent p-0">
                                <span>Ajouter une tâche</span>
                                <div class="w-4 h-4 d-flex align-items-center justify-content-center ms-1">
                                    <i class="ri-add-line"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection