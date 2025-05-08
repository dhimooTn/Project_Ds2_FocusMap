@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 h-100">
    <div class="row g-0 h-100">
        <!-- Main Content -->
        <div id="main-content" class="col-12 d-flex flex-column h-100">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="d-flex justify-content-between align-items-center px-4 py-3">
                    <div class="d-flex align-items-center">
                        <h2 class="h5 mb-0 me-3">Carte</h2>
                        <span class="text-muted small">{{ now()->format('l, j F Y') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="input-group me-3" style="width: 250px;">
                            <span class="input-group-text bg-light border-0"><i class="ri-search-line"></i></span>
                            <input type="text" id="search-location" class="form-control border-0 bg-light" placeholder="Rechercher un lieu...">
                        </div>
                        <button class="btn btn-light btn-sm me-2">
                            <i class="ri-notification-3-line"></i>
                        </button>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <span class="text-white small">{{ auth()->user()->initials ?? 'U' }}</span>
                                </div>
                                <div class="d-none d-md-block">
                                    <div class="small">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                                    <div class="text-muted x-small">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Paramètres</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Déconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Map Content -->
            <div class="flex-grow-1 position-relative">
                <!-- Filter Bar -->
                <div class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button id="all-filter" class="btn btn-primary btn-sm rounded-pill me-2">
                            <i class="ri-map-pin-line me-1"></i>
                            Tous ({{ isset($markers) ? count($markers) : 0 }})
                        </button>
                        <button id="active-filter" class="btn btn-outline-secondary btn-sm rounded-pill me-2">
                            <i class="ri-play-line text-primary me-1"></i>
                            Actifs ({{ isset($markers) ? count(array_filter($markers->toArray(), fn($m) => $m['status'] === 'active')) : 0 }})
                        </button>
                        <button id="completed-filter" class="btn btn-outline-secondary btn-sm rounded-pill me-2">
                            <i class="ri-check-line text-success me-1"></i>
                            Terminés ({{ isset($markers) ? count(array_filter($markers->toArray(), fn($m) => $m['status'] === 'completed')) : 0 }})
                        </button>
                        <button id="archived-filter" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="ri-archive-line text-warning me-1"></i>
                            Archivés ({{ isset($markers) ? count(array_filter($markers->toArray(), fn($m) => $m['status'] === 'archived')) : 0 }})
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <select id="sort-select" class="form-select form-select-sm me-2" style="width: 120px;">
                            <option value="date">Date</option>
                            <option value="proximity">Proximité</option>
                            <option value="progress">Progression</option>
                        </select>
                        <button id="add-marker-btn" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add-marker-modal">
                            <i class="ri-add-line me-1"></i>
                            Ajouter un objectif
                        </button>
                    </div>
                </div>

                <!-- Map Container -->
                <div id="map" style="height: calc(100vh - 150px); width: 100%;"></div>
                
                <!-- Map Popup -->
                <div id="marker-popup" class="position-absolute bg-white rounded shadow p-3" style="display: none; width: 300px; z-index: 1000; top: 20px; left: 20px;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 id="popup-title" class="mb-0"></h5>
                        <button id="close-popup" class="btn btn-sm btn-link text-muted">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center text-muted small mb-2">
                        <i id="popup-status-icon" class="me-2"></i>
                        <span id="popup-status"></span>
                    </div>
                    <div class="d-flex align-items-center text-muted small mb-3">
                        <i class="ri-map-pin-line text-muted me-2"></i>
                        <span id="popup-location"></span>
                    </div>
                    <div class="mb-3">
                        <div class="progress mb-1" style="height: 5px;">
                            <div id="popup-progress-bar" class="progress-bar bg-primary" style="width: 0%"></div>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span id="popup-progress">Progression: 0%</span>
                            <span id="popup-date"></span>
                        </div>
                    </div>
                    <p id="popup-description" class="small text-muted mb-3"></p>
                    <div class="d-flex gap-2">
                        <button id="view-details-btn" class="btn btn-primary btn-sm flex-grow-1">
                            Voir les détails
                        </button>
                        <button id="edit-marker-btn" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-pencil-line"></i>
                        </button>
                        <button id="delete-marker-btn" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Map Controls -->
                <div class="position-absolute d-flex flex-column gap-2" style="bottom: 20px; right: 20px; z-index: 500;">
                    <button id="zoom-in" class="btn btn-light btn-sm rounded-circle shadow-sm">
                        <i class="ri-add-line"></i>
                    </button>
                    <button id="zoom-out" class="btn btn-light btn-sm rounded-circle shadow-sm">
                        <i class="ri-subtract-line"></i>
                    </button>
                    <button id="center-map" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Cliquez pour centrer la carte, double-cliquez pour utiliser votre position">
                        <i class="ri-fullscreen-line"></i>
                    </button>
                </div>

                <!-- Map Legend -->
                <div class="position-absolute bg-white rounded shadow-sm p-2" style="bottom: 20px; left: 20px; z-index: 500;">
                    <h6 class="small fw-bold mb-2">Légende</h6>
                    <div class="d-flex flex-column gap-1">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                            <span class="small">Actifs</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                            <span class="small">Terminés</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                            <span class="small">Archivés</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Marker Modal -->
<div id="add-marker-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un nouvel objectif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-marker-form">
                    <div class="mb-3">
                        <label for="objective-title" class="form-label">Titre de l'objectif</label>
                        <input type="text" class="form-control" id="objective-title" placeholder="Ex: Apprendre le piano">
                    </div>
                    <div class="mb-3">
                        <label for="objective-status" class="form-label">Statut</label>
                        <select class="form-select" id="objective-status">
                            <option value="active">Actif</option>
                            <option value="completed">Terminé</option>
                            <option value="archived">Archivé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="objective-location" class="form-label">Lieu</label>
                        <input type="text" class="form-control" id="objective-location" placeholder="Ex: Parc de la Tête d'Or, Lyon">
                        <div class="form-text">Recherchez un lieu ou cliquez sur la carte pour sélectionner un emplacement</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coordonnées</label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="text" class="form-control" id="objective-lat" placeholder="Latitude">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="objective-lng" placeholder="Longitude">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="objective-description" class="form-label">Description</label>
                        <textarea class="form-control" id="objective-description" rows="3" placeholder="Décrivez votre objectif..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirm-add-marker" class="btn btn-primary">Ajouter l'objectif</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-confirm-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                    <i class="ri-delete-bin-line text-danger fs-4"></i>
                </div>
                <h5 class="mb-2">Supprimer cet objectif ?</h5>
                <p class="small text-muted mb-3">Cette action ne peut pas être annulée. L'objectif sera définitivement supprimé.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" id="confirm-delete" class="btn btn-danger btn-sm">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .custom-marker-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .active-marker {
        background-color: #0d6efd;
    }
    
    .completed-marker {
        background-color: #198754;
    }
    
    .archived-marker {
        background-color: #ffc107;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser la carte avec les données du contrôleur
    const defaultLatitude = {{ $defaultLatitude ?? 45.764043 }};
    const defaultLongitude = {{ $defaultLongitude ?? 4.835659 }};
    const defaultZoom = {{ $defaultZoom ?? 13 }};
    const markersData = @json($markers ?? collect([]));

    // Initialiser la carte
    const map = L.map('map').setView([defaultLatitude, defaultLongitude], defaultZoom);

    // Ajouter les tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Créer un groupe de marqueurs pour faciliter la gestion
    const markersGroup = L.layerGroup().addTo(map);
    
    // Créer les marqueurs
    const markers = {};
    let invalidMarkers = 0;
    
    if (markersData && markersData.length > 0) {
        markersData.forEach(data => {
            // Vérifier que les coordonnées sont valides
            const lat = parseFloat(data.latitude);
            const lng = parseFloat(data.longitude);
            
            if (isNaN(lat) || isNaN(lng) || 
                lat < -90 || lat > 90 || 
                lng < -180 || lng > 180) {
                console.warn(`Marqueur invalide: ${data.title} (${data.latitude}, ${data.longitude})`);
                invalidMarkers++;
                return; // Ignorer ce marqueur
            }
            
            const markerIcon = L.divIcon({
                className: `custom-marker-icon ${data.status}-marker`,
                html: `<i class="ri-${getStatusIcon(data.status)}-line"></i>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            const marker = L.marker([lat, lng], { 
                icon: markerIcon 
            }).addTo(markersGroup);

            marker.on('click', function() {
                showMarkerPopup(data);
            });

            markers[data.id] = marker;
        });
    }
    
    // Afficher une notification pour les marqueurs invalides
    if (invalidMarkers > 0) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-dismissible fade show position-absolute';
        alertDiv.style.top = '70px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '1000';
        alertDiv.innerHTML = `
            <strong>Attention!</strong> ${invalidMarkers} objectif(s) avec des coordonnées invalides.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto-fermeture après 5 secondes
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }

    // Contrôles de la carte
    document.getElementById('zoom-in').addEventListener('click', () => map.zoomIn());
    document.getElementById('zoom-out').addEventListener('click', () => map.zoomOut());
    document.getElementById('center-map').addEventListener('click', () => {
        map.flyTo([defaultLatitude, defaultLongitude], defaultZoom);
    });

    // Ajout de la géolocalisation
    document.getElementById('center-map').addEventListener('dblclick', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    map.flyTo([userLat, userLng], 15);
                },
                (error) => {
                    console.error('Erreur de géolocalisation:', error);
                }
            );
        }
    });

    // Filtres
    document.getElementById('all-filter').addEventListener('click', () => {
        markersGroup.clearLayers();
        
        if (markers && Object.keys(markers).length > 0) {
            Object.values(markers).forEach(marker => markersGroup.addLayer(marker));
        }
        updateActiveFilter('all-filter');
    });

    document.getElementById('active-filter').addEventListener('click', () => {
        markersGroup.clearLayers();
        
        if (markersData && markersData.length > 0) {
            markersData.filter(data => data.status === 'active')
                .forEach(data => {
                    if (markers[data.id]) markersGroup.addLayer(markers[data.id]);
                });
        }
        updateActiveFilter('active-filter');
    });

    document.getElementById('completed-filter').addEventListener('click', () => {
        markersGroup.clearLayers();
        
        if (markersData && markersData.length > 0) {
            markersData.filter(data => data.status === 'completed')
                .forEach(data => {
                    if (markers[data.id]) markersGroup.addLayer(markers[data.id]);
                });
        }
        updateActiveFilter('completed-filter');
    });

    document.getElementById('archived-filter').addEventListener('click', () => {
        markersGroup.clearLayers();
        
        if (markersData && markersData.length > 0) {
            markersData.filter(data => data.status === 'archived')
                .forEach(data => {
                    if (markers[data.id]) markersGroup.addLayer(markers[data.id]);
                });
        }
        updateActiveFilter('archived-filter');
    });

    function updateActiveFilter(activeId) {
        ['all-filter', 'active-filter', 'completed-filter', 'archived-filter'].forEach(id => {
            const btn = document.getElementById(id);
            if (id === activeId) {
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-primary');
            } else {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-secondary');
            }
        });
    }

    // Recherche de lieu
    const searchInput = document.getElementById('search-location');
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.trim();
            if (searchTerm.length > 0) {
                searchLocation(searchTerm);
            }
        }
    });

    function searchLocation(query) {
        // Utilisation de Nominatim pour la géocodification
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    map.flyTo([lat, lon], 15);
                    
                    // Si le modal d'ajout est ouvert, on remplit automatiquement les champs
                    if (document.getElementById('add-marker-modal').classList.contains('show')) {
                        document.getElementById('objective-lat').value = lat.toFixed(6);
                        document.getElementById('objective-lng').value = lon.toFixed(6);
                        document.getElementById('objective-location').value = result.display_name;
                    }
                } else {
                    alert('Aucun résultat trouvé pour cette recherche.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
                alert('Erreur lors de la recherche de lieu.');
            });
    }

    function showMarkerPopup(data) {
        document.getElementById('popup-title').textContent = data.title;
        
        const statusIcon = document.getElementById('popup-status-icon');
        statusIcon.className = `ri-${getStatusIcon(data.status)}-line ${
            data.status === 'active' ? 'text-primary' : 
            data.status === 'completed' ? 'text-success' : 'text-warning'
        } me-2`;
        
        document.getElementById('popup-status').textContent = 
            data.status === 'active' ? 'Actif' : 
            data.status === 'completed' ? 'Terminé' : 'Archivé';
        
        document.getElementById('popup-location').textContent = data.location || 'Emplacement non spécifié';
        document.getElementById('popup-progress-bar').style.width = `${data.progress}%`;
        document.getElementById('popup-progress').textContent = `Progression: ${data.progress}%`;
        document.getElementById('popup-date').textContent = formatDate(data.date);
        document.getElementById('popup-description').textContent = data.description || 'Aucune description';
        
        // Stocker l'ID pour la suppression
        document.getElementById('delete-marker-btn').dataset.goalId = data.id;
        
        // Position du popup
        const popup = document.getElementById('marker-popup');
        popup.style.display = 'block';
    }

    document.getElementById('close-popup').addEventListener('click', () => {
        document.getElementById('marker-popup').style.display = 'none';
    });

    function getStatusIcon(status) {
        switch(status) {
            case 'active': return 'play';
            case 'completed': return 'check';
            case 'archived': return 'archive';
            default: return 'map-pin';
        }
    }

    function formatDate(dateString) {
        if (!dateString) return 'Date inconnue';
        
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        try {
            return new Date(dateString).toLocaleDateString('fr-FR', options);
        } catch (e) {
            console.error('Erreur de formatage de date:', e);
            return 'Date invalide';
        }
    }

    // Gestion des formulaires
    document.getElementById('confirm-add-marker').addEventListener('click', function() {
        const title = document.getElementById('objective-title').value;
        const status = document.getElementById('objective-status').value;
        const location = document.getElementById('objective-location').value;
        const lat = document.getElementById('objective-lat').value;
        const lng = document.getElementById('objective-lng').value;
        const description = document.getElementById('objective-description').value;
        
        // Validation basique
        if (!title) {
            alert('Le titre est obligatoire.');
            return;
        }
        
        if (!lat || !lng || isNaN(parseFloat(lat)) || isNaN(parseFloat(lng))) {
            alert('Les coordonnées sont invalides. Veuillez cliquer sur la carte ou rechercher un lieu.');
            return;
        }
        
        // Envoyer les données via AJAX
        fetch('/goals', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title,
                status,
                location,
                lat,
                lng,
                description,
                progress: 0
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la création de l\'objectif');
            }
            return response.json();
        })
        .then(data => {
            // Rafraîchir la page pour voir le nouveau marqueur
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la création de l\'objectif. Veuillez réessayer.');
        });
        
        bootstrap.Modal.getInstance(document.getElementById('add-marker-modal')).hide();
    });

    document.getElementById('confirm-delete').addEventListener('click', function() {
        // ID de l'objectif à supprimer
        const goalId = document.getElementById('delete-marker-btn').dataset.goalId;
        
        if (!goalId) {
            alert('Aucun objectif sélectionné.');
            return;
        }
        
        // Envoyer la requête de suppression via AJAX
        fetch(`/goals/${goalId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la suppression');
            }
            return response.json();
        })
        .then(data => {
            // Rafraîchir la page pour mettre à jour la carte
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de l\'objectif.');
        });
        
        bootstrap.Modal.getInstance(document.getElementById('delete-confirm-modal')).hide();
    });
    
    // Pour la sélection d'un emplacement sur la carte
    map.on('click', function(e) {
        // Si le modal d'ajout est ouvert, on remplit automatiquement les champs de latitude et longitude
        if (document.getElementById('add-marker-modal').classList.contains('show')) {
            document.getElementById('objective-lat').value = e.latlng.lat.toFixed(6);
            document.getElementById('objective-lng').value = e.latlng.lng.toFixed(6);
            
            // Résolution inverse pour obtenir le nom du lieu
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('objective-location').value = data.display_name;
                    }
                })
                .catch(error => {
                    console.error('Erreur de géocodage inverse:', error);
                });
        }
    });
});
</script>
@endsection