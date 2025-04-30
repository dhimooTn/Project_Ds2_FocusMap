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