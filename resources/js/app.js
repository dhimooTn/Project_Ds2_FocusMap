document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const closeSidebar = document.getElementById('close-sidebar');
    
    if (mobileMenuButton && mobileSidebar && mobileOverlay && closeSidebar) {
        mobileMenuButton.addEventListener('click', function() {
            mobileOverlay.classList.remove('d-none');
            mobileSidebar.classList.add('show');
        });
        
        function closeMobileMenu() {
            mobileSidebar.classList.remove('show');
            setTimeout(() => {
                mobileOverlay.classList.add('d-none');
            }, 300);
        }
        
        closeSidebar.addEventListener('click', closeMobileMenu);
        mobileOverlay.addEventListener('click', function(e) {
            if (e.target === mobileOverlay) {
                closeMobileMenu();
            }
        });
    }
    
    // Initialize mindmap chart if element exists
    const mindmapChartEl = document.getElementById('mindmap-chart');
    if (mindmapChartEl && typeof echarts !== 'undefined') {
        const mindmapChart = echarts.init(mindmapChartEl);
        const option = {
            animation: false,
            tooltip: {
                trigger: 'item',
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                borderColor: '#eee',
                borderWidth: 1,
                textStyle: {
                    color: '#1f2937'
                }
            },
            series: [
                {
                    type: 'graph',
                    layout: 'force',
                    roam: true,
                    draggable: true,
                    label: {
                        show: true,
                        position: 'inside',
                        color: '#fff',
                        fontSize: 12
                    },
                    force: {
                        repulsion: 200,
                        edgeLength: 80
                    },
                    data: [
                        {
                            name: 'Objectifs 2025',
                            symbolSize: 70,
                            itemStyle: {
                                color: '#3498db'
                            },
                            x: 0,
                            y: 0,
                            fixed: true
                        },
                        {
                            name: 'Santé',
                            symbolSize: 50,
                            itemStyle: {
                                color: '#57b5e7'
                            }
                        },
                        {
                            name: 'Carrière',
                            symbolSize: 50,
                            itemStyle: {
                                color: '#8dd3c7'
                            }
                        },
                        {
                            name: 'Éducation',
                            symbolSize: 50,
                            itemStyle: {
                                color: '#fbbf72'
                            }
                        },
                        {
                            name: 'Finances',
                            symbolSize: 50,
                            itemStyle: {
                                color: '#fc8d62'
                            }
                        },
                        {
                            name: 'Marathon',
                            symbolSize: 35,
                            itemStyle: {
                                color: '#57b5e7'
                            }
                        },
                        {
                            name: 'Méditation',
                            symbolSize: 35,
                            itemStyle: {
                                color: '#57b5e7'
                            }
                        },
                        {
                            name: 'Promotion',
                            symbolSize: 35,
                            itemStyle: {
                                color: '#8dd3c7'
                            }
                        },
                        {
                            name: 'Espagnol',
                            symbolSize: 35,
                            itemStyle: {
                                color: '#fbbf72'
                            }
                        },
                        {
                            name: 'Appartement',
                            symbolSize: 35,
                            itemStyle: {
                                color: '#fc8d62'
                            }
                        }
                    ],
                    links: [
                        {
                            source: 'Objectifs 2025',
                            target: 'Santé'
                        },
                        {
                            source: 'Objectifs 2025',
                            target: 'Carrière'
                        },
                        {
                            source: 'Objectifs 2025',
                            target: 'Éducation'
                        },
                        {
                            source: 'Objectifs 2025',
                            target: 'Finances'
                        },
                        {
                            source: 'Santé',
                            target: 'Marathon'
                        },
                        {
                            source: 'Santé',
                            target: 'Méditation'
                        },
                        {
                            source: 'Carrière',
                            target: 'Promotion'
                        },
                        {
                            source: 'Éducation',
                            target: 'Espagnol'
                        },
                        {
                            source: 'Finances',
                            target: 'Appartement'
                        }
                    ],
                    lineStyle: {
                        color: 'source',
                        curveness: 0.3,
                        width: 2,
                        opacity: 0.7
                    }
                }
            ]
        };
        mindmapChart.setOption(option);
        
        window.addEventListener('resize', function() {
            mindmapChart.resize();
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    let map;
    let marker;
    
    function initMap() {
        map = L.map('map').setView([46.2276, 2.2137], 5); // Default to France
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add click event
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
            
            // Reverse geocoding to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                .then(response => response.json())
                .then(data => {
                    const address = data.display_name || 'Adresse non trouvée';
                    document.getElementById('location').value = address;
                })
                .catch(error => console.error('Error:', error));
        });
    }
    
    // Initialize map when modal is shown
    document.getElementById('createGoalModal').addEventListener('shown.bs.modal', function() {
        if (!map) {
            initMap();
        } else {
            map.invalidateSize();
        }
    });
    
    // Task management
    let taskCount = 1;
    
    document.getElementById('add-task').addEventListener('click', function() {
        const container = document.getElementById('tasks-container');
        const newTask = document.createElement('div');
        newTask.className = 'task-item mb-3 p-3 border rounded';
        newTask.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control mb-2" name="tasks[${taskCount}][title]" placeholder="Titre de la tâche" required>
                </div>
                <div class="col-md-6">
                    <input type="date" class="form-control mb-2" name="tasks[${taskCount}][due_date]">
                </div>
            </div>
            <textarea class="form-control mb-2" name="tasks[${taskCount}][description]" placeholder="Description" rows="2"></textarea>
            <div class="row">
                <div class="col-md-4">
                    <select class="form-select" name="tasks[${taskCount}][priority]">
                        <option value="low">Faible priorité</option>
                        <option value="medium" selected>Priorité moyenne</option>
                        <option value="high">Haute priorité</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tasks[${taskCount}][has_event]" id="task${taskCount}HasEvent">
                        <label class="form-check-label" for="task${taskCount}HasEvent">Ajouter un événement</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-sm btn-danger remove-task float-end">Supprimer</button>
                </div>
            </div>
            
            <!-- Event Fields (hidden by default) -->
            <div class="event-fields mt-2" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <input type="datetime-local" class="form-control mb-2" name="tasks[${taskCount}][event_start]">
                    </div>
                    <div class="col-md-6">
                        <input type="datetime-local" class="form-control mb-2" name="tasks[${taskCount}][event_end]">
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(newTask);
        taskCount++;
    });
    
    // Event delegation for remove buttons and event toggles
    document.getElementById('tasks-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-task')) {
            e.target.closest('.task-item').remove();
        }
        
        if (e.target.type === 'checkbox' && e.target.name.includes('has_event')) {
            const eventFields = e.target.closest('.task-item').querySelector('.event-fields');
            eventFields.style.display = e.target.checked ? 'block' : 'none';
        }
    });
});