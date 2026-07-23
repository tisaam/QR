<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Business</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { margin:0; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f3f4f6; color: #111827; min-height: 100vh; }
        .onboarding-page { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .onboarding-card { width: 100%; max-width: 760px; background: #ffffff; border-radius: 28px; box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12); padding: 40px; }
        .onboarding-title { font-size: 32px; font-weight: 800; margin: 0 0 8px; text-align: center; }
        .onboarding-subtitle { margin: 0 0 32px; color: #6b7280; text-align: center; font-size: 16px; }
        .form-grid { display: grid; gap: 24px; }
        .form-row { display: grid; gap: 24px; }
        @media (min-width: 768px) { .form-row { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151; }
        input[type="text"], input[type="email"], select { width: 100%; border: 1px solid #d1d5db; border-radius: 14px; padding: 14px 16px; font-size: 15px; color: #111827; background: #ffffff; outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
        input[type="text"]:focus, input[type="email"]:focus, select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
        .section-heading { font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 16px; padding-bottom: 10px; border-bottom: 1px solid #e5e7eb; }
        .google-panel { background: #f9fafb; padding: 18px; border-radius: 18px; border: 1px solid #e5e7eb; }
        .google-input-row { display: flex; flex-direction: column; gap: 14px; }
        @media (min-width: 640px) { .google-input-row { flex-direction: row; } }
        .google-input-row input { flex: 1; }
        .google-search-button { border: none; border-radius: 14px; background: #e5e7eb; color: #374151; padding: 0 18px; min-width: 96px; cursor: pointer; font-weight: 700; transition: background 0.2s ease; }
        .google-search-button:hover { background: #d1d5db; }
        .google-results { margin-top: 16px; background: #ffffff; border: 1px solid #d1d5db; border-radius: 14px; max-height: 200px; overflow-y: auto; display: none; }
        .google-results.open { display: block; }
        .google-result-item { padding: 14px 16px; cursor: pointer; border-bottom: 1px solid #e5e7eb; font-size: 14px; color: #111827; }
        .google-result-item:last-child { border-bottom: none; }
        .google-result-item:hover { background: #f3f4f6; }
        .submit-button { width: 100%; background: #2563eb; color: #ffffff; border: none; border-radius: 16px; padding: 16px 20px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background 0.2s ease; }
        .submit-button:hover { background: #1d4ed8; }
    /* ==========================================
   ONBOARDING PAGE - DARK MODE
========================================== */

body.dark-mode{
    background:#0f172a !important;
    color:#f8fafc !important;
}

body.dark-mode .onboarding-card{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
}

body.dark-mode .onboarding-title{
    color:#ffffff !important;
}

body.dark-mode .onboarding-subtitle{
    color:#94a3b8 !important;
}

body.dark-mode .section-heading{
    color:#ffffff !important;
    border-bottom:1px solid #334155 !important;
}

body.dark-mode label{
    color:#cbd5e1 !important;
}

body.dark-mode input[type="text"],
body.dark-mode input[type="email"],
body.dark-mode select{
    background:#0f172a !important;
    color:#f8fafc !important;
    border:1px solid #334155 !important;
}

body.dark-mode input[type="text"]::placeholder,
body.dark-mode input[type="email"]::placeholder{
    color:#94a3b8 !important;
}

body.dark-mode input[type="text"]:focus,
body.dark-mode input[type="email"]:focus,
body.dark-mode select:focus{
    border-color:#6366f1 !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.25) !important;
}

body.dark-mode .google-panel{
    background:#0f172a !important;
    border:1px solid #334155 !important;
}

body.dark-mode .google-search-button{
    background:#334155 !important;
    color:#f8fafc !important;
}

body.dark-mode .google-search-button:hover{
    background:#475569 !important;
}

body.dark-mode .google-results{
    background:#1e293b !important;
    border:1px solid #334155 !important;
}

body.dark-mode .google-result-item{
    color:#f8fafc !important;
    border-bottom:1px solid #334155 !important;
}

body.dark-mode .google-result-item:hover{
    background:#334155 !important;
}

body.dark-mode .submit-button{
    background:#6366f1 !important;
    color:#ffffff !important;
}

body.dark-mode .submit-button:hover{
    background:#4f46e5 !important;
}
    </style>
</head>
<body>
    <div class="onboarding-page">
        <div class="onboarding-card">
            <h1 class="onboarding-title">Setup Your Business</h1>
            <p class="onboarding-subtitle">Enter your details to generate your first QR code</p>

            <form method="POST" action="{{ route('onboarding') }}">
                @csrf

                <div class="form-row">
                    <div>
                        <label for="name">Business Name *</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div>
                        <label for="business_type">Business Type *</label>
                        <select id="business_type" name="business_type" required>
                            <option value="restaurant">Restaurant</option>
                            <option value="hotel">Hotel</option>
                            <option value="retail">Retail Shop</option>
                            <option value="salon">Salon / Spa</option>
                            <option value="hospital">Hospital / Clinic</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <h2 class="section-heading">Address Details</h2>
                <div class="form-grid">
                    <div>
                        <label for="address">Full Address *</label>
                        <input id="address" type="text" name="address" value="{{ old('address') }}" required placeholder="Street name, area, landmark...">
                    </div>
                    <div class="form-row">
                        <div>
                            <label for="city">City *</label>
                            <input id="city" type="text" name="city" value="{{ old('city') }}" required>
                        </div>
                        <div>
                            <label for="state">State *</label>
                            <input id="state" type="text" name="state" value="{{ old('state', 'Gujarat') }}" required>
                        </div>
                    </div>
                    <div>
                        <label for="pincode">Pincode *</label>
                        <input id="pincode" type="text" name="pincode" value="{{ old('pincode') }}" required placeholder="360001" maxlength="6">
                    </div>
                </div>

                <h2 class="section-heading">Contact Details</h2>
                <div class="form-row">
                    <div>
                        <label for="phone">Phone Number *</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="9876543210">
                    </div>
                    <div>
                        <label for="email">Business Email *</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="contact@yourbusiness.com">
                    </div>
                </div>

                <div class="google-panel">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><i class="fab fa-google" style="color: #ef4444; margin-right: 8px;"></i> Link Google My Business (Optional but Recommended)</label>
                    <div class="google-input-row">
                        <input id="google-search" type="text" placeholder="Search your business name on Google...">
                        <button type="button" id="btn-search" class="google-search-button"><i class="fas fa-search"></i></button>
                    </div>
                    <ul id="google-results" class="google-results"></ul>
                    <input type="hidden" name="google_place_id" id="google-place-id">
                </div>

                <button type="submit" class="submit-button">Complete Setup & Go to Dashboard</button>
            </form>
        </div>
    </div>

    <script>
        const searchButton = document.getElementById('btn-search');
        const searchInput = document.getElementById('google-search');
        const resultsList = document.getElementById('google-results');
        const placeIdInput = document.getElementById('google-place-id');

        searchButton.addEventListener('click', async () => {
            const query = searchInput.value.trim();
            const city = document.getElementById('city').value.trim();
            if (!query) return;

            const response = await fetch(`/business/google-search?query=${encodeURIComponent(query)}&city=${encodeURIComponent(city)}`);
            const data = await response.json();
            resultsList.innerHTML = '';

            if (Array.isArray(data) && data.length) {
                resultsList.classList.add('open');
                data.forEach(place => {
                    const item = document.createElement('li');
                    item.className = 'google-result-item';
                    item.dataset.placeid = place.place_id;
                    item.textContent = `${place.name} - ${place.formatted_address || ''}`;
                    item.addEventListener('click', () => {
                        placeIdInput.value = item.dataset.placeid;
                        searchInput.value = place.name || '';
                        resultsList.classList.remove('open');
                    });
                    resultsList.appendChild(item);
                });
            } else {
                resultsList.classList.remove('open');
            }
        });
    </script>
</body>
</html>
