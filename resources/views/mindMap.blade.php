@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Carte Mentale des Objectifs</h1>
    <div id="mindMap" style="width: 100%; height: 600px; background-color: #f8f9fa;"></div>
</div>

<script src="https://unpkg.com/gojs/release/go.js"></script>
<script>
    // Initialize GoJS mind map
    function initMindMap() {
        const $ = go.GraphObject.make;

        // Create the diagram
        const myDiagram = $(go.Diagram, "mindMap", {
            "undoManager.isEnabled": true,
            layout: $(go.TreeLayout, {
                angle: 90,
                layerSpacing: 35,
                nodeSpacing: 20,
            }),
        });

        // Define node template
        myDiagram.nodeTemplate = $(
            go.Node,
            "Auto",
            $(go.Shape, "RoundedRectangle", {
                fill: $(go.Brush, "Linear", {
                    0: "white",
                    1: new go.Brush(go.Brush.Solid, { color: "lightgray" }),
                }),
                strokeWidth: 1,
                stroke: "#333",
            }, new go.Binding("fill", "color")),
            $(go.TextBlock, {
                margin: 8,
                font: "14px sans-serif",
                stroke: "#333",
            }, new go.Binding("text", "text"))
        );

        // Define link template
        myDiagram.linkTemplate = $(
            go.Link,
            { routing: go.Link.Orthogonal, corner: 5 },
            $(go.Shape, { strokeWidth: 2, stroke: "#555" })
        );

        // Load data from PHP
        const nodeDataArray = @json($nodes);

        // Assign model
        myDiagram.model = new go.TreeModel(nodeDataArray);
    }

    // Initialize when the page loads
    window.addEventListener('load', initMindMap);
</script>
@endsection