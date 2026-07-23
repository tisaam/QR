@extends('layouts.landing')

@section('title', 'Leave a Review')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-light: #eef2ff;
        --primary-glow: rgba(99,102,241,0.25);
        --accent: #f59e0b;
        --accent-light: #fffbeb;
        --success: #10b981;
        --success-dark: #059669;
        --success-light: #ecfdf5;
        --danger: #ef4444;
        --danger-light: #fef2f2;
        --gray-50: #fafafa;
        --gray-100: #f4f4f5;
        --gray-200: #e4e4e7;
        --gray-300: #d4d4d8;
        --gray-400: #a1a1aa;
        --gray-500: #71717a;
        --gray-600: #52525b;
        --gray-700: #3f3f46;
        --gray-800: #27272a;
        --gray-900: #18181b;
        --radius: 24px;
        --radius-sm: 14px;
        --radius-full: 9999px;
        --shadow-soft: 0 2px 20px rgba(0,0,0,0.04);
        --shadow-card: 0 8px 40px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.02);
        --shadow-glow: 0 0 60px rgba(99,102,241,0.12);
        --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --bounce: 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        -webkit-font-smoothing: antialiased;
        background: #f8f9fb;
        overflow-x: hidden;
    }

    /* ══════════ ANIMATED BACKGROUND ══════════ */
    .review-page-bg {
        min-height: 100vh;
        background: linear-gradient(160deg, #f0f0ff 0%, #f8f9fb 30%, #fff 60%, #fefce8 100%);
        position: relative;
        overflow: hidden;
    }

    .bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.5;
        pointer-events: none;
        animation: orbFloat 20s ease-in-out infinite;
    }

    .bg-orb--1 {
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(99,102,241,0.15), transparent 70%);
        top: -150px; right: -100px;
        animation-delay: 0s;
    }

    .bg-orb--2 {
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(245,158,11,0.1), transparent 70%);
        bottom: -100px; left: -80px;
        animation-delay: -7s;
    }

    .bg-orb--3 {
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(16,185,129,0.08), transparent 70%);
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        animation-delay: -14s;
    }

    .bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(99,102,241,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99,102,241,0.03) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none;
    }

    @keyframes orbFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25% { transform: translate(30px, -20px) scale(1.05); }
        50% { transform: translate(-20px, 30px) scale(0.95); }
        75% { transform: translate(20px, 20px) scale(1.02); }
    }

    /* ══════════ LAYOUT ══════════ */
    .review-page {
        max-width: 520px;
        margin: 0 auto;
        padding: 32px 20px 60px;
        position: relative;
        z-index: 2;
    }

    /* ══════════ BRAND HEADER ══════════ */
    .brand-header {
        text-align: center;
        margin-bottom: 32px;
        animation: fadeDown 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .brand-avatar {
        position: relative;
        display: inline-block;
        margin-bottom: 16px;
    }

    .brand-avatar-ring {
        width: 76px; height: 76px;
        border-radius: 22px;
        background: linear-gradient(135deg, var(--primary), #818cf8, var(--accent));
        padding: 3px;
        animation: ringRotate 8s linear infinite;
        box-shadow: 0 8px 32px var(--primary-glow);
    }

    @keyframes ringRotate {
        from { filter: hue-rotate(0deg); }
        to { filter: hue-rotate(360deg); }
    }

    .brand-avatar-inner {
        width: 100%; height: 100%;
        border-radius: 19px;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 900;
        color: #fff;
        letter-spacing: -0.02em;
    }

    .brand-avatar-badge {
        position: absolute;
        bottom: -4px; right: -4px;
        width: 26px; height: 26px;
        border-radius: 8px;
        background: var(--success);
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #fff;
        box-shadow: 0 2px 8px rgba(16,185,129,0.4);
    }

    .brand-name {
        font-size: 22px;
        font-weight: 800;
        color: var(--gray-900);
        letter-spacing: -0.03em;
        line-height: 1.2;
    }

    .brand-tagline {
        font-size: 13px;
        color: var(--gray-400);
        margin-top: 4px;
        font-weight: 500;
    }

    /* ══════════ PROGRESS BAR (New Design) ══════════ */
    .progress-bar-wrapper {
        margin-bottom: 28px;
        animation: fadeDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
    }

    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .progress-step {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: var(--radius-full);
        font-size: 11px;
        font-weight: 700;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        transition: all var(--transition);
        background: transparent;
    }

    .progress-step.active {
        color: var(--primary-dark);
        background: var(--primary-light);
    }

    .progress-step.completed {
        color: var(--success-dark);
        background: var(--success-light);
    }

    .progress-step-num {
        width: 22px; height: 22px;
        border-radius: 7px;
        background: var(--gray-200);
        color: var(--gray-400);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
        transition: all var(--transition);
    }

    .progress-step.active .progress-step-num {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 2px 8px var(--primary-glow);
    }

    .progress-step.completed .progress-step-num {
        background: var(--success);
        color: #fff;
    }

    .progress-connector {
        width: 20px; height: 2px;
        background: var(--gray-200);
        border-radius: 2px;
        transition: background var(--transition);
    }

    .progress-connector.completed {
        background: var(--success);
    }

    /* ══════════ GLASS CARD ══════════ */
    .review-panel {
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255,255,255,0.9);
        border-radius: var(--radius);
        box-shadow: var(--shadow-card);
        padding: 32px 24px;
        position: relative;
        overflow: hidden;
        animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .review-panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 10%, rgba(255,255,255,1) 50%, transparent 90%);
    }

    .review-panel::after {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle at 30% 20%, rgba(99,102,241,0.03), transparent 50%);
        pointer-events: none;
    }

    /* ══════════ TYPOGRAPHY ══════════ */
    .review-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--gray-900);
        text-align: center;
        letter-spacing: -0.03em;
        line-height: 1.2;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .review-subtitle {
        color: var(--gray-400);
        font-size: 14px;
        text-align: center;
        font-weight: 500;
        margin-bottom: 32px;
        position: relative;
        z-index: 1;
    }

    /* ══════════ EMOJI RATING (New!) ══════════ */
    .emoji-rating {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .emoji-btn {
        width: 56px; height: 56px;
        border-radius: 16px;
        border: 2px solid var(--gray-100);
        background: var(--gray-50);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        transition: all var(--bounce);
        position: relative;
        overflow: hidden;
    }

    .emoji-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 14px;
        opacity: 0;
        transition: opacity var(--transition);
    }

    .emoji-btn:hover {
        transform: translateY(-4px) scale(1.05);
        border-color: var(--gray-200);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .emoji-btn.selected {
        transform: translateY(-4px) scale(1.1);
        border-color: transparent;
        box-shadow: 0 8px 28px rgba(0,0,0,0.12);
    }

    .emoji-btn[data-value="1"].selected { background: #fef2f2; border-color: #fca5a5; }
    .emoji-btn[data-value="2"].selected { background: #fff7ed; border-color: #fdba74; }
    .emoji-btn[data-value="3"].selected { background: #fefce8; border-color: #fde047; }
    .emoji-btn[data-value="4"].selected { background: #ecfdf5; border-color: #6ee7b7; }
    .emoji-btn[data-value="5"].selected { background: #eef2ff; border-color: #a5b4fc; }

    .emoji-btn:active { transform: translateY(-2px) scale(0.95); }

    /* Star rating hidden, replaced by emoji */
    .star-rating { display: none; }

    /* ══════════ RATING FEEDBACK TEXT ══════════ */
    .rating-feedback {
        text-align: center;
        margin-bottom: 28px;
        min-height: 48px;
        position: relative;
        z-index: 1;
    }

    .rating-emoji-big {
        font-size: 32px;
        display: block;
        margin-bottom: 4px;
        animation: popIn 0.4s var(--bounce);
    }

    .rating-label {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-800);
        transition: all var(--transition);
    }

    .rating-hint {
        font-size: 12px;
        color: var(--gray-400);
        margin-top: 2px;
        font-weight: 500;
    }

    .rating-placeholder {
        font-size: 13px;
        color: var(--gray-300);
        font-weight: 500;
        padding-top: 12px;
    }

    /* ══════════ LANGUAGE PILLS ══════════ */
    .lang-section {
        margin-bottom: 28px;
        position: relative;
        z-index: 1;
    }

    .lang-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--gray-400);
        text-align: center;
        margin-bottom: 10px;
    }

    .lang-actions {
        display: flex;
        justify-content: center;
        gap: 6px;
        background: var(--gray-100);
        border-radius: var(--radius-full);
        padding: 4px;
        width: fit-content;
        margin: 0 auto;
    }

    .lang-pill {
        border: none;
        background: transparent;
        color: var(--gray-500);
        border-radius: var(--radius-full);
        padding: 8px 18px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all var(--transition);
        font-family: inherit;
        white-space: nowrap;
    }

    .lang-pill:hover { color: var(--gray-700); }

    .lang-pill.active {
        background: #fff;
        color: var(--gray-900);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04);
    }

    /* ══════════ BUTTONS ══════════ */
    .btn-primary,
    .btn-success {
        width: 100%;
        border-radius: 14px;
        padding: 16px 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all var(--transition);
        border: none;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        letter-spacing: -0.01em;
        position: relative;
        z-index: 1;
    }

    .btn-primary {
        background: var(--gray-900);
        color: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .btn-primary:hover:not(:disabled) {
        background: var(--gray-800);
        box-shadow: 0 8px 28px rgba(0,0,0,0.2);
        transform: translateY(-1px);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #34d399);
        color: #fff;
        box-shadow: 0 4px 16px rgba(16,185,129,0.3);
    }

    .btn-success:hover:not(:disabled) {
        box-shadow: 0 8px 28px rgba(16,185,129,0.4);
        transform: translateY(-1px);
    }

    .btn-primary:active:not(:disabled),
    .btn-success:active:not(:disabled) {
        transform: translateY(0) scale(0.98);
    }

    .btn-disabled {
        opacity: 0.35 !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-link {
        background: transparent;
        color: var(--gray-400);
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        padding: 12px;
        transition: color var(--transition);
        font-family: inherit;
        width: 100%;
        text-align: center;
    }

    .btn-link:hover { color: var(--gray-700); }

    /* ══════════ BACK BUTTON ══════════ */
    .btn-back {
        background: none;
        border: none;
        color: var(--gray-400);
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        padding: 0 0 20px 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all var(--transition);
        font-family: inherit;
        position: relative;
        z-index: 1;
    }

    .btn-back:hover { color: var(--gray-700); transform: translateX(-2px); }

    /* ══════════ SUGGESTIONS ══════════ */
    .suggestions-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--gray-400);
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .suggestions-container {
        display: grid;
        gap: 8px;
        margin-bottom: 16px;
        max-height: 220px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--gray-200) transparent;
        position: relative;
        z-index: 1;
    }

    .suggestions-container::-webkit-scrollbar { width: 3px; }
    .suggestions-container::-webkit-scrollbar-track { background: transparent; }
    .suggestions-container::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 3px; }

    .suggestion-chip {
        text-align: left;
        border: 1.5px solid var(--gray-100);
        background: var(--gray-50);
        color: var(--gray-600);
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        width: 100%;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all var(--transition);
        font-family: inherit;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .suggestion-chip-icon {
        width: 20px; height: 20px;
        border-radius: 6px;
        border: 2px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
        transition: all var(--transition);
        font-size: 9px;
        color: transparent;
    }

    .suggestion-chip:hover {
        border-color: var(--primary);
        background: var(--primary-light);
        color: var(--primary-dark);
    }

    .suggestion-chip.selected {
        border-color: var(--primary);
        background: var(--primary-light);
        color: var(--primary-dark);
        font-weight: 600;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
    }

    .suggestion-chip.selected .suggestion-chip-icon {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    /* ══════════ DIVIDER ══════════ */
    .divider {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 18px 0;
        position: relative;
        z-index: 1;
    }

    .divider-line {
        flex: 1;
        height: 1px;
        background: var(--gray-100);
    }

    .divider-text {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--gray-300);
        white-space: nowrap;
    }

    /* ══════════ INPUTS ══════════ */
    .textarea-input,
    .text-input {
        width: 100%;
        border: 1.5px solid var(--gray-100);
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        font-size: 13px;
        color: var(--gray-800);
        background: var(--gray-50);
        outline: none;
        transition: all var(--transition);
        font-family: inherit;
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }

    .textarea-input:focus,
    .text-input:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
    }

    .textarea-input { min-height: 100px; resize: vertical; margin-bottom: 4px; }
    .text-input { margin-bottom: 20px; }

    .textarea-input::placeholder,
    .text-input::placeholder {
        color: var(--gray-300);
        font-weight: 400;
    }

    .char-count {
        font-size: 10px;
        color: var(--gray-300);
        text-align: right;
        margin-bottom: 16px;
        font-weight: 600;
        letter-spacing: 0.02em;
        position: relative;
        z-index: 1;
    }

    /* ══════════ STATUS CARD ══════════ */
    .review-status-card { text-align: center; }

    .status-icon-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 24px;
    }

    .review-status-icon {
        width: 80px; height: 80px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        position: relative;
        z-index: 1;
    }

    .review-status-icon.success {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        color: var(--success);
        box-shadow: 0 8px 32px rgba(16,185,129,0.2);
    }

    .review-status-icon.danger {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: var(--danger);
        box-shadow: 0 8px 32px rgba(239,68,68,0.15);
    }

    .review-status-icon.love {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        color: var(--primary);
        box-shadow: 0 8px 32px rgba(99,102,241,0.15);
    }

    .status-ring {
        position: absolute;
        inset: -8px;
        border-radius: 28px;
        border: 2px dashed currentColor;
        opacity: 0.12;
        animation: spin 25s linear infinite;
    }

    .review-status-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 8px;
        letter-spacing: -0.03em;
        position: relative;
        z-index: 1;
    }

    .review-status-text {
        color: var(--gray-500);
        font-size: 14px;
        line-height: 1.7;
        max-width: 300px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    /* ══════════ GOOGLE BUTTON ══════════ */
    .btn-google {
        width: 100%;
        border-radius: 14px;
        padding: 15px 20px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all var(--transition);
        border: 1.5px solid var(--gray-100);
        background: #fff;
        color: var(--gray-700);
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        text-decoration: none;
        margin-top: 28px;
        position: relative;
        z-index: 1;
    }

    .btn-google:hover {
        border-color: var(--gray-200);
        box-shadow: var(--shadow-soft);
        transform: translateY(-1px);
    }

    .btn-google:active { transform: translateY(0) scale(0.98); }

    .btn-google .g-icon { width: 20px; height: 20px; }

    /* ══════════ SKELETON LOADING ══════════ */
    .skeleton {
        background: linear-gradient(90deg, var(--gray-100) 25%, var(--gray-50) 50%, var(--gray-100) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: var(--radius-sm);
        height: 52px;
    }

    .skeleton + .skeleton { margin-top: 8px; }

    /* ══════════ CONFETTI CANVAS ══════════ */
    #confetti-canvas {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 9999;
    }

    /* ══════════ UTILITIES ══════════ */
    .hidden { display: none !important; }
    .mt-16 { margin-top: 16px; }

    /* ══════════ ANIMATIONS ══════════ */
    @keyframes fadeDown {
        from { opacity: 0; transform: translateY(-16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(24px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes popIn {
        0% { opacity: 0; transform: scale(0.5); }
        70% { transform: scale(1.15); }
        100% { opacity: 1; transform: scale(1); }
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }

    @media (min-width: 768px) {
        .review-page { padding: 48px 24px 80px; }
        .review-panel { padding: 40px 36px; }
        .emoji-btn { width: 60px; height: 60px; font-size: 28px; }
    }
</style>

<!-- Confetti Canvas -->
<canvas id="confetti-canvas"></canvas>

<div class="review-page-bg">
    <!-- Animated Background Elements -->
    <div class="bg-orb bg-orb--1"></div>
    <div class="bg-orb bg-orb--2"></div>
    <div class="bg-orb bg-orb--3"></div>
    <div class="bg-grid"></div>

<div class="review-page">

@if(isset($qrCode) && isset($business))

    {{-- Brand Header --}}
    <div class="brand-header">
        <div class="brand-avatar">
            <div class="brand-avatar-ring">
                <div class="brand-avatar-inner">
                    {{ strtoupper(substr($business->name ?? 'B', 0, 1)) }}
                </div>
            </div>
            <div class="brand-avatar-badge">
                <i class="fas fa-check"></i>
            </div>
        </div>
        <h1 class="brand-name">{{ $business->name ?? 'Business' }}</h1>
        <p class="brand-tagline">We'd love your feedback</p>
    </div>

    {{-- Progress Steps --}}
    <div class="progress-bar-wrapper">
        <div class="progress-steps">
            <div class="progress-step active" id="step-label-1">
                <span class="progress-step-num">1</span>
                <span>Rate</span>
            </div>
            <div class="progress-connector" id="conn-1"></div>
            <div class="progress-step" id="step-label-2">
                <span class="progress-step-num">2</span>
                <span>Review</span>
            </div>
            <div class="progress-connector" id="conn-2"></div>
            <div class="progress-step" id="step-label-3">
                <span class="progress-step-num">3</span>
                <span>Done</span>
            </div>
        </div>
    </div>

    {{-- Step 1: Rating --}}
    <div id="step-rating" class="review-panel">
        <h2 class="review-title">How was your experience?</h2>
        <p class="review-subtitle">Tap an emoji that matches your feeling</p>

        {{-- Emoji Rating (Visible) --}}
        <div class="emoji-rating" role="radiogroup" aria-label="Rating">
            <button type="button" class="emoji-btn" data-value="1" aria-label="1 star">😢</button>
            <button type="button" class="emoji-btn" data-value="2" aria-label="2 stars">😕</button>
            <button type="button" class="emoji-btn" data-value="3" aria-label="3 stars">😐</button>
            <button type="button" class="emoji-btn" data-value="4" aria-label="4 stars">😊</button>
            <button type="button" class="emoji-btn" data-value="5" aria-label="5 stars">🤩</button>
        </div>

        {{-- Hidden Star Rating (for data) --}}
        <div class="star-rating" role="radiogroup" aria-label="Rating">
            @for($i = 5; $i >= 1; $i--)
                <button type="button" class="star-button" data-value="{{ $i }}" aria-label="{{ $i }} star">
                    <i class="fas fa-star"></i>
                </button>
            @endfor
        </div>

        <div class="rating-feedback">
            <div id="rating-placeholder" class="rating-placeholder">Select a rating to continue</div>
            <div id="rating-active" class="hidden">
                <span id="rating-emoji-big" class="rating-emoji-big"></span>
                <div id="rating-label" class="rating-label"></div>
                <div id="rating-hint" class="rating-hint"></div>
            </div>
        </div>

        <div class="lang-section">
            <p class="lang-label">Review Language</p>
            <div class="lang-actions">
                <button type="button" class="lang-pill active" data-lang="en">English</button>
                <button type="button" class="lang-pill" data-lang="hi">हिन्दी</button>
                <button type="button" class="lang-pill" data-lang="gu">ગુજરાતી</button>
            </div>
        </div>

        <button id="btn-generate" class="btn-primary btn-disabled" disabled>
            <i class="fas fa-arrow-right" id="load-icon"></i>
            <span id="btn-generate-text">Continue</span>
        </button>
    </div>

    {{-- Step 2: Review --}}
    <div id="step-review" class="review-panel hidden">
        <button id="btn-back" type="button" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </button>

        <h2 class="review-title">Share your thoughts</h2>
        <p class="review-subtitle">Pick a suggestion or write your own</p>

        <p class="suggestions-label">Quick Suggestions</p>
        <div id="suggestions-container" class="suggestions-container"></div>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">or write your own</span>
            <div class="divider-line"></div>
        </div>

        <textarea id="custom-review" class="textarea-input" placeholder="Tell us more about your experience..." maxlength="500"></textarea>
        <p class="char-count"><span id="char-count-num">0</span> / 500</p>

        <input type="text" id="customer-name" class="text-input" placeholder="Your Name (optional)">

        <button id="btn-submit" class="btn-success btn-disabled" disabled>
            <i class="fas fa-paper-plane" id="submit-load-icon"></i>
            <span id="btn-submit-text">Submit Review</span>
        </button>
    </div>

    {{-- Step 3: Thank You --}}
    <div id="step-google" class="review-panel hidden review-status-card">
        <div class="status-icon-wrapper">
            <div class="review-status-icon success animate-pop">
                <i class="fas fa-check"></i>
            </div>
            <div class="status-ring" style="color: var(--success);"></div>
        </div>
        <h2 class="review-status-title">Thank You!</h2>
        <p class="review-status-text">Your review means a lot to us. Would you also share it on Google?</p>

        <a id="google-link" target="_blank" class="btn-google" href="#">
            <svg class="g-icon" viewBox="0 0 24 24" fill="none">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Review on Google
        </a>

        <button id="btn-skip" type="button" class="btn-link">No thanks, I'm done</button>
    </div>

@else
    {{-- Invalid QR --}}
    <div class="review-panel review-status-card" style="margin-top: 80px;">
        <div class="status-icon-wrapper">
            <div class="review-status-icon danger animate-pop">
                <i class="fas fa-link-slash"></i>
            </div>
            <div class="status-ring" style="color: var(--danger);"></div>
        </div>
        <h2 class="review-status-title">Invalid Link</h2>
        <p class="review-status-text">This QR code link is invalid, expired, or has been disabled by the business.</p>

        <a href="/" class="btn-primary mt-16" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:10px; width:100%; border-radius:14px; padding:16px 24px; font-size:14px; font-weight:700;">
            <i class="fas fa-home"></i> Go to Homepage
        </a>
    </div>
@endif

</div>
</div>
@endsection

@push('scripts')
<script>
// Safely get elements (returns null if doesn't exist)
const slug = @json($qrCode->slug ?? null);
const businessId = @json($business->id ?? 0);
const businessName = @json($business->name ?? 'Us');
const googlePlaceId = @json($business->google_place_id ?? '');

let selectedRating = 0;
let selectedLanguage = 'en';
let selectedReview = '';

const ratingData = {
    1: { emoji: '😢', label: 'Terrible', hint: 'We\'re sorry to hear that', color: '#ef4444' },
    2: { emoji: '😕', label: 'Poor', hint: 'We can do better', color: '#f97316' },
    3: { emoji: '😐', label: 'Average', hint: 'Room for improvement', color: '#eab308' },
    4: { emoji: '😊', label: 'Good', hint: 'Glad you enjoyed it', color: '#22c55e' },
    5: { emoji: '🤩', label: 'Excellent!', hint: 'That means the world to us!', color: '#6366f1' }
};

// Elements
const emojiBtns = document.querySelectorAll('.emoji-btn');
const ratingPlaceholder = document.getElementById('rating-placeholder');
const ratingActive = document.getElementById('rating-active');
const ratingEmojiBig = document.getElementById('rating-emoji-big');
const ratingLabel = document.getElementById('rating-label');
const ratingHint = document.getElementById('rating-hint');
const btnGenerate = document.getElementById('btn-generate');
const btnGenerateText = document.getElementById('btn-generate-text');
const loadIcon = document.getElementById('load-icon');
const suggestionsContainer = document.getElementById('suggestions-container');
const customReview = document.getElementById('custom-review');
const charCountNum = document.getElementById('char-count-num');
const btnSubmit = document.getElementById('btn-submit');
const btnSubmitText = document.getElementById('btn-submit-text');
const submitLoadIcon = document.getElementById('submit-load-icon');
const stepRating = document.getElementById('step-rating');
const stepReview = document.getElementById('step-review');
const stepGoogle = document.getElementById('step-google');
const btnBack = document.getElementById('btn-back');

const stepLabels = [
    document.getElementById('step-label-1'),
    document.getElementById('step-label-2'),
    document.getElementById('step-label-3')
];
const connectors = [document.getElementById('conn-1'), document.getElementById('conn-2')];

function setStep(step) {
    if(!stepLabels[0]) return;
    stepLabels.forEach((el, i) => {
        if(!el) return;
        el.classList.remove('active', 'completed');
        if (i + 1 < step) el.classList.add('completed');
        else if (i + 1 === step) el.classList.add('active');
    });
    connectors.forEach((el, i) => {
        if(!el) return;
        el.classList.toggle('completed', i + 1 < step);
    });
}

// Emoji Rating
if(emojiBtns.length > 0) {
    emojiBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.value, 10);
            emojiBtns.forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            
            const data = ratingData[selectedRating];
            if(ratingPlaceholder) ratingPlaceholder.classList.add('hidden');
            if(ratingActive) ratingActive.classList.remove('hidden');
            if(ratingEmojiBig) ratingEmojiBig.textContent = data.emoji;
            if(ratingLabel) { ratingLabel.textContent = data.label; ratingLabel.style.color = data.color; }
            if(ratingHint) ratingHint.textContent = data.hint;
            
            if(ratingEmojiBig) {
                ratingEmojiBig.style.animation = 'none';
                ratingEmojiBig.offsetHeight;
                ratingEmojiBig.style.animation = 'popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
            }
            
            if(btnGenerate) {
                btnGenerate.disabled = false;
                btnGenerate.classList.remove('btn-disabled');
            }
        });
    });
}

// Language
document.querySelectorAll('.lang-pill').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.lang-pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        selectedLanguage = this.dataset.lang;
    });
});

// Generate
if(btnGenerate) {
    btnGenerate.addEventListener('click', fetchSuggestions);
}

async function fetchSuggestions() {
    if (selectedRating === 0) return;
    if(!stepRating || !stepReview || !suggestionsContainer) return;

    stepRating.classList.add('hidden');
    stepReview.classList.remove('hidden');
    stepReview.style.animation = 'none';
    stepReview.offsetHeight;
    stepReview.style.animation = 'cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
    setStep(2);

    suggestionsContainer.innerHTML = '';
    for (let i = 0; i < 3; i++) {
        const skel = document.createElement('div');
        skel.className = 'skeleton';
        suggestionsContainer.appendChild(skel);
    }

    if(loadIcon) loadIcon.className = 'fas fa-spinner fa-spin';
    if(btnGenerateText) btnGenerateText.textContent = 'Generating...';

    try {
        const response = await fetch('/ai/generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ business_id: businessId, rating: selectedRating, language: selectedLanguage }),
        });

        const data = await response.json();
        suggestionsContainer.innerHTML = '';

        if (data.success) {
            const reviews = Array.isArray(data.reviews) ? data.reviews.slice(0, 4) : [data.review];
            reviews.forEach((text, index) => {
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'suggestion-chip';
                chip.style.animation = `slideUp 0.35s ease-out ${index * 0.07}s both`;
                chip.innerHTML = `<span class="suggestion-chip-icon"><i class="fas fa-check"></i></span><span>${text}</span>`;
                chip.addEventListener('click', function() {
                    document.querySelectorAll('.suggestion-chip').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedReview = text;
                    if(customReview) customReview.value = selectedReview;
                    if(charCountNum) charCountNum.textContent = selectedReview.length;
                    enableSubmit();
                });
                suggestionsContainer.appendChild(chip);
            });
        } else {
            suggestionsContainer.innerHTML = `<div style="text-align:center; padding:24px; color:var(--danger); font-size:13px;"><i class="fas fa-exclamation-circle" style="font-size:22px; display:block; margin-bottom:10px;"></i>${data.message || 'Failed to generate reviews.'}</div>`;
        }
    } catch (err) {
        suggestionsContainer.innerHTML = `<div style="text-align:center; padding:24px; color:var(--danger); font-size:13px;"><i class="fas fa-wifi" style="font-size:22px; display:block; margin-bottom:10px;"></i>Network error. Please try again.</div>`;
    }

    if(loadIcon) loadIcon.className = 'fas fa-arrow-right';
    if(btnGenerateText) btnGenerateText.textContent = 'Continue';
}

// Back
if(btnBack) {
    btnBack.addEventListener('click', () => {
        if(!stepReview || !stepRating) return;
        stepReview.classList.add('hidden');
        stepRating.classList.remove('hidden');
        stepRating.style.animation = 'none';
        stepRating.offsetHeight;
        stepRating.style.animation = 'cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
        setStep(1);
    });
}

// Textarea
function enableSubmit() {
    if(!btnSubmit) return;
    const hasText = customReview.value.trim().length >= 3;
    btnSubmit.disabled = !hasText;
    btnSubmit.classList.toggle('btn-disabled', !hasText);
}

if(customReview) {
    customReview.addEventListener('input', () => {
        selectedReview = customReview.value;
        if(charCountNum) charCountNum.textContent = selectedReview.length;
        document.querySelectorAll('.suggestion-chip').forEach(c => c.classList.remove('selected'));
        enableSubmit();
    });
}

// ══════════ REAL SUBMIT REVIEW ══════════
if(btnSubmit) {
    btnSubmit.addEventListener('click', function() {
        if (!selectedReview || selectedReview.trim().length < 3) return;
        
        this.disabled = true;
        this.classList.add('btn-disabled');
        if(submitLoadIcon) submitLoadIcon.className = 'fas fa-spinner fa-spin';
        if(btnSubmitText) btnSubmitText.textContent = 'Submitting...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

        fetch(`/r/${slug}/submit-review`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                rating: selectedRating,
                 review_text: selectedReview,
                customer_name: document.getElementById('customer-name') ? document.getElementById('customer-name').value : '',
                language: selectedLanguage
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP error ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                if(submitLoadIcon) submitLoadIcon.className = 'fas fa-check';
                if(btnSubmitText) btnSubmitText.textContent = 'Submitted!';

                setTimeout(() => {
                    if(stepReview) stepReview.classList.add('hidden');
                    if(stepGoogle) {
                        stepGoogle.classList.remove('hidden');
                        stepGoogle.style.animation = 'none';
                        stepGoogle.offsetHeight;
                        stepGoogle.style.animation = 'cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                    }
                    setStep(3);

                    const googleLink = document.getElementById('google-link');
                    if (googleLink) {
                        if (googlePlaceId) {
                            googleLink.href = `https://search.google.com/local/writereview?placeid=${googlePlaceId}`;
                        } else {
                            googleLink.style.display = 'none';
                        }
                    }

                    launchConfetti();
                    
                    if(submitLoadIcon) submitLoadIcon.className = 'fas fa-paper-plane';
                    if(btnSubmitText) btnSubmitText.textContent = 'Submit Review';
                    if(btnSubmit) { btnSubmit.disabled = false; btnSubmit.classList.remove('btn-disabled'); }
                }, 500);
            } else {
                alert(data.message || 'Something went wrong!');
                if(submitLoadIcon) submitLoadIcon.className = 'fas fa-paper-plane';
                if(btnSubmitText) btnSubmitText.textContent = 'Submit Review';
                this.disabled = false;
                this.classList.remove('btn-disabled');
            }
        })
                .catch(async err => {
            let errorMsg = err.message;
            try {
                if (err.response) {
                    const data = await err.response.json();
                    errorMsg = data.message || JSON.stringify(data);
                }
            } catch(e) {}
            
            alert("SERVER ERROR:\n" + errorMsg);
            
            if(submitLoadIcon) submitLoadIcon.className = 'fas fa-paper-plane';
            if(btnSubmitText) btnSubmitText.textContent = 'Submit Review';
            this.disabled = false;
            this.classList.remove('btn-disabled');
        });
    });
}

// Skip
const btnSkip = document.getElementById('btn-skip');
if(btnSkip) {
    btnSkip.addEventListener('click', function() {
        if(!stepGoogle) return;
        stepGoogle.innerHTML = `
            <div class="status-icon-wrapper">
                <div class="review-status-icon love animate-pop"><i class="fas fa-heart"></i></div>
                <div class="status-ring" style="color: var(--primary);"></div>
            </div>
            <h2 class="review-status-title">Thanks for visiting ${businessName}!</h2>
            <p class="review-status-text">We truly appreciate your feedback and hope to see you again soon.</p>
            <div style="margin-top:32px; padding-top:20px; border-top:1px solid var(--gray-100);">
                <p style="font-size:10px; color:var(--gray-300); margin:0; font-weight:700; letter-spacing:0.08em; text-transform:uppercase;">Powered by QR Review</p>
            </div>
        `;
        launchConfetti();
    });
}

// ══════════ CONFETTI EFFECT ══════════
function launchConfetti() {
    const canvas = document.getElementById('confetti-canvas');
    if(!canvas) return;
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const particles = [];
    const colors = ['#6366f1', '#f59e0b', '#10b981', '#ef4444', '#ec4899', '#8b5cf6', '#06b6d4'];

    for (let i = 0; i < 80; i++) {
        particles.push({
            x: canvas.width / 2 + (Math.random() - 0.5) * 200,
            y: canvas.height / 2,
            vx: (Math.random() - 0.5) * 16,
            vy: Math.random() * -18 - 4,
            color: colors[Math.floor(Math.random() * colors.length)],
            size: Math.random() * 6 + 3,
            rotation: Math.random() * 360,
            rotationSpeed: (Math.random() - 0.5) * 12,
            gravity: 0.4,
            opacity: 1,
            shape: Math.random() > 0.5 ? 'rect' : 'circle'
        });
    }

    let frame = 0;
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        let alive = false;
        particles.forEach(p => {
            if (p.opacity <= 0) return;
            alive = true;
            p.x += p.vx;
            p.vy += p.gravity;
            p.y += p.vy;
            p.vx *= 0.99;
            p.rotation += p.rotationSpeed;
            p.opacity -= 0.012;

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rotation * Math.PI / 180);
            ctx.globalAlpha = Math.max(0, p.opacity);
            ctx.fillStyle = p.color;

            if (p.shape === 'rect') {
                ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 0.6);
            } else {
                ctx.beginPath();
                ctx.arc(0, 0, p.size / 2, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.restore();
        });

        frame++;
        if (alive && frame < 180) {
            requestAnimationFrame(animate);
        } else {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }
    animate();
}

window.addEventListener('resize', () => {
    const canvas = document.getElementById('confetti-canvas');
    if(canvas) {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
});
</script>
@endpush