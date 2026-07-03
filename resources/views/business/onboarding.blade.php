<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Business</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-3xl">
        <h1 class="text-3xl font-bold text-center mb-2">Setup Your Business</h1>
        <p class="text-gray-500 text-center mb-8">Enter your details to generate your first QR code</p>

        <form method="POST" action="{{ route('onboarding') }}">
            @csrf
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Type *</label>
                    <select name="business_type" required class="w-full px-4 py-2 border rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="restaurant">Restaurant</option>
                        <option value="hotel">Hotel</option>
                        <option value="retail">Retail Shop</option>
                        <option value="salon">Salon / Spa</option>
                        <option value="hospital">Hospital / Clinic</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Address Info -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Address Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Address *</label>
                    <input type="text" name="address" value="{{ old('address') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Street name, area, landmark...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                    <input type="text" name="state" value="{{ old('state', 'Gujarat') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pincode *</label>
                    <input type="text" name="pincode" value="{{ old('pincode') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="360001" maxlength="6">
                </div>
            </div>

            <!-- Contact Info -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Contact Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="9876543210">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="contact@yourbusiness.com">
                </div>
            </div>

            <!-- Google Places Search Section -->
            <div class="p-4 bg-gray-50 rounded-lg border mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fab fa-google text-red-500 mr-1"></i> Link Google My Business (Optional but Recommended)</label>
                <div class="flex gap-2">
                    <input type="text" id="google-search" placeholder="Search your business name on Google..." class="flex-1 px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" id="btn-search" class="bg-gray-200 px-4 rounded-lg hover:bg-gray-300"><i class="fas fa-search"></i></button>
                </div>
                <ul id="google-results" class="mt-2 bg-white border rounded-lg hidden max-h-40 overflow-y-auto"></ul>
                <input type="hidden" name="google_place_id" id="google-place-id">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold mt-4 hover:bg-blue-700">
                Complete Setup & Go to Dashboard
            </button>
        </form>
    </div>

    <script>
        document.getElementById('btn-search').addEventListener('click', async () => {
            const query = document.getElementById('google-search').value;
            const city = document.getElementById('city').value;
            if(!query) return;
            
            const res = await fetch(`/business/google-search?query=${encodeURIComponent(query)}&city=${encodeURIComponent(city)}`);
            const data = await res.json();
            const list = document.getElementById('google-results');
            list.innerHTML = '';
            
            if(data.length > 0) {
                list.classList.remove('hidden');
                data.forEach(place => {
                    list.innerHTML += `<li class="p-3 hover:bg-gray-100 cursor-pointer text-sm border-b" data-placeid="${place.place_id}">${place.name} - ${place.formatted_address || ''}</li>`;
                });
                
                list.querySelectorAll('li').forEach(li => {
                    li.addEventListener('click', function() {
                        document.getElementById('google-place-id').value = this.dataset.placeid;
                        document.getElementById('google-search').value = this.innerText.split(' - ')[0];
                        list.classList.add('hidden');
                    });
                });
            }
        });
    </script>
</body>
</html>