@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Mindmap des Objectifs</h1>
    <div id="markmap-container" class="border rounded p-3 bg-white shadow-sm">
        <svg id="markmap" width="100%" height="600"></svg>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/markmap-autoloader"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch the mindmap data from the API endpoint
    fetch('/api/mindmap')
        .then(response => response.json())
        .then(data => {
            // Transform the API data into markdown format for markmap
            let markdown = `# Mes Objectifs\n\n`;
            
            data.data.forEach(goal => {
                markdown += `## ${goal.name} (${goal.progress}%)\n`;
                
                goal.tasks.forEach(task => {
                    const status = task.completed ? '✓' : '◯';
                    const priority = task.priority ? ` [${task.priority}]` : '';
                    const dueDate = task.due_date ? ` (${task.due_date})` : '';
                    markdown += `- ${status} ${task.name}${priority}${dueDate}\n`;
                });
                
                markdown += '\n';
            });
            
            // Initialize the markmap
            markmap.AutoLoader.render(
                document.getElementById('markmap'),
                markdown,
                {
                    preset: 'default', // or 'colorful'
                    linkShape: 'diagonal',
                    duration: 500,
                    nodeMinHeight: 16,
                    spacingVertical: 5,
                    spacingHorizontal: 80,
                    autoFit: true,
                    fitRatio: 1,
                    zoom: true,
                    pan: true,
                    color: (node) => {
                        // Color nodes based on completion status
                        if (node.payload?.content?.includes('✓')) return '#10B981'; // green for completed
                        if (node.payload?.content?.includes('◯')) return '#EF4444'; // red for pending
                        return null; // default color
                    },
                }
            );
        })
        .catch(error => {
            console.error('Error loading mindmap data:', error);
            document.getElementById('markmap-container').innerHTML = `
                <div class="alert alert-danger">
                    Erreur lors du chargement de la mindmap. Veuillez rafraîchir la page.
                </div>
            `;
        });
});
</script>
@endpush

<style>
#markmap-container {
    overflow: hidden;
}

#markmap {
    min-height: 600px;
    width: 100%;
}

.markmap-node circle {
    stroke-width: 2px;
}

.markmap-node text {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    font-size: 14px;
}

.markmap-link {
    stroke: #94a3b8;
}
</style>
@endsection