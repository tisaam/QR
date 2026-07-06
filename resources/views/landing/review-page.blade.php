@extends('layouts.landing')

@section('title', 'Leave a Review')

@section('content')

@if(isset($qrCode) && isset($business))

    <!-- Step 1: Rating -->
    <div id="step-rating">
        <h2 class="text-xl font-semibold text-center mb-2">How was your experience?</h2>
        <p class="text-center text-gray-500 text-sm mb-6">Tap a star to rate us</p>

        <div class="star-rating mb-6">
            @for($i = 5; $i >= 1; $i--)
                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
            @endfor
        </div>

        <p id="rating-text" class="text-center text-gray-400 text-sm mb-6">Select a rating</p>

        <div class="flex justify-center gap-2 mb-6">
            <button class="lang-btn px-3 py-1 text-xs rounded-full border-2 border-blue-500 bg-blue-500 text-white" data-lang="en">English</button>
            <button class="lang-btn px-3 py-1 text-xs rounded-full border-2 border-gray-300 text-gray-600" data-lang="hi">हिन्दी</button>
            <button class="lang-btn px-3 py-1 text-xs rounded-full border-2 border-gray-300 text-gray-600" data-lang="gu">ગુજરાતી</button>
        </div>

        <button id="btn-generate" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            <i class="fas fa-spinner fa-spin mr-2 hidden" id="load-icon"></i>Get Review Suggestions
        </button>
    </div>

    <!-- Step 2 -->
    <div id="step-review" class="hidden">
        <h2 class="text-xl font-semibold text-center mb-1">Choose or write your review</h2>

        <div id="suggestions-container" class="space-y-2 mb-4 max-h-48 overflow-y-auto"></div>

        <textarea id="custom-review"
            class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            rows="3"
            placeholder="Or write your own review..."></textarea>

        <input type="text" id="customer-name"
            class="w-full border border-gray-300 rounded-xl p-3 text-sm mt-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            placeholder="Your Name (Optional)">

        <button id="btn-submit"
            class="w-full bg-green-600 text-white py-3 rounded-xl mt-4 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            <i class="fas fa-spinner fa-spin mr-2 hidden" id="submit-load-icon"></i>Submit Review
        </button>
    </div>

    <!-- Step 3 -->
    <div id="step-google" class="hidden text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>

        <h2 class="text-xl font-bold mb-2">Thank You!</h2>
        <p class="text-sm text-gray-500 mb-4">Could you also post this on Google?</p>

        <a id="google-link"
           target="_blank"
           class="block w-full bg-blue-600 text-white py-3 rounded-xl text-center font-semibold">
            <i class="fab fa-google mr-2"></i>Post on Google
        </a>

        <button id="btn-skip" class="w-full text-gray-400 mt-3 text-sm hover:text-gray-600">
            Skip this step
        </button>
    </div>

@else

    <!-- ERROR UI -->
    <div class="text-center py-10">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
        </div>

        <h2 class="text-2xl font-bold mt-5 text-gray-800">Invalid QR Code</h2>

        <p class="text-gray-500 mt-2">
            This QR code link is invalid or disabled.
        </p>

        <a href="/" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
            Go Home
        </a>
    </div>

@endif

@endsection

@push('scripts')
<script>
// Prevent errors if variables aren't set
const slug = @json($qrCode->slug ?? null);
const businessName = @json($business->name ?? 'Us');

if (!slug) {
    console.error("Missing slug");
}

let selectedRating = 0;
let selectedLanguage = 'en';
let selectedReview = '';

const texts = {1:'Terrible', 2:'Poor', 3:'Average', 4:'Good', 5:'Excellent!'};

// 1. Handle Star Rating Click
document.querySelectorAll('input[name="rating"]').forEach(el => {
    el.addEventListener('change', function() {
        selectedRating = this.value;
        document.getElementById('btn-generate').disabled = false;
        document.getElementById('rating-text').innerText = texts[selectedRating];
        document.getElementById('rating-text').classList.remove('text-gray-400');
        document.getElementById('rating-text').classList.add('text-blue-600', 'font-semibold');
    });
});

// 2. Handle Language Selection
document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Reset all buttons
        document.querySelectorAll('.lang-btn').forEach(b => {
            b.className = 'lang-btn px-3 py-1 text-xs rounded-full border-2 border-gray-300 text-gray-600';
        });
        // Highlight clicked button
        this.className = 'lang-btn px-3 py-1 text-xs rounded-full border-2 border-blue-500 bg-blue-500 text-white';
        selectedLanguage = this.dataset.lang;
        
        // If rating is selected, auto-fetch new language suggestions
        if(selectedRating > 0) {
            fetchSuggestions();
        }
    });
});

// 3. Fetch Review Suggestions via AJAX
document.getElementById('btn-generate').addEventListener('click', fetchSuggestions);

function fetchSuggestions() {
    const btn = document.getElementById('btn-generate');
    const loader = document.getElementById('load-icon');
    const container = document.getElementById('suggestions-container');
    
    btn.disabled = true;
    loader.classList.remove('hidden');
    container.innerHTML = '<p class="text-center text-gray-400 text-sm py-4">Loading suggestions...</p>';

    // Move to Step 2
    document.getElementById('step-rating').classList.add('hidden');
    document.getElementById('step-review').classList.remove('hidden');

    // Simulate API Call (Replace this URL with your actual backend endpoint)
    // fetch(`/api/reviews/suggestions?rating=${selectedRating}&lang=${selectedLanguage}`)
    setTimeout(() => {
        loader.classList.add('hidden');
        
        // Mock data - Replace this block with your actual fetch response
        const mockSuggestions = {
            en: ["Great service!", "Very clean place.", "Will visit again.", "Staff was friendly."],
            hi: ["बहुत अच्छी सेवा!", "बहुत साफ जगह है।", "मैं फिर से जरूर आऊंगा।", "कर्मचारी अनुकूल थे।"],
            gu: ["ખૂબ સારી સેવા!", "ખૂબ સ્વચ્છ જગ્યા.", "હું ફરીથી ચોક્કસ આવીશ.", "સ્ટાફ મૈત્રીપૂર્ણ હતું."]
        };

        const list = mockSuggestions[selectedLanguage] || mockSuggestions.en;
        container.innerHTML = '';

        list.forEach(text => {
            const div = document.createElement('div');
            div.innerHTML = `<button type="button" class="suggestion-btn w-full text-left p-3 border-2 border-gray-200 rounded-xl text-sm hover:border-blue-500 hover:bg-blue-50 transition">${text}</button>`;
            container.appendChild(div);
        });

        // Add click listeners to suggestions
        document.querySelectorAll('.suggestion-btn').forEach(sBtn => {
            sBtn.addEventListener('click', function() {
                document.querySelectorAll('.suggestion-btn').forEach(b => {
                    b.classList.remove('border-blue-500', 'bg-blue-50');
                    b.classList.add('border-gray-200');
                });
                this.classList.add('border-blue-500', 'bg-blue-50');
                this.classList.remove('border-gray-200');
                
                selectedReview = this.innerText.trim();
                document.getElementById('custom-review').value = selectedReview;
                document.getElementById('btn-submit').disabled = false;
            });
        });

    }, 800); // End setTimeout mock
}

// 4. Enable submit button if user types custom review
document.getElementById('custom-review').addEventListener('input', function() {
    selectedReview = this.value;
    document.getElementById('btn-submit').disabled = this.value.trim().length < 3;
    
    // Deselect suggestion buttons if typing manually
    if(this.value.trim().length > 0) {
        document.querySelectorAll('.suggestion-btn').forEach(b => {
            b.classList.remove('border-blue-500', 'bg-blue-50');
            b.classList.add('border-gray-200');
        });
    }
});

// 5. Submit Review via AJAX
document.getElementById('btn-submit').addEventListener('click', function() {
    const btn = this;
    const loader = document.getElementById('submit-load-icon');
    const customerName = document.getElementById('customer-name').value;

    if(selectedReview.trim().length < 3) return;

    btn.disabled = true;
    loader.classList.remove('hidden');

    // Mock API Call to save review (Replace with actual fetch)
    // fetch('/api/reviews', { method: 'POST', body: JSON.stringify({ slug, rating: selectedRating, review: selectedReview, name: customerName }) })
    setTimeout(() => {
        loader.classList.add('hidden');
        
        // Move to Step 3
        document.getElementById('step-review').classList.add('hidden');
        document.getElementById('step-google').classList.remove('hidden');

        // Set Google Review Link (Replace with actual dynamic link from DB)
        const googleLink = document.getElementById('google-link');
        const mockGoogleUrl = `https://search.google.com/local/writereview?placeid=YOUR_PLACE_ID`;
        googleLink.href = mockGoogleUrl;

    }, 1000); // End setTimeout mock
});

// 6. Skip Google Step
document.getElementById('btn-skip').addEventListener('click', function() {
    // Optional: Redirect to home or show a final message
    document.getElementById('step-google').innerHTML = `
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-heart text-blue-600 text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold mb-2">Thanks for visiting ${businessName}!</h2>
        <p class="text-gray-500 text-sm">We hope to see you again soon.</p>
    `;
});
</script>
@endpush