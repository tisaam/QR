<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Your Feedback</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 90%; max-width: 400px; text-align: center; }
        .stars { font-size: 40px; color: #ddd; cursor: pointer; margin: 20px 0; }
        .stars .active { color: #f1c40f; }
        textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; resize: none; }
        input[type="text"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background: #3498db; color: white; border: none; padding: 12px 20px; border-radius: 5px; font-size: 16px; cursor: pointer; width: 100%; }
        button:hover { background: #2980b9; }
    /* ==========================================================
   REVIEW PAGE - DARK MODE ONLY
========================================================== */

body.dark-mode{
    background:#0f172a !important;
    color:#f8fafc !important;
}

/* Card */
body.dark-mode .card{
    background:#1e293b !important;
    border:1px solid #334155 !important;
    box-shadow:none !important;
    color:#f8fafc !important;
}

/* Headings & Text */
body.dark-mode .card h1,
body.dark-mode .card h2,
body.dark-mode .card h3,
body.dark-mode .card p,
body.dark-mode .card label{
    color:#f8fafc !important;
}

/* Stars */
body.dark-mode .stars{
    color:#475569 !important;
}

body.dark-mode .stars .active{
    color:#fbbf24 !important;
}

/* Textarea */
body.dark-mode textarea{
    background:#0f172a !important;
    border:1px solid #334155 !important;
    color:#f8fafc !important;
}

body.dark-mode textarea::placeholder{
    color:#94a3b8 !important;
}

body.dark-mode textarea:focus{
    border-color:#6366f1 !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.25) !important;
    outline:none;
}

/* Text Input */
body.dark-mode input[type="text"]{
    background:#0f172a !important;
    border:1px solid #334155 !important;
    color:#f8fafc !important;
}

body.dark-mode input[type="text"]::placeholder{
    color:#94a3b8 !important;
}

body.dark-mode input[type="text"]:focus{
    border-color:#6366f1 !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.25) !important;
    outline:none;
}

/* Button */
body.dark-mode button{
    background:#6366f1 !important;
    color:#ffffff !important;
}

body.dark-mode button:hover{
    background:#4f46e5 !important;
}
    </style>
</head>
<body>

<div class="card">
    <h2>How was your experience?</h2>
    <p>Please rate us below:</p>

   <form action="{{ route('review.feedback', $qrCode->slug) }}" method="POST">
        @csrf
        <input type="hidden" name="qr_code_id" value="{{ $qrCode->id }}">

        <!-- Star Rating -->
        <div class="stars" id="star-container">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <input type="hidden" name="rating" id="rating-input" value="0" required>

        <input type="text" name="customer_name" placeholder="Your Name (Optional)">

        <textarea name="review_text" rows="4" placeholder="Tell us more about your experience (Optional)"></textarea>

        <button type="submit">Submit Feedback</button>
    </form>
</div>

<script>
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = parseInt(star.getAttribute('data-value'));
            ratingInput.value = value;
            
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
</script>

</body>
</html>