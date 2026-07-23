<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Us — {{ $qrCode->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0f1117;
            --card-bg: #1a1d27;
            --card-border: rgba(255,255,255,0.06);
            --fg: #f0f0f2;
            --muted: #8b8d98;
            --accent: #f59e0b;
            --accent-glow: rgba(245, 158, 11, 0.25);
            --danger: #ef4444;
            --success: #22c55e;
            --input-bg: #252833;
            --input-border: #333645;
            --ai-bg: rgba(139, 92, 246, 0.08);
            --ai-border: rgba(139, 92, 246, 0.2);
            --ai-text: #a78bfa;
            --ai-chip-bg: rgba(139, 92, 246, 0.1);
            --ai-chip-hover: rgba(139, 92, 246, 0.2);
            --ai-chip-border: rgba(139, 92, 246, 0.15);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -40%; left: -20%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -30%; right: -10%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(139,92,246,0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            max-width: 420px;
            width: 100%;
            padding: 36px 28px 32px;
            text-align: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            animation: cardIn 0.5s ease-out;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), #d97706);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 4px 20px var(--accent-glow);
        }
        .brand-icon svg { width: 26px; height: 26px; fill: #fff; }

        h2 {
            color: var(--fg);
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }
        .subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 28px;
            line-height: 1.4;
        }

        /* --- Stars --- */
        .star-rating {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .star-rating .star {
            width: 44px; height: 44px;
            cursor: pointer;
            transition: transform 0.15s ease, filter 0.2s ease;
        }
        .star-rating .star:hover { transform: scale(1.18); }
        .star-rating .star:active { transform: scale(0.95); }
        .star-rating .star svg {
            width: 100%; height: 100%;
            fill: #333645;
            transition: fill 0.2s ease, filter 0.2s ease;
        }
        .star-rating .star.active svg {
            fill: var(--accent);
            filter: drop-shadow(0 0 8px var(--accent-glow));
        }
        .star-rating .star.hover-preview svg {
            fill: rgba(245,158,11,0.55);
        }

        .rating-label {
            font-size: 0.82rem;
            color: var(--muted);
            min-height: 20px;
            margin-bottom: 20px;
            transition: color 0.2s;
        }
        .rating-label.colored { color: var(--accent); font-weight: 600; }

        /* --- Inputs --- */
        .field { position: relative; margin-bottom: 16px; text-align: left; }
        .field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .field input,
        .field textarea {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--fg);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            resize: vertical;
        }
        .field input::placeholder,
        .field textarea::placeholder { color: #555770; }
        .field input:focus,
        .field textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
        .field textarea.ai-filled {
            border-color: rgba(139, 92, 246, 0.4);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        /* --- AI Suggestions Box --- */
        .ai-suggestions {
            display: none;
            text-align: left;
            margin-bottom: 16px;
            background: var(--ai-bg);
            border: 1px solid var(--ai-border);
            border-radius: 12px;
            padding: 14px;
            animation: aiFadeIn 0.35s ease-out;
        }
        .ai-suggestions.visible { display: block; }

        @keyframes aiFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .ai-header {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }
        .ai-sparkle {
            width: 16px; height: 16px;
            color: var(--ai-text);
            animation: sparklePulse 2s ease-in-out infinite;
        }
        @keyframes sparklePulse {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.15); }
        }
        .ai-header span {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--ai-text);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .ai-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 8px;
        }
        .ai-chip {
            background: var(--ai-chip-bg);
            border: 1px solid var(--ai-chip-border);
            color: #c4b5fd;
            font-size: 0.8rem;
            font-family: inherit;
            padding: 7px 12px;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s, transform 0.1s, color 0.15s;
            line-height: 1.3;
        }
        .ai-chip:hover {
            background: var(--ai-chip-hover);
            border-color: rgba(139, 92, 246, 0.35);
            transform: translateY(-1px);
            color: #ddd6fe;
        }
        .ai-chip:active { transform: translateY(0) scale(0.97); }
        .ai-chip.selected {
            background: rgba(139, 92, 246, 0.25);
            border-color: var(--ai-text);
            color: #fff;
        }

        .ai-more-btn {
            background: none;
            border: none;
            color: var(--ai-text);
            font-size: 0.76rem;
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            padding: 4px 0;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.15s;
        }
        .ai-more-btn:hover { color: #c4b5fd; }
        .ai-more-btn svg {
            width: 12px; height: 12px;
            transition: transform 0.3s;
        }
        .ai-more-btn:hover svg { transform: rotate(180deg); }

        /* --- Button --- */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent), #d97706);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 16px var(--accent-glow);
        }
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(245,158,11,0.35);
        }
        .btn-submit:active:not(:disabled) { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

        .btn-submit .spinner {
            display: none;
            width: 20px; height: 20px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            position: absolute;
            top: 50%; left: 50%;
            margin: -10px 0 0 -10px;
        }
        .btn-submit.loading .btn-text { opacity: 0; }
        .btn-submit.loading .spinner { display: block; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* --- Redirecting Overlay --- */
        .redirect-overlay {
            display: none;
            position: absolute;
            inset: 0;
            background: var(--card-bg);
            border-radius: 20px;
            z-index: 10;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            animation: cardIn 0.35s ease-out;
        }
        .redirect-overlay .g-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: rgba(245,158,11,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .redirect-overlay .g-icon svg { width: 32px; height: 32px; }
        .redirect-overlay h3 { color: var(--fg); font-size: 1.15rem; }
        .redirect-overlay p { color: var(--muted); font-size: 0.85rem; }

        .redirect-dots {
            display: flex; gap: 6px;
        }
        .redirect-dots span {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--accent);
            animation: dotPulse 1.2s ease-in-out infinite;
        }
        .redirect-dots span:nth-child(2) { animation-delay: 0.15s; }
        .redirect-dots span:nth-child(3) { animation-delay: 0.3s; }

        @keyframes dotPulse {
            0%, 80%, 100% { opacity: 0.25; transform: scale(0.8); }
            40% { opacity: 1; transform: scale(1.1); }
        }

        /* --- Thank You Screen --- */
        .thank-you-screen {
            display: none;
            animation: fadeUp 0.45s ease-out;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .thank-you-screen .check-circle {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: rgba(34,197,94,0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .thank-you-screen .check-circle svg {
            width: 36px; height: 36px;
            stroke: var(--success);
            stroke-width: 2.5;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .thank-you-screen h2 { margin-bottom: 8px; }
        .thank-you-screen p { color: var(--muted); font-size: 0.9rem; line-height: 1.5; }

        /* --- Toast --- */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #dc2626;
            color: #fff;
            padding: 12px 22px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 500;
            box-shadow: 0 8px 24px rgba(220,38,38,0.3);
            z-index: 999;
            opacity: 0;
            transition: transform 0.35s ease, opacity 0.35s ease;
            pointer-events: none;
            max-width: 90vw;
            text-align: center;
        }
        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        @media (max-width: 460px) {
            .card { padding: 28px 20px 24px; }
            .star-rating .star { width: 38px; height: 38px; }
            h2 { font-size: 1.25rem; }
            .ai-chip { font-size: 0.76rem; padding: 6px 10px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>

<div class="card">

    <!-- Redirecting overlay -->
    <div class="redirect-overlay" id="redirectOverlay">
        <div class="g-icon">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18A10.96 10.96 0 0 0 1 12c0 1.77.42 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
        </div>
        <h3>Redirecting to Google...</h3>
        <p>Your review is being posted on Google</p>
        <div class="redirect-dots">
            <span></span><span></span><span></span>
        </div>
    </div>

    <!-- ========== FORM SCREEN ========== -->
    <div id="formScreen">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>

        <h2>{{ $qrCode->name }}</h2>
        <p class="subtitle">How was your experience? Your feedback helps us improve.</p>

        <div class="star-rating" id="starRating" role="radiogroup" aria-label="Rating">
            <div class="star" data-value="1" role="radio" aria-checked="false" aria-label="1 star" tabindex="0">
                <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div class="star" data-value="2" role="radio" aria-checked="false" aria-label="2 stars" tabindex="0">
                <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div class="star" data-value="3" role="radio" aria-checked="false" aria-label="3 stars" tabindex="0">
                <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div class="star" data-value="4" role="radio" aria-checked="false" aria-label="4 stars" tabindex="0">
                <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div class="star" data-value="5" role="radio" aria-checked="false" aria-label="5 stars" tabindex="0">
                <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
        </div>
        <div class="rating-label" id="ratingLabel">Tap to rate</div>

        <input type="hidden" name="rating" id="ratingInput" value="">

        <div class="field">
            <label for="customerName">Your Name <span style="font-weight:400;text-transform:none;letter-spacing:0">(optional)</span></label>
            <input type="text" id="customerName" name="customer_name" placeholder="John Doe" maxlength="255" autocomplete="name">
        </div>

        <!-- AI Suggestion Box -->
        <div class="ai-suggestions" id="aiSuggestions">
            <div class="ai-header">
                <svg class="ai-sparkle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/>
                </svg>
                <span>AI Suggestions</span>
            </div>
            <div class="ai-chips" id="aiChips"></div>
            <button type="button" class="ai-more-btn" id="aiMoreBtn" onclick="loadMoreSuggestions()">
                <span>More suggestions</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
        </div>

        <div class="field">
            <label for="reviewText">Your Feedback</label>
            <textarea id="reviewText" name="review_text" rows="3" placeholder="Tell us what went well or what could be better..." maxlength="1000"></textarea>
        </div>

        <button type="button" class="btn-submit" id="submitBtn" onclick="submitReview()">
            <span class="spinner"></span>
            <span class="btn-text">Submit Feedback</span>
        </button>
    </div>

    <!-- ========== THANK YOU SCREEN ========== -->
    <div class="thank-you-screen" id="thankYouScreen">
        <div class="check-circle">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h2>Thank You!</h2>
        <p>We appreciate your honest feedback. It helps us serve you better next time.</p>
    </div>

</div>

<div class="toast" id="toast"></div>

<script>
    let selectedRating = 0;
    let currentSuggestionSet = 0;
    let selectedChipEl = null;
    const ratingLabels = ['', 'Terrible', 'Poor', 'Okay', 'Great', 'Awesome!'];

    const stars = document.querySelectorAll('#starRating .star');
    const ratingLabel = document.getElementById('ratingLabel');
    const submitBtn = document.getElementById('submitBtn');
    const formScreen = document.getElementById('formScreen');
    const thankYouScreen = document.getElementById('thankYouScreen');
    const redirectOverlay = document.getElementById('redirectOverlay');
    const toast = document.getElementById('toast');
    const aiSuggestions = document.getElementById('aiSuggestions');
    const aiChipsContainer = document.getElementById('aiChips');
    const reviewTextarea = document.getElementById('reviewText');

    // === AI Suggestion Data ===
    // Har rating ke liye 3 sets hain — "More" pe next set aata hai
    const aiData = {
        1: [
            ["Very disappointing experience. Would not recommend.", "Staff was unresponsive and unhelpful.", "Waited too long, service was terrible."],
            ["Worst experience I've had here. Nothing went right.", "Found the place unhygienic and poorly managed.", "Left without getting what I came for."],
            ["Completely unacceptable. Needs major improvement.", "Rude behavior from the staff, very upsetting.", "Avoid this place, save your time and money."]
        ],
        2: [
            ["Below average experience. Expected much better.", "Service was slow and staff seemed disinterested.", "Few things were okay but mostly disappointing."],
            ["Not worth the money. Needs improvement.", "Food quality has gone down significantly.", "Staff needs training, very unprofessional."],
            ["Mediocre at best. Won't be returning soon.", "Cleanliness was an issue, needs attention.", "Delayed service and average quality overall."]
        ],
        3: [
            ["Decent experience, nothing extraordinary though.", "Average service, could be better.", "It was okay. Some things were good, some not."],
            ["Mixed feelings. Staff was polite but slow.", "Fair experience. Has potential to improve.", "Not bad but not great either. Room for improvement."],
            ["Standard experience, nothing to complain much about.", "Average place for a one-time visit.", "Some positives but consistency is missing."]
        ],
        4: [
            ["Really good experience! Would visit again.", "Great service and quality. Keep it up!", "Almost perfect, minor things to improve."],
            ["Loved the experience. Staff was friendly and helpful.", "Consistent quality as always. Impressed!", "Very satisfying visit. Highly recommend."],
            ["Excellent service and ambiance. Nice work!", "Almost everything was perfect. Great job team.", "Really enjoyed it. Will definitely come back."]
        ],
        5: [
            ["Absolutely amazing! Best experience ever!", "Outstanding service, quality, and ambiance!", "Perfect in every way. Highly recommended!"],
            ["Exceeded all my expectations. Truly exceptional!", "10/10 experience. Will keep coming back!", "Incredible! One of the best I've been to."],
            ["Flawless experience from start to finish!", "Mind-blowing service. You guys are the best!", "Couldn't have asked for anything more. Superb!"]
        ]
    };

    // === Star Clicks ===
    stars.forEach(star => {
        star.addEventListener('click', () => {
            selectedRating = parseInt(star.dataset.value);
            document.getElementById('ratingInput').value = selectedRating;
            updateStars();
            showAiSuggestions();
        });

        star.addEventListener('mouseenter', () => {
            const hoverVal = parseInt(star.dataset.value);
            stars.forEach(s => {
                const v = parseInt(s.dataset.value);
                s.classList.toggle('hover-preview', v <= hoverVal && v > selectedRating);
            });
            ratingLabel.textContent = ratingLabels[hoverVal];
            ratingLabel.classList.add('colored');
        });

        star.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); star.click(); }
        });
    });

    document.getElementById('starRating').addEventListener('mouseleave', () => {
        stars.forEach(s => s.classList.remove('hover-preview'));
        if (selectedRating > 0) {
            ratingLabel.textContent = ratingLabels[selectedRating];
            ratingLabel.classList.add('colored');
        } else {
            ratingLabel.textContent = 'Tap to rate';
            ratingLabel.classList.remove('colored');
        }
    });

    function updateStars() {
        stars.forEach(s => {
            const v = parseInt(s.dataset.value);
            s.classList.toggle('active', v <= selectedRating);
            s.setAttribute('aria-checked', v === selectedRating ? 'true' : 'false');
        });
        ratingLabel.textContent = ratingLabels[selectedRating];
        ratingLabel.classList.add('colored');
    }

    // === AI Suggestions Logic ===
    function showAiSuggestions() {
        currentSuggestionSet = 0;
        renderChips();
        aiSuggestions.classList.add('visible');

        // Re-trigger animation
        aiSuggestions.style.animation = 'none';
        aiSuggestions.offsetHeight; // force reflow
        aiSuggestions.style.animation = '';
    }

    function renderChips() {
        const set = aiData[selectedRating][currentSuggestionSet];
        aiChipsContainer.innerHTML = '';

        set.forEach(text => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'ai-chip';
            chip.textContent = text;
            chip.addEventListener('click', () => selectChip(chip, text));
            aiChipsContainer.appendChild(chip);
        });

        // "More" button hide karo agar last set hai
        const totalSets = aiData[selectedRating].length;
        document.getElementById('aiMoreBtn').style.display =
            (currentSuggestionSet >= totalSets - 1) ? 'none' : 'inline-flex';
    }

    function selectChip(chipEl, text) {
        // Pehle wala deselect
        if (selectedChipEl) selectedChipEl.classList.remove('selected');

        // Agar same chip dubara click kiya → deselect karo
        if (selectedChipEl === chipEl) {
            selectedChipEl = null;
            reviewTextarea.value = '';
            reviewTextarea.classList.remove('ai-filled');
            return;
        }

        chipEl.classList.add('selected');
        selectedChipEl = chipEl;
        reviewTextarea.value = text;
        reviewTextarea.classList.add('ai-filled');

        // Textarea pe focus with cursor at end
        reviewTextarea.focus();
        reviewTextarea.setSelectionRange(text.length, text.length);
    }

    function loadMoreSuggestions() {
        const totalSets = aiData[selectedRating].length;
        currentSuggestionSet = (currentSuggestionSet + 1) % totalSets;

        // Chip selection reset
        selectedChipEl = null;

        // Animate chips out then in
        aiChipsContainer.style.opacity = '0';
        aiChipsContainer.style.transform = 'translateY(6px)';

        setTimeout(() => {
            renderChips();
            aiChipsContainer.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            aiChipsContainer.style.opacity = '1';
            aiChipsContainer.style.transform = 'translateY(0)';
        }, 150);
    }

    // Agar user manually type kare toh chip selection hata do
    reviewTextarea.addEventListener('input', () => {
        if (selectedChipEl) {
            selectedChipEl.classList.remove('selected');
            selectedChipEl = null;
        }
        reviewTextarea.classList.remove('ai-filled');
    });

    // === AJAX Submit ===
    async function submitReview() {
        if (selectedRating === 0) {
            showToast('Please select a rating first.');
            return;
        }

        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route("review.feedback", $qrCode->slug) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    rating: selectedRating,
                    review_text: reviewTextarea.value.trim() || null,
                    customer_name: document.getElementById('customerName').value.trim() || null
                })
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    const msg = Object.values(data.errors)[0][0];
                    showToast(msg);
                } else {
                    showToast(data.message || 'Something went wrong.');
                }
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
                return;
            }

            if (data.action === 'google_redirect' && data.url) {
                redirectOverlay.style.display = 'flex';
                setTimeout(() => {
                    window.location.href = data.url;
                }, 1500);
            } else {
                formScreen.style.display = 'none';
                thankYouScreen.style.display = 'block';
            }

        } catch (err) {
            console.error(err);
            showToast('Network error. Please check your connection.');
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }
    }

    // === Toast ===
    let toastTimer = null;
    function showToast(message) {
        toast.textContent = message;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 3500);
    }
</script>

</body>
</html>