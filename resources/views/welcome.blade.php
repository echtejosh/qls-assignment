<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MyQLS - Verzendlabel & Pakbon Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        button, .button, [role="button"] {
            cursor: pointer;
        }

        button:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8">
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 rounded-lg">
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <i class='bx bxs-truck text-4xl'></i>
                    QLS Verzendlabel & Pakbon
                </h1>
                <p class="text-blue-100 mt-2">Combineer verzendlabels met pakbonnen in één document</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <i class='bx bx-check-circle text-xl'></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-semibold flex items-center gap-2 text-gray-800">
                                <i class='bx bx-receipt text-blue-600'></i>
                                Bestelling Details
                            </h2>
                        </div>

                        <x-input label="Bestelnummer" name="order_number"
                                 value="{{ old('order_number', $formData['order_number']) }}" required/>

                        <x-input label="Naam klant" name="customer_name"
                                 value="{{ old('customer_name', $formData['customer_name']) }}" required/>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="Straat" name="street"
                                     value="{{ old('street', $formData['street']) }}" required/>
                            <x-input label="Huisnummer" name="house_number"
                                     value="{{ old('house_number', $formData['house_number']) }}" required/>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="Postcode" name="postal_code"
                                     value="{{ old('postal_code', $formData['postal_code']) }}" required/>
                            <x-input label="Plaats" name="city"
                                     value="{{ old('city', $formData['city']) }}" required/>
                        </div>

                        <x-input label="Email" name="email" type="email"
                                 value="{{ old('email', $formData['email']) }}" required/>

                        <x-input label="Telefoon" name="phone" type="tel"
                                 value="{{ old('phone', $formData['phone']) }}" required/>
                    </div>

                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-semibold flex items-center gap-2 text-gray-800">
                                <i class='bx bx-package text-green-600'></i>
                                Verzending & Producten
                            </h2>
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                <i class='bx bx-car mr-1'></i>
                                Verzendmethode
                            </label>
                            <select name="shipping_method"
                                    class="block w-full border border-gray-300 rounded-md p-3 cursor-pointer"
                                    required>
                                @foreach ($shippingOptions as $data)
                                    <option value="{{ $data['id'] }}"
                                            {{ old('shipping_method', $formData['shipping_method']) === $data['id'] ? 'selected' : '' }}>
                                        {{ $data['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="levering_bij_buren" name="levering_bij_buren"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                            <label for="levering_bij_buren" class="text-sm text-gray-700 flex items-center gap-2 cursor-pointer">
                                Levering bij buren toestaan
                            </label>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                <i class='bx bx-list-ul mr-1'></i>
                                Bestelde Producten
                            </label>

                            <livewire:ordered-items :items="$items"/>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-4 items-center">
                        <button type="submit" id="submitBtn"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center gap-2 font-medium shadow-md cursor-pointer disabled:opacity-70 disabled:cursor-not-allowed">
                            <i class='bx bx-save' id="submitIcon"></i>
                            <div class="spinner hidden" id="loadingSpinner">
                                <i class='bx bx-loader-alt'></i>
                            </div>
                            <span id="submitText">Maak Bestelling Aan</span>
                        </button>

                        <div class="text-sm text-gray-600">
                            <i class='bx bx-info-circle'></i>
                            Pakbon + verzendlabel wordt automatisch aangemaakt
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-error-circle text-red-500 text-xl'></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-red-800 mb-2">
                                        Er zijn fouten opgetreden bij het verwerken van uw bestelling:
                                    </h3>
                                    <div class="space-y-1">
                                        @foreach($errors->all() as $error)
                                            <div class="flex items-start gap-2 text-sm text-red-700">
                                                <i class='bx bx-x text-red-500 mt-0.5 flex-shrink-0'></i>
                                                <span>{{ $error }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @livewireScripts

    <script>
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitIcon = document.getElementById('submitIcon');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const submitText = document.getElementById('submitText');

            submitBtn.disabled = true;

            submitIcon.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');

            submitText.textContent = 'Bestelling wordt aangemaakt...';
        });

        window.addEventListener('load', function() {
            const submitBtn = document.getElementById('submitBtn');
            const submitIcon = document.getElementById('submitIcon');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const submitText = document.getElementById('submitText');

            submitBtn.disabled = false;
            submitIcon.classList.remove('hidden');
            loadingSpinner.classList.add('hidden');
            submitText.textContent = 'Maak Bestelling Aan';
        });
    </script>
</body>

</html>
