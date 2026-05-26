@extends('layouts.admin')

@section('title', 'Bulk Upload Products')

@section('content')
<!-- DEBUG: workspace/0001 -->
<div class="mb-6">
    <h1 class="text-2xl font-bold">Bulk Upload Products</h1>
    <p class="text-gray-600 mt-1">Upload multiple products using CSV file</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Upload CSV File</h2>

            <form method="POST" action="{{ route('admin.products.bulk-upload-store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold mb-2">CSV File *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition">
                        <input type="file" name="file" accept=".csv,.txt" required class="hidden" id="fileInput">
                        <label for="fileInput" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-700 font-semibold">Click to upload CSV file</p>
                            <p class="text-gray-500 text-sm">or drag and drop</p>
                            <p class="text-gray-400 text-xs mt-2">CSV or TXT files only</p>
                        </label>
                    </div>
                    <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
                    <i class="fas fa-upload mr-2"></i>Upload Products
                </button>
            </form>

            @if($errors->any())
                <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="font-bold text-red-800 mb-2">Upload Errors:</h3>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
                    <a href="/admin/products" class="text-green-600 hover:underline text-sm mt-2 inline-block">View all products →</a>
                </div>
            @endif
        </div>
    </div>

    <div>
        <!-- CSV Format Guide -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-bold text-blue-900 mb-3">CSV Format Guide</h3>
            
            <p class="text-blue-800 text-sm mb-3">Your CSV file must have these columns in this exact order:</p>
            
            <div class="bg-white rounded p-3 mb-3 font-mono text-xs overflow-x-auto">
                <pre>name,sku,part_number,description,price,cost,brand_id,category_id,vessel_type_id
Engine Cylinder,ENG-001,ENG-001-PS,Main cylinder,15000,8000,1,1,
Fuel Valve,FUE-001,FUE-001-PS,Fuel valve assembly,5000,2500,1,2,1</pre>
            </div>

            <div class="space-y-2 text-sm">
                <div class="bg-white p-2 rounded">
                    <p class="font-semibold text-blue-900">Field Requirements:</p>
                    <ul class="text-blue-800 text-xs mt-1 space-y-1">
                        <li>• <strong>name</strong>: Product name (max 255 chars)</li>
                        <li>• <strong>sku</strong>: Unique SKU code</li>
                        <li>• <strong>part_number</strong>: Unique part number</li>
                        <li>• <strong>description</strong>: Product description</li>
                        <li>• <strong>price</strong>: Selling price (number)</li>
                        <li>• <strong>cost</strong>: Cost price (number)</li>
                        <li>• <strong>brand_id</strong>: Brand ID (numeric)</li>
                        <li>• <strong>category_id</strong>: Category ID (numeric)</li>
                        <li>• <strong>vessel_type_id</strong>: Optional (numeric)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sample Download -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="font-bold mb-3">Sample File</h3>
            <p class="text-gray-600 text-sm mb-4">Download a sample CSV file to use as a template</p>
            
            <button onclick="downloadSample()" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 rounded font-semibold text-sm">
                <i class="fas fa-download mr-2"></i>Download Sample CSV
            </button>
        </div>
    </div>
</div>

<script>
    // File name display
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || '';
        document.getElementById('fileName').textContent = fileName ? `Selected: ${fileName}` : '';
    });

    // Drag and drop
    const dropZone = document.querySelector('.border-dashed');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    dropZone.addEventListener('drop', handleDrop, false);
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('fileInput').files = files;
        document.getElementById('fileName').textContent = `Selected: ${files[0].name}`;
    }

    // Download sample CSV
    function downloadSample() {
        const csv = `name,sku,part_number,description,price,cost,brand_id,category_id,vessel_type_id
Engine Cylinder Head,ENG-001,PS-5000-001,Main cylinder head assembly,15000,8000,1,1,1
Marine Fuel Valve,FUE-001,PS-3000-001,Fuel injection valve,5000,2500,1,2,1
Ballast Water Pump,PUM-001,PS-2000-001,High capacity ballast pump,25000,12000,2,1,2
Navigation Console,NAV-001,PS-6000-001,Marine navigation system,45000,22000,3,3,
Deck Lighting Kit,LIG-001,PS-1000-001,LED deck lighting assembly,8000,4000,1,4,3`;
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'products_sample.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }
</script>
@endsection
