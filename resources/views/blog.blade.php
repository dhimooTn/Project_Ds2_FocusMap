@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 h-100">
    <div class="row g-0 h-100">
        <!-- Left Sidebar -->


        <!-- Main Blog Content -->
        <div class="col-md-9 p-4 h-100 overflow-auto bg-light">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Blog</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEntryModal">
                    <i class="ri-add-line me-2"></i>Nouvelle entrée
                </button>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button class="btn btn-primary btn-sm">
                            <i class="ri-time-line me-2"></i>Tous
                        </button>
                        <button class="btn btn-light btn-sm">
                            <i class="ri-calendar-line me-2"></i>Aujourd'hui
                        </button>
                        <button class="btn btn-light btn-sm">
                            <i class="ri-calendar-check-line me-2"></i>Cette semaine
                        </button>
                        <button class="btn btn-light btn-sm">
                            <i class="ri-calendar-event-line me-2"></i>Ce mois
                        </button>
                        <div class="ms-auto">
                            <button class="btn btn-light btn-sm">
                                <i class="ri-sort-desc me-2"></i>Plus récent
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blog Entries -->
            <div class="mb-4">
                <!-- Today's Entry -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <small class="text-muted me-3">Aujourd'hui, 4 Mai 2025 • 10:30</small>
                                    <span class="badge bg-blue-100 text-blue-800">Apprendre le piano</span>
                                </div>
                                <h5 class="card-title">Première leçon de piano réussie</h5>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-light me-1">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-light">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">J'ai eu ma première leçon de piano aujourd'hui et c'était incroyable ! Mon professeur, Monsieur Dupont, est très patient et pédagogue. J'ai appris les bases de la position des mains et quelques notes simples. Je suis très motivé pour continuer et pratiquer tous les jours comme prévu dans mon objectif.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark">#musique</span>
                                <span class="badge bg-light text-dark">#apprentissage</span>
                                <span class="badge bg-light text-dark">#motivation</span>
                            </div>
                            <a href="#" class="text-primary">Lire la suite</a>
                        </div>
                    </div>
                </div>

                <!-- Previous Entry 1 -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <small class="text-muted me-3">2 Mai 2025 • 18:45</small>
                                    <span class="badge bg-green-100 text-green-800">Courir un semi-marathon</span>
                                </div>
                                <h5 class="card-title">Séance d'entraînement intensive</h5>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-light me-1">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-light">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">Aujourd'hui j'ai réalisé ma séance d'entraînement la plus difficile jusqu'à présent : 8 km avec des intervalles à haute intensité. J'ai ressenti une fatigue importante mais aussi une grande satisfaction. Mes nouvelles chaussures de course sont parfaites et m'ont évité les douleurs aux genoux que j'avais avant. Je progresse bien vers mon objectif de courir un semi-marathon.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark">#course</span>
                                <span class="badge bg-light text-dark">#progrès</span>
                                <span class="badge bg-light text-dark">#endurance</span>
                            </div>
                            <a href="#" class="text-primary">Lire la suite</a>
                        </div>
                    </div>
                </div>

                <!-- Previous Entry 2 -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <small class="text-muted me-3">30 Avril 2025 • 09:15</small>
                                    <span class="badge bg-yellow-100 text-yellow-800">Apprendre la photographie</span>
                                </div>
                                <h5 class="card-title">Séance photo au parc</h5>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-light me-1">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-light">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">J'ai passé la matinée au Parc des Buttes-Chaumont pour pratiquer la photographie de paysage. J'ai essayé d'appliquer les techniques apprises dans mon cours en ligne sur la composition et l'exposition. Sur les 50 photos prises, j'en ai sélectionné 5 qui me semblent vraiment réussies. Je commence à mieux comprendre comment utiliser la profondeur de champ.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark">#photographie</span>
                                <span class="badge bg-light text-dark">#nature</span>
                                <span class="badge bg-light text-dark">#pratique</span>
                            </div>
                            <a href="#" class="text-primary">Lire la suite</a>
                        </div>
                    </div>
                </div>

                <!-- Previous Entry 3 -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <small class="text-muted me-3">28 Avril 2025 • 21:30</small>
                                    <span class="badge bg-red-100 text-red-800">Lire 20 livres cette année</span>
                                </div>
                                <h5 class="card-title">Terminé "L'Étranger" d'Albert Camus</h5>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-light me-1">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-light">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">Je viens de terminer "L'Étranger" d'Albert Camus, mon 6ème livre de l'année. Une œuvre puissante qui m'a fait réfléchir sur l'absurdité de l'existence et la place de l'individu dans la société. J'ai particulièrement apprécié le style d'écriture minimaliste mais percutant. C'est un livre que je recommande vivement, même si la lecture peut être déstabilisante au début.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark">#lecture</span>
                                <span class="badge bg-light text-dark">#philosophie</span>
                                <span class="badge bg-light text-dark">#réflexion</span>
                            </div>
                            <a href="#" class="text-primary">Lire la suite</a>
                        </div>
                    </div>
                </div>

                <!-- Previous Entry 4 -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <small class="text-muted me-3">25 Avril 2025 • 16:00</small>
                                    <span class="badge bg-purple-100 text-purple-800">Apprendre l'espagnol</span>
                                </div>
                                <h5 class="card-title">Premier échange linguistique</h5>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-light me-1">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-light">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">Aujourd'hui j'ai participé à mon premier échange linguistique avec Carmen, une étudiante espagnole. Nous avons parlé pendant 30 minutes en français et 30 minutes en espagnol. J'étais nerveux au début, mais elle a été très patiente. J'ai réussi à me présenter et à parler un peu de mes hobbies en espagnol. C'est encourageant de voir que je peux déjà communiquer malgré mes erreurs de grammaire.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark">#espagnol</span>
                                <span class="badge bg-light text-dark">#conversation</span>
                                <span class="badge bg-light text-dark">#échange</span>
                            </div>
                            <a href="#" class="text-primary">Lire la suite</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">8</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- New Entry Modal -->
<div class="modal fade" id="newEntryModal" tabindex="-1" aria-labelledby="newEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="newEntryModalLabel">Nouvelle entrée de blog</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="entry-title" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="entry-title" placeholder="Donnez un titre à votre entrée...">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="entry-date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="entry-date">
                        </div>
                        <div class="col-md-6">
                            <label for="entry-objective" class="form-label">Objectif associé</label>
                            <select class="form-select" id="entry-objective">
                                <option value="">Aucun objectif</option>
                                <option value="piano">Apprendre le piano</option>
                                <option value="marathon">Courir un semi-marathon</option>
                                <option value="photo">Apprendre la photographie</option>
                                <option value="books">Lire 20 livres cette année</option>
                                <option value="spanish">Apprendre l'espagnol</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="entry-content" class="form-label">Contenu</label>
                        <textarea class="form-control" id="entry-content" rows="8" placeholder="Partagez vos réflexions, progrès, difficultés..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                #motivation
                                <button class="btn btn-link p-0 ms-1 text-primary">
                                    <i class="ri-close-line"></i>
                                </button>
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                #progrès
                                <button class="btn btn-link p-0 ms-1 text-primary">
                                    <i class="ri-close-line"></i>
                                </button>
                            </span>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Ajouter un tag...">
                            <button class="btn btn-outline-secondary" type="button">Ajouter</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ajouter une image (optionnel)</label>
                        <div class="border-2 border-dashed rounded p-5 text-center">
                            <i class="ri-image-line fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-3">Glissez-déposez une image ici ou</p>
                            <button type="button" class="btn btn-light">Parcourir</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary">Publier</button>
            </div>
        </div>
    </div>
</div>
@endsection
