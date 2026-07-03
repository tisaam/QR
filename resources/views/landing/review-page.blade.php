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

        <button id="btn-generate" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold disabled:opacity-50" disabled>
            Get Review Suggestions
        </button>
    </div>

    <!-- Step 2 -->
    <div id="step-review" class="hidden">
        <h2 class="text-xl font-semibold text-center mb-1">Choose or write your review</h2>

        <div id="suggestions-container" class="space-y-2 mb-4"></div>

        <textarea id="custom-review"
            class="w-full border rounded-xl p-3 text-sm"
            rows="3"
            placeholder="Or write your own review..."></textarea>

        <input type="text" id="customer-name"
            class="w-full border rounded-xl p-3 text-sm mt-3"
            placeholder="Your Name (Optional)">

        <button id="btn-submit"
            class="w-full bg-green-600 text-white py-3 rounded-xl mt-4 disabled:opacity-50"
            disabled>
            Submit Review
        </button>
    </div>

    <!-- Step 3 -->
    <div id="step-google" class="hidden text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>

        <h2 class="text-xl font-bold mb-2">Thank You!</h2>

        <a id="google-link"
           target="_blank"
           class="block w-full bg-blue-600 text-white py-3 rounded-xl">
            Post on Google
        </a>

        <button id="btn-skip" class="w-full text-gray-400 mt-2 text-sm">
            Skip this step
        </button>
    </div>

@else

    <!-- ERROR UI -->
    <div class="text-center py-10">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
        </div>

        <h2 class="text-2xl font-bold mt-5">Invalid QR Code</h2>

        <p class="text-gray-500 mt-2">
            This QR code link is invalid or disabled.
        </p>

        <a href="/" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg">
            Go Home
        </a>
    </div>

@endif

@endsection

@push('scripts')
<script>
const slug = @json($qrCode->slug ?? null);

if (!slug) {
    console.error("Missing slug");
}

let selectedRating = 0;
let selectedLanguage = 'en';
let selectedReview = '';
let reviewId = null;

const texts = {1:'Terrible',2:'Poor',3:'Average',4:'Good',5:'Excellent!'};

// rating
document.querySelectorAll('input[name="rating"]').forEach(el=>{
    el.addEventListener('change', function(){
        selectedRating = this.value;
        document.getElementById('btn-generate').disabled = false;
        document.getElementById('rating-text').innerText = texts[selectedRating];
    });
});

// language
document.querySelectorAll('.lang-btn').forEach(btn=>{
    btn.addEventListener('click', function(){
        document.querySelectorAll('.lang-btn').forEach(b=>{
            b.className = 'lang-btn px-3 py-1 text-xs rounded-full border-2 border-gray-300 text-gray-600';
        });
        this.className = 'lang-btn px-3 py-1 text-xs rounded-full border-2 border-blue-500 bg-blue-500 text-white';
        selectedLanguage = this.dataset.lang;
    });
});
</script>
@endpush