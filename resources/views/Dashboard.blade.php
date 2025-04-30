@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <header class="py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-4">
            <h2 class="h4 mb-0 d-none d-md-block">Tableau de bord</h2>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2">
            <i class="ri-add-line"></i>
            <span>Nouvel objectif</span>
        </button>
    </header>

    <div class="pt-4">
        <!-- Welcome Section -->
        <div class="mb-5">
            <h1 class="h2">Bonjour, Thomas 👋</h1>
            <p class="text-muted mb-0">Vous avez 3 objectifs en cours et 2 tâches pour aujourd'hui.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="text-muted small mb-0">Objectifs actifs</h3>
                            <div class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-blue-100 text-primary">
                                <i class="ri-flag-line"></i>
                            </div>
                        </div>
                        <p class="h3 fw-bold mb-1">7</p>
                        <div class="d-flex align-items-center small">
                            <span class="text-success d-flex align-items-center me-2">
                                <i class="ri-arrow-up-line me-1"></i>
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
                            <div class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-green-100 text-success">
                                <i class="ri-check-double-line"></i>
                            </div>
                        </div>
                        <p class="h3 fw-bold mb-1">12</p>
                        <div class="d-flex align-items-center small">
                            <span class="text-success d-flex align-items-center me-2">
                                <i class="ri-arrow-up-line me-1"></i>
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
                            <div class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-purple-100 text-secondary">
                                <i class="ri-medal-line"></i>
                            </div>
                        </div>
                        <p class="h3 fw-bold mb-1">8</p>
                        <div class="d-flex align-items-center small">
                            <span class="text-success d-flex align-items-center me-2">
                                <i class="ri-arrow-up-line me-1"></i>
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
                            <div class="w-8 h-8 d-flex align-items-center justify-content-center rounded-circle bg-yellow-100 text-warning">
                                <i class="ri-bar-chart-line"></i>
                            </div>
                        </div>
                        <p class="h3 fw-bold mb-1">68%</p>
                        <div class="d-flex align-items-center small">
                            <span class="text-success d-flex align-items-center me-2">
                                <i class="ri-arrow-up-line me-1"></i>
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
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="w-6 h-6 d-flex align-items-center justify-content-center rounded-circle bg-blue-100 text-primary me-3">
                                        <i class="ri-run-line"></i>
                                    </div>
                                    <h3 class="h6 mb-0">Courir un semi-marathon</h3>
                                </div>
                                <span class="text-muted small">75%</span>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-primary" style="width: 75%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="w-6 h-6 d-flex align-items-center justify-content-center rounded-circle bg-purple-100 text-secondary me-3">
                                        <i class="ri-book-open-line"></i>
                                    </div>
                                    <h3 class="h6 mb-0">Apprendre l'espagnol</h3>
                                </div>
                                <span class="text-muted small">45%</span>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-secondary" style="width: 45%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="w-6 h-6 d-flex align-items-center justify-content-center rounded-circle bg-green-100 text-success me-3">
                                        <i class="ri-briefcase-line"></i>
                                    </div>
                                    <h3 class="h6 mb-0">Obtenir une promotion</h3>
                                </div>
                                <span class="text-muted small">60%</span>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-success" style="width: 60%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="w-6 h-6 d-flex align-items-center justify-content-center rounded-circle bg-yellow-100 text-warning me-3">
                                        <i class="ri-home-line"></i>
                                    </div>
                                    <h3 class="h6 mb-0">Économiser pour un appartement</h3>
                                </div>
                                <span class="text-muted small">30%</span>
                            </div>
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-warning" style="width: 30%"></div>
                            </div>
                        </div>
                        
                        <button class="mt-4 text-primary font-medium d-flex align-items-center text-sm border-0 bg-transparent p-0">
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
                        <div class="mb-3 d-flex">
                            <input type="checkbox" class="form-check-input custom-checkbox mt-1 me-3">
                            <div>
                                <h3 class="h6 mb-0">Courir 5 km</h3>
                                <p class="text-muted small mb-1">Objectif: Courir un semi-marathon</p>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-time-line me-1"></i>
                                    <span>08:00 - 09:00</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 d-flex">
                            <input type="checkbox" class="form-check-input custom-checkbox mt-1 me-3">
                            <div>
                                <h3 class="h6 mb-0">Leçon d'espagnol</h3>
                                <p class="text-muted small mb-1">Objectif: Apprendre l'espagnol</p>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-time-line me-1"></i>
                                    <span>12:30 - 13:30</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 d-flex">
                            <input type="checkbox" checked class="form-check-input custom-checkbox mt-1 me-3">
                            <div>
                                <h3 class="h6 mb-0 text-decoration-line-through">Préparer présentation</h3>
                                <p class="text-muted small mb-1 text-decoration-line-through">Objectif: Obtenir une promotion</p>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-time-line me-1"></i>
                                    <span>10:00 - 11:30</span>
                                </div>
                            </div>
                        </div>
                        
                        <button class="mt-4 text-primary font-medium d-flex align-items-center text-sm border-0 bg-transparent p-0">
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