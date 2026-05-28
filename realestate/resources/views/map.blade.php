@extends('layouts.app')

@section('title', 'Property Map')

@section('content')
{{-- Full-viewport map --}}
<div style="position:relative;">

    {{-- Zoom legend --}}
    <div id="map-legend"
         style="position:absolute;top:12px;left:50%;transform:translateX(-50%);z-index:1000;
                background:white;border-radius:999px;padding:6px 18px;
                box-shadow:0 2px 12px rgba(0,0,0,0.15);display:flex;align-items:center;gap:12px;
                font-size:12px;font-weight:600;color:#475569;white-space:nowrap;">
        <span style="display:flex;align-items:center;gap:5px;">
            <span style="width:14px;height:14px;border-radius:50%;background:#1e3a5f;display:inline-block;"></span> Country
        </span>
        <span style="color:#cbd5e1;">|</span>
        <span style="display:flex;align-items:center;gap:5px;">
            <span style="width:14px;height:14px;border-radius:50%;background:#2563eb;display:inline-block;"></span> State
        </span>
        <span style="color:#cbd5e1;">|</span>
        <span style="display:flex;align-items:center;gap:5px;">
            <span style="width:14px;height:14px;border-radius:50%;background:#38bdf8;display:inline-block;"></span> City
        </span>
        <span style="color:#cbd5e1;">|</span>
        <span style="display:flex;align-items:center;gap:5px;">
            <span style="width:14px;height:14px;border-radius:50%;background:#f59e0b;display:inline-block;"></span> Property
        </span>
        <span id="zoom-label" style="margin-left:4px;color:#94a3b8;font-weight:400;"></span>
    </div>

    <div id="property-map" style="height:calc(100vh - 64px);width:100%;"></div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    .map-cluster-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
        color: white;
        border: 3px solid white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        transition: transform 0.15s ease;
        cursor: pointer;
    }
    .map-cluster-icon:hover { transform: scale(1.15); }
    .leaflet-popup-content-wrapper {
        border-radius: 12px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        padding: 0 !important;
    }
    .leaflet-popup-content { margin: 0 !important; }
    .leaflet-popup-tip-container { margin-top: -1px; }
    .prop-popup { padding: 14px 16px; min-width: 200px; }
    .prop-popup-title { font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 4px; line-height: 1.3; }
    .prop-popup-price { color: #2563eb; font-weight: 700; font-size: 15px; margin-bottom: 8px; }
    .prop-popup-meta { display: flex; gap: 6px; margin-bottom: 10px; }
    .prop-popup-badge { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px; text-transform: capitalize; }
    .prop-popup-btn {
        display: block; text-align: center; background: #2563eb; color: white;
        border-radius: 8px; padding: 7px 0; font-size: 12px; font-weight: 600;
        text-decoration: none; transition: background 0.15s;
    }
    .prop-popup-btn:hover { background: #1d4ed8; color: white; }
    .aggregate-popup { padding: 12px 16px; min-width: 170px; }
    .aggregate-popup-title { font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 4px; }
    .aggregate-popup-count { color: #2563eb; font-size: 13px; margin-bottom: 10px; }
    .aggregate-popup-btn {
        display: block; text-align: center; background: #0f172a; color: white;
        border-radius: 8px; padding: 7px 0; font-size: 12px; font-weight: 600;
        text-decoration: none; transition: background 0.15s;
    }
    .aggregate-popup-btn:hover { background: #1e293b; color: white; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Raw data from server ───────────────────────────────────────────────
    var countryData  = @json($countries);
    var stateData    = @json($states);
    var cityData     = @json($cities);
    var propertyData = @json($properties);

    var filterBase = "{{ route('properties.filter') }}";

    // ── Map init ──────────────────────────────────────────────────────────
    var map = L.map('property-map', {
        center: [20, 10],
        zoom: 3,
        scrollWheelZoom: true,
        zoomControl: true
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
    }).addTo(map);

    // ── Layer groups ──────────────────────────────────────────────────────
    var countryLayer  = L.layerGroup();
    var stateLayer    = L.layerGroup();
    var cityLayer     = L.layerGroup();
    var propertyLayer = L.layerGroup();

    // ── Icon helpers ──────────────────────────────────────────────────────
    function clusterIcon(count, bg, size) {
        var html = '<div class="map-cluster-icon" style="width:' + size + 'px;height:' + size + 'px;background:' + bg + ';font-size:' + Math.round(size * 0.32) + 'px;">' + count + '</div>';
        return L.divIcon({ html: html, className: '', iconSize: [size, size], iconAnchor: [size/2, size/2], popupAnchor: [0, -(size/2 + 4)] });
    }

    function propertyIcon(featured) {
        var bg = featured ? '#f59e0b' : '#2563eb';
        var html = '<div class="map-cluster-icon" style="width:32px;height:32px;background:' + bg + ';font-size:13px;">1</div>';
        return L.divIcon({ html: html, className: '', iconSize: [32, 32], iconAnchor: [16, 16], popupAnchor: [0, -20] });
    }

    // ── Build country layer ───────────────────────────────────────────────
    countryData.forEach(function (d) {
        if (!d.lat || !d.lng) return;
        var url = filterBase + '?country=' + encodeURIComponent(d.country);
        var popup = '<div class="aggregate-popup">' +
            '<div class="aggregate-popup-title">' + d.country + '</div>' +
            '<div class="aggregate-popup-count">' + d.count + ' ' + (d.count === 1 ? 'property' : 'properties') + '</div>' +
            '<a class="aggregate-popup-btn" href="' + url + '">Browse Properties</a>' +
            '</div>';
        L.marker([d.lat, d.lng], { icon: clusterIcon(d.count, '#1e3a5f', 56) })
            .bindPopup(popup)
            .addTo(countryLayer);
    });

    // ── Build state layer ─────────────────────────────────────────────────
    stateData.forEach(function (d) {
        if (!d.lat || !d.lng) return;
        var url = filterBase + '?country=' + encodeURIComponent(d.country) + '&state=' + encodeURIComponent(d.state);
        var popup = '<div class="aggregate-popup">' +
            '<div class="aggregate-popup-title">' + d.state + '</div>' +
            '<div class="aggregate-popup-count">' + d.count + ' ' + (d.count === 1 ? 'property' : 'properties') + '</div>' +
            '<a class="aggregate-popup-btn" href="' + url + '">Browse Properties</a>' +
            '</div>';
        L.marker([d.lat, d.lng], { icon: clusterIcon(d.count, '#2563eb', 44) })
            .bindPopup(popup)
            .addTo(stateLayer);
    });

    // ── Build city layer ──────────────────────────────────────────────────
    cityData.forEach(function (d) {
        if (!d.lat || !d.lng) return;
        var url = filterBase + '?country=' + encodeURIComponent(d.country) + '&state=' + encodeURIComponent(d.state) + '&city=' + encodeURIComponent(d.city);
        var popup = '<div class="aggregate-popup">' +
            '<div class="aggregate-popup-title">' + d.city + '</div>' +
            '<div class="aggregate-popup-count">' + d.count + ' ' + (d.count === 1 ? 'property' : 'properties') + '</div>' +
            '<a class="aggregate-popup-btn" href="' + url + '">Browse Properties</a>' +
            '</div>';
        L.marker([d.lat, d.lng], { icon: clusterIcon(d.count, '#0ea5e9', 38) })
            .bindPopup(popup)
            .addTo(cityLayer);
    });

    // ── Build individual property layer ───────────────────────────────────
    propertyData.forEach(function (p) {
        if (!p.latitude || !p.longitude) return;
        var detailUrl = '/properties/' + p.id;
        var typeBg = p.type === 'residential' ? '#dcfce7' : p.type === 'commercial' ? '#dbeafe' : '#fef9c3';
        var typeColor = p.type === 'residential' ? '#16a34a' : p.type === 'commercial' ? '#1d4ed8' : '#a16207';
        var location = [p.city, p.state].filter(Boolean).join(', ');
        var popup = '<div class="prop-popup">' +
            '<div class="prop-popup-title">' + p.title + '</div>' +
            '<div class="prop-popup-price">$' + Number(p.price).toLocaleString() + '</div>' +
            '<div class="prop-popup-meta">' +
                '<span class="prop-popup-badge" style="background:' + typeBg + ';color:' + typeColor + ';">' + p.type + '</span>' +
                (p.is_featured ? '<span class="prop-popup-badge" style="background:#eff6ff;color:#2563eb;">Featured</span>' : '') +
            '</div>' +
            (location ? '<div style="font-size:11px;color:#64748b;margin-bottom:10px;"><i class="fas fa-map-marker-alt" style="color:#2563eb;margin-right:4px;"></i>' + location + '</div>' : '') +
            '<a class="prop-popup-btn" href="' + detailUrl + '">View Details</a>' +
            '</div>';
        L.marker([p.latitude, p.longitude], { icon: propertyIcon(p.is_featured) })
            .bindPopup(popup)
            .addTo(propertyLayer);
    });

    // ── Zoom level switching ───────────────────────────────────────────────
    var ZOOM_STATE    = 5;
    var ZOOM_CITY     = 8;
    var ZOOM_PROPERTY = 12;

    var zoomLabel = document.getElementById('zoom-label');

    function updateLayers() {
        var z = map.getZoom();
        countryLayer.remove();
        stateLayer.remove();
        cityLayer.remove();
        propertyLayer.remove();

        if (z < ZOOM_STATE) {
            countryLayer.addTo(map);
            zoomLabel.textContent = '(zoom in for states)';
        } else if (z < ZOOM_CITY) {
            stateLayer.addTo(map);
            zoomLabel.textContent = '(zoom in for cities)';
        } else if (z < ZOOM_PROPERTY) {
            cityLayer.addTo(map);
            zoomLabel.textContent = '(zoom in for properties)';
        } else {
            propertyLayer.addTo(map);
            zoomLabel.textContent = '(individual properties)';
        }
    }

    map.on('zoomend', updateLayers);
    updateLayers();
});
</script>
@endpush
