<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FocusMap - Organisez et suivez vos objectifs</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#3498db',secondary:'#9b59b6'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
body {
font-family: 'Inter', sans-serif;
background-color: #f8fafc;
}
.sidebar {
background-color: #2C2C54;
transition: all 0.3s;
}
.progress {
height: 8px;
border-radius: 4px;
overflow: hidden;
}
.progress-bar {
height: 100%;
border-radius: 4px;
transition: width 0.5s ease;
}
.badge {
transition: transform 0.3s ease;
}
.badge:hover {
transform: scale(1.1);
}
input[type="checkbox"] {
appearance: none;
width: 18px;
height: 18px;
border: 2px solid #cbd5e1;
border-radius: 4px;
cursor: pointer;
position: relative;
}
input[type="checkbox"]:checked {
background-color: #3498db;
border-color: #3498db;
}
input[type="checkbox"]:checked::after {
content: "";
position: absolute;
left: 5px;
top: 2px;
width: 6px;
height: 10px;
border: solid white;
border-width: 0 2px 2px 0;
transform: rotate(45deg);
}
.switch {
position: relative;
display: inline-block;
width: 46px;
height: 24px;
}
.switch input {
opacity: 0;
width: 0;
height: 0;
}
.slider {
position: absolute;
cursor: pointer;
top: 0;
left: 0;
right: 0;
bottom: 0;
background-color: #cbd5e1;
transition: .4s;
border-radius: 24px;
}
.slider:before {
position: absolute;
content: "";
height: 18px;
width: 18px;
left: 3px;
bottom: 3px;
background-color: white;
transition: .4s;
border-radius: 50%;
}
input:checked + .slider {
background-color: #3498db;
}
input:checked + .slider:before {
transform: translateX(22px);
}
.custom-range {
-webkit-appearance: none;
width: 100%;
height: 6px;
border-radius: 3px;
background: #e2e8f0;
outline: none;
}
.custom-range::-webkit-slider-thumb {
-webkit-appearance: none;
appearance: none;
width: 18px;
height: 18px;
border-radius: 50%;
background: #3498db;
cursor: pointer;
}
.custom-range::-moz-range-thumb {
width: 18px;
height: 18px;
border-radius: 50%;
background: #3498db;
cursor: pointer;
border: none;
}
.tab-active {
color: #3498db;
border-bottom: 2px solid #3498db;
}
.mindmap-node {
transition: all 0.3s ease;
}
.mindmap-node:hover {
transform: scale(1.05);
}
.mindmap-connection {
stroke: #cbd5e1;
stroke-width: 2;
}
</style>
</head>
<body class="min-h-screen flex">
<!-- Sidebar -->
<aside class="sidebar w-64 h-screen fixed left-0 top-0 text-white flex flex-col z-10 shadow-lg hidden md:block">
<div class="p-6 flex items-center">
<h1 class="text-2xl font-['Pacifico'] text-white">FocusMap</h1>
</div>
<nav class="flex-1 px-4 py-6">
<ul class="space-y-2">
<li>
<a href="#" class="flex items-center p-3 rounded-button bg-primary bg-opacity-20 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-dashboard-line"></i>
</div>
<span>Tableau de bord</span>
</a>
</li>
<li>
<a href="https://readdy.ai/home/b333a2dc-f51a-4a98-8cd6-102f1fd56ba4/f3195b1a-e352-49d2-b8ff-62c2a527db5e" data-readdy="true" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-mind-map-line"></i>
</div>
<span>Carte mentale</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-map-pin-line"></i>
</div>
<span>Carte géographique</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-book-2-line"></i>
</div>
<span>Journal</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-group-line"></i>
</div>
<span>Collaborations</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-medal-line"></i>
</div>
<span>Badges</span>
</a>
</li>
</ul>
</nav>
<div class="p-4 border-t border-white border-opacity-10">
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-settings-3-line"></i>
</div>
<span>Paramètres</span>
</a>
</div>
</aside>
<!-- Mobile Header -->
<div class="md:hidden fixed top-0 left-0 right-0 bg-white shadow-md z-20 p-4 flex items-center justify-between">
<button class="p-2 rounded-button" id="mobile-menu-button">
<div class="w-6 h-6 flex items-center justify-center">
<i class="ri-menu-line text-gray-700"></i>
</div>
</button>
<h1 class="text-xl font-['Pacifico'] text-primary">FocusMap</h1>
<button class="p-2 rounded-button">
<div class="w-6 h-6 flex items-center justify-center">
<i class="ri-user-line text-gray-700"></i>
</div>
</button>
</div>
<!-- Mobile Sidebar -->
<div class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-30 hidden" id="mobile-overlay">
<div class="sidebar w-64 h-screen bg-[#2C2C54] text-white flex flex-col transform -translate-x-full transition-transform duration-300" id="mobile-sidebar">
<div class="p-6 flex items-center justify-between">
<h1 class="text-2xl font-['Pacifico'] text-white">FocusMap</h1>
<button class="p-2 rounded-full hover:bg-white hover:bg-opacity-10" id="close-sidebar">
<div class="w-6 h-6 flex items-center justify-center">
<i class="ri-close-line"></i>
</div>
</button>
</div>
<nav class="flex-1 px-4 py-6">
<ul class="space-y-2">
<li>
<a href="#" class="flex items-center p-3 rounded-button bg-primary bg-opacity-20 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-dashboard-line"></i>
</div>
<span>Tableau de bord</span>
</a>
</li>
<li>
<a href="https://readdy.ai/home/b333a2dc-f51a-4a98-8cd6-102f1fd56ba4/f3195b1a-e352-49d2-b8ff-62c2a527db5e" data-readdy="true" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-mind-map-line"></i>
</div>
<span>Carte mentale</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-map-pin-line"></i>
</div>
<span>Carte géographique</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-book-2-line"></i>
</div>
<span>Journal</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-group-line"></i>
</div>
<span>Collaborations</span>
</a>
</li>
<li>
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-medal-line"></i>
</div>
<span>Badges</span>
</a>
</li>
</ul>
</nav>
<div class="p-4 border-t border-white border-opacity-10">
<a href="#" class="flex items-center p-3 rounded-button hover:bg-white hover:bg-opacity-10 text-white">
<div class="w-6 h-6 flex items-center justify-center mr-3">
<i class="ri-settings-3-line"></i>
</div>
<span>Paramètres</span>
</a>
</div>
</div>
</div>
<!-- Main Content -->
<main class="flex-1 md:ml-64 pt-4 md:pt-0 pb-10 min-h-screen bg-gray-50">
<!-- Header -->
<header class="hidden md:flex items-center justify-between px-8 py-4 bg-white shadow-sm">
<div class="relative w-72">
<input type="text" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 rounded-button border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
<div class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-400">
<i class="ri-search-line"></i>
</div>
</div>
<div class="flex items-center space-x-4">
<button class="p-2 rounded-button bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 relative">
<div class="w-5 h-5 flex items-center justify-center">
<i class="ri-notification-3-line"></i>
</div>
<span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">3</span>
</button>
<button class="flex items-center space-x-2 p-2 rounded-button hover:bg-gray-50">
<img src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20a%20young%20french%20man%20with%20short%20hair%2C%20neutral%20expression%2C%20high%20quality%20portrait%2C%20professional%20lighting%2C%20studio%20background&width=40&height=40&seq=1&orientation=squarish" class="w-8 h-8 rounded-full object-cover" alt="Photo de profil">
<span class="text-sm font-medium text-gray-700">Thomas Dubois</span>
<div class="w-4 h-4 flex items-center justify-center text-gray-400">
<i class="ri-arrow-down-s-line"></i>
</div>
</button>
<button class="px-4 py-2 bg-primary text-white rounded-button flex items-center space-x-2 hover:bg-primary/90 whitespace-nowrap">
<div class="w-4 h-4 flex items-center justify-center">
<i class="ri-add-line"></i>
</div>
<span>Nouvel objectif</span>
</button>
</div>
</header>
<div class="px-4 md:px-8 pt-16 md:pt-6">
<!-- Welcome Section -->
<div class="mb-8">
<h1 class="text-2xl font-bold text-gray-800">Bonjour, Thomas 👋</h1>
<p class="text-gray-600 mt-1">Vous avez 3 objectifs en cours et 2 tâches pour aujourd'hui.</p>
</div>
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-4">
<h3 class="text-gray-500 font-medium text-sm">Objectifs actifs</h3>
<div class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-primary">
<i class="ri-flag-line"></i>
</div>
</div>
<p class="text-3xl font-bold text-gray-800">7</p>
<div class="flex items-center mt-2 text-sm">
<span class="text-green-500 flex items-center">
<div class="w-4 h-4 flex items-center justify-center mr-1">
<i class="ri-arrow-up-line"></i>
</div>
<span>+2</span>
</span>
<span class="text-gray-500 ml-2">depuis le mois dernier</span>
</div>
</div>
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-4">
<h3 class="text-gray-500 font-medium text-sm">Objectifs complétés</h3>
<div class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-500">
<i class="ri-check-double-line"></i>
</div>
</div>
<p class="text-3xl font-bold text-gray-800">12</p>
<div class="flex items-center mt-2 text-sm">
<span class="text-green-500 flex items-center">
<div class="w-4 h-4 flex items-center justify-center mr-1">
<i class="ri-arrow-up-line"></i>
</div>
<span>+5</span>
</span>
<span class="text-gray-500 ml-2">depuis le mois dernier</span>
</div>
</div>
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-4">
<h3 class="text-gray-500 font-medium text-sm">Badges gagnés</h3>
<div class="w-8 h-8 flex items-center justify-center rounded-full bg-purple-100 text-secondary">
<i class="ri-medal-line"></i>
</div>
</div>
<p class="text-3xl font-bold text-gray-800">8</p>
<div class="flex items-center mt-2 text-sm">
<span class="text-green-500 flex items-center">
<div class="w-4 h-4 flex items-center justify-center mr-1">
<i class="ri-arrow-up-line"></i>
</div>
<span>+3</span>
</span>
<span class="text-gray-500 ml-2">depuis le mois dernier</span>
</div>
</div>
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-4">
<h3 class="text-gray-500 font-medium text-sm">Progression moyenne</h3>
<div class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500">
<i class="ri-bar-chart-line"></i>
</div>
</div>
<p class="text-3xl font-bold text-gray-800">68%</p>
<div class="flex items-center mt-2 text-sm">
<span class="text-green-500 flex items-center">
<div class="w-4 h-4 flex items-center justify-center mr-1">
<i class="ri-arrow-up-line"></i>
</div>
<span>+12%</span>
</span>
<span class="text-gray-500 ml-2">depuis le mois dernier</span>
</div>
</div>
</div>
<!-- Main Content Sections -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Left Column -->
<div class="lg:col-span-2 space-y-6">
<!-- Objectives Progress -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-6">
<h2 class="text-lg font-bold text-gray-800">Objectifs en cours</h2>
<div class="flex space-x-2">
<button class="px-1 py-1 rounded-full bg-gray-100 text-gray-600 flex items-center">
<span class="px-3 py-1 rounded-full bg-white shadow-sm text-sm">Tous</span>
<span class="px-3 py-1 rounded-full text-sm">Personnels</span>
<span class="px-3 py-1 rounded-full text-sm">Professionnels</span>
</button>
</div>
</div>
<div class="space-y-5">
<div>
<div class="flex items-center justify-between mb-2">
<div class="flex items-center">
<div class="w-6 h-6 flex items-center justify-center rounded-full bg-blue-100 text-primary mr-3">
<i class="ri-run-line"></i>
</div>
<h3 class="font-medium text-gray-800">Courir un semi-marathon</h3>
</div>
<span class="text-sm text-gray-500">75%</span>
</div>
<div class="progress bg-gray-200">
<div class="progress-bar bg-primary" style="width: 75%"></div>
</div>
</div>
<div>
<div class="flex items-center justify-between mb-2">
<div class="flex items-center">
<div class="w-6 h-6 flex items-center justify-center rounded-full bg-purple-100 text-secondary mr-3">
<i class="ri-book-open-line"></i>
</div>
<h3 class="font-medium text-gray-800">Apprendre l'espagnol</h3>
</div>
<span class="text-sm text-gray-500">45%</span>
</div>
<div class="progress bg-gray-200">
<div class="progress-bar bg-secondary" style="width: 45%"></div>
</div>
</div>
<div>
<div class="flex items-center justify-between mb-2">
<div class="flex items-center">
<div class="w-6 h-6 flex items-center justify-center rounded-full bg-green-100 text-green-500 mr-3">
<i class="ri-briefcase-line"></i>
</div>
<h3 class="font-medium text-gray-800">Obtenir une promotion</h3>
</div>
<span class="text-sm text-gray-500">60%</span>
</div>
<div class="progress bg-gray-200">
<div class="progress-bar bg-green-500" style="width: 60%"></div>
</div>
</div>
<div>
<div class="flex items-center justify-between mb-2">
<div class="flex items-center">
<div class="w-6 h-6 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mr-3">
<i class="ri-home-line"></i>
</div>
<h3 class="font-medium text-gray-800">Économiser pour un appartement</h3>
</div>
<span class="text-sm text-gray-500">30%</span>
</div>
<div class="progress bg-gray-200">
<div class="progress-bar bg-yellow-500" style="width: 30%"></div>
</div>
</div>
</div>
<button class="mt-6 text-primary font-medium flex items-center text-sm">
<span>Voir tous les objectifs</span>
<div class="w-4 h-4 flex items-center justify-center ml-1">
<i class="ri-arrow-right-line"></i>
</div>
</button>
</div>
<!-- Mind Map -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-6">
<h2 class="text-lg font-bold text-gray-800">Carte mentale</h2>
<button class="p-2 rounded-button text-gray-500 hover:bg-gray-100">
<div class="w-5 h-5 flex items-center justify-center">
<i class="ri-fullscreen-line"></i>
</div>
</button>
</div>
<div class="relative h-80 border border-gray-100 rounded-lg overflow-hidden">
<div id="mindmap-chart" class="w-full h-full"></div>
</div>
</div>
</div>
<!-- Right Column -->
<div class="space-y-6">
<!-- Today's Tasks -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-6">
<h2 class="text-lg font-bold text-gray-800">Tâches du jour</h2>
<span class="text-sm text-gray-500">19 avril 2025</span>
</div>
<div class="space-y-4">
<div class="flex items-start">
<input type="checkbox" class="mt-1 mr-3">
<div>
<h3 class="font-medium text-gray-800">Courir 5 km</h3>
<p class="text-sm text-gray-500">Objectif: Courir un semi-marathon</p>
<div class="flex items-center mt-1 text-xs text-gray-500">
<div class="w-4 h-4 flex items-center justify-center mr-1">
<i class="ri-time-line"></i>
</div>
<span>08:00 - 09:00</span>
</div>
</div>
</div>
<div class="flex items-start">
<input type="checkbox" class="mt-1 mr-3">
<div>
<h3 class="font-medium text-gray-800">Leçon d'espagnol</h3>
<p class="text-sm text-gray-500">Objectif: Apprendre l'espagnol</p>
<div class="flex items-center mt-1 text-xs text-gray-500">
<div class="w-4 h-4 flex items-center justify-center mr-1">
<i class="ri-time-line"></i>
</div>
<span>12:30 - 13:30</span>
</div>
</div>
</div>
<div class="flex items-start">
<input type="checkbox" checked class="mt-1 mr-3">
<div>
<h3 class="font-medium text-gray-800 line-through">Préparer présentation</h3>
<p class="text-sm text-gray-500 line-through">Objectif: Obtenir une promotion</p>
<div class="flex items-center mt-1 text-xs text-gray-500">
<div class="w-4 h-4 flex items-center justify-center mr-1">
<i class="ri-time-line"></i>
</div>
<span>10:00 - 11:30</span>
</div>
</div>
</div>
</div>
<button class="mt-6 text-primary font-medium flex items-center text-sm">
<span>Ajouter une tâche</span>
<div class="w-4 h-4 flex items-center justify-center ml-1">
<i class="ri-add-line"></i>
</div>
</button>
</div>
<!-- Badges -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-6">
<h2 class="text-lg font-bold text-gray-800">Badges récents</h2>
<span class="text-sm text-primary font-medium cursor-pointer">Voir tous</span>
</div>
<div class="grid grid-cols-3 gap-4">
<div class="badge flex flex-col items-center">
<div class="w-16 h-16 rounded-full bg-primary bg-opacity-10 flex items-center justify-center mb-2">
<div class="w-8 h-8 flex items-center justify-center text-primary">
<i class="ri-run-line"></i>
</div>
</div>
<span class="text-xs text-gray-700 text-center">Coureur</span>
</div>
<div class="badge flex flex-col items-center">
<div class="w-16 h-16 rounded-full bg-secondary bg-opacity-10 flex items-center justify-center mb-2">
<div class="w-8 h-8 flex items-center justify-center text-secondary">
<i class="ri-book-open-line"></i>
</div>
</div>
<span class="text-xs text-gray-700 text-center">Linguiste</span>
</div>
<div class="badge flex flex-col items-center">
<div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-2">
<div class="w-8 h-8 flex items-center justify-center text-green-500">
<i class="ri-calendar-check-line"></i>
</div>
</div>
<span class="text-xs text-gray-700 text-center">Régulier</span>
</div>
</div>
</div>
<!-- Geographic Map -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
<div class="flex items-center justify-between mb-6">
<h2 class="text-lg font-bold text-gray-800">Carte géographique</h2>
<button class="p-2 rounded-button text-gray-500 hover:bg-gray-100">
<div class="w-5 h-5 flex items-center justify-center">
<i class="ri-fullscreen-line"></i>
</div>
</button>
</div>
<div class="relative h-60 rounded-lg overflow-hidden" style="background-image: url('https://public.readdy.ai/gen_page/map_placeholder_1280x720.png'); background-size: cover; background-position: center;">
<div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
<div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white shadow-lg">
<i class="ri-map-pin-fill"></i>
</div>
</div>
<div class="absolute top-2/3 left-1/4 transform -translate-x-1/2 -translate-y-1/2">
<div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center text-white shadow-lg">
<i class="ri-map-pin-fill"></i>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
<!-- AI Assistant Button -->
<button class="fixed bottom-6 right-6 w-14 h-14 bg-primary rounded-full shadow-lg flex items-center justify-center text-white z-30 hover:bg-primary/90">
<div class="w-6 h-6 flex items-center justify-center">
<i class="ri-robot-line"></i>
</div>
</button>
<script>
document.addEventListener('DOMContentLoaded', function() {
// Mobile menu toggle
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileSidebar = document.getElementById('mobile-sidebar');
const mobileOverlay = document.getElementById('mobile-overlay');
const closeSidebar = document.getElementById('close-sidebar');
if (mobileMenuButton && mobileSidebar && mobileOverlay && closeSidebar) {
mobileMenuButton.addEventListener('click', function() {
mobileOverlay.classList.remove('hidden');
setTimeout(() => {
mobileSidebar.classList.remove('-translate-x-full');
}, 50);
});
function closeMobileMenu() {
mobileSidebar.classList.add('-translate-x-full');
setTimeout(() => {
mobileOverlay.classList.add('hidden');
}, 300);
}
closeSidebar.addEventListener('click', closeMobileMenu);
mobileOverlay.addEventListener('click', function(e) {
if (e.target === mobileOverlay) {
closeMobileMenu();
}
});
}
});
document.addEventListener('DOMContentLoaded', function() {
// Initialize mindmap chart
const mindmapChart = echarts.init(document.getElementById('mindmap-chart'));
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
});
</script>
</body>
</html>
