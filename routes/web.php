<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landing\LandingPageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Business\BusinessDashboardController;
use App\Http\Controllers\Business\BusinessProfileController;
use App\Http\Controllers\Business\BranchController;
use App\Http\Controllers\QR\QRCodeController;
use App\Http\Controllers\QR\QRScanController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Review\AIReviewController;
use App\Http\Controllers\Review\AIReviewTemplateController;
use App\Http\Controllers\Analytics\AnalyticsController;
use App\Http\Controllers\Subscription\PlanController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\Subscription\PaymentController;
use App\Http\Controllers\Subscription\CouponController;
use App\Http\Controllers\Campaign\WhatsAppController;
use App\Http\Controllers\Campaign\SMSController;
use App\Http\Controllers\Campaign\EmailCampaignController;
use App\Http\Controllers\NFC\NFCCardController;
use App\Http\Controllers\Support\TicketController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AICreditController;
use App\Http\Controllers\ReviewReminderController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBusinessController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminAICreditController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminActivityLogController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing.review-page'); // Optional: Create a marketing homepage
})->name('home');

Route::get('/pricing', [PlanController::class, 'index'])->name('public.pricing');

/*
|--------------------------------------------------------------------------
| LANDING PAGE (Customer Scans QR) - NO AUTH REQUIRED
|--------------------------------------------------------------------------
*/
Route::get('/r/{slug}', [LandingPageController::class, 'reviewPage'])->name('landing.review');
Route::post('/r/{slug}/generate-review', [LandingPageController::class, 'generateReview']);
Route::post('/r/{slug}/submit-review', [LandingPageController::class, 'submitReview']);
Route::post('/r/{slug}/mark-published', [LandingPageController::class, 'markReviewPublished']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED BUSINESS ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- Onboarding ---
    Route::get('/onboarding', [BusinessProfileController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding', [BusinessProfileController::class, 'storeOnboarding']);

    // --- Dashboard ---
    Route::get('/dashboard', [BusinessDashboardController::class, 'index'])->name('dashboard');

    // --- Business Profile ---
    Route::get('/business/edit', [BusinessProfileController::class, 'edit'])->name('business.edit');
    Route::put('/business/update', [BusinessProfileController::class, 'update'])->name('business.update');
    Route::get('/business/google-search', [BusinessProfileController::class, 'searchGooglePlaces'])->name('business.google-search');

    // --- Branches (Premium Feature) ---
    Route::resource('branches', BranchController::class);
    Route::post('branches/{branch}/set-main', [BranchController::class, 'setMain'])->name('branches.set-main');

    // --- QR Codes ---
    Route::resource('qr-codes', QRCodeController::class);
    Route::post('qr-codes/bulk-generate', [QRCodeController::class, 'bulkGenerate'])->name('qr-codes.bulk-generate')->middleware('qr.limit');
    Route::get('qr-codes/{qrCode}/download/{format}', [QRCodeController::class, 'download'])->name('qr-codes.download');
    
    // --- QR Scans Log ---
    Route::get('qr-scans', [QRScanController::class, 'index'])->name('qr-scans.index');

    // --- Reviews ---
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::get('reviews/export', [ReviewController::class, 'export'])->name('reviews.export');

    // --- AI Reviews ---
    Route::post('ai/generate', [AIReviewController::class, 'generate'])->name('ai.generate')->middleware('aicredits');
    Route::resource('ai-templates', AIReviewTemplateController::class)->only(['index', 'store', 'update']);
    Route::post('ai-templates/{template}/set-default', [AIReviewTemplateController::class, 'setDefault'])->name('ai-templates.setDefault');

    // --- Analytics ---
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/stats', [AnalyticsController::class, 'getDashboardStats'])->name('api.stats');
    Route::get('analytics/scans', [AnalyticsController::class, 'getScanData'])->name('api.scans');
    Route::get('analytics/devices', [AnalyticsController::class, 'getDeviceData'])->name('api.devices');
    Route::get('analytics/funnel', [AnalyticsController::class, 'getConversionFunnel'])->name('api.funnel');

    // --- Subscriptions & Payments ---
    Route::get('subscription/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::get('subscription/current', [SubscriptionController::class, 'current'])->name('subscription.current');
    Route::post('subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::post('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    
    Route::get('payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::post('razorpay/webhook', [PaymentController::class, 'webhook'])->name('razorpay.webhook')->withoutMiddleware(['auth']); // Webhooks are external

    // --- Coupons ---
    Route::post('coupon/validate', [CouponController::class, 'validate'])->name('coupon.validate');

    // --- Campaigns: WhatsApp (Premium) ---
    Route::get('campaign/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index')->middleware('subscription:whatsapp');
    Route::post('campaign/whatsapp/send', [WhatsAppController::class, 'send'])->name('whatsapp.send')->middleware('subscription:whatsapp');

    // --- Campaigns: SMS ---
    Route::get('campaign/sms', [SMSController::class, 'index'])->name('sms.index');
    Route::post('campaign/sms/send', [SMSController::class, 'send'])->name('sms.send');

    // --- Campaigns: Email ---
    Route::resource('campaign/email', EmailCampaignController::class)->names('email-campaign');
    Route::post('campaign/email/{campaign}/send', [EmailCampaignController::class, 'send'])->name('email-campaign.send');

    // --- NFC Cards (Premium) ---
    Route::resource('nfc-cards', NFCCardController::class)->middleware('subscription:nfc');

    // --- Employees ---
    Route::resource('employees', EmployeeController::class)->only(['index', 'store']);
    Route::get('employees/{employee}/performance', [EmployeeController::class, 'performance'])->name('employees.performance');

    // --- Customers ---
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);

    // --- Reminders ---
    Route::get('reminders', [ReviewReminderController::class, 'index'])->name('reminders.index');
    Route::post('reminders', [ReviewReminderController::class, 'store'])->name('reminders.store');

    // --- Support Tickets ---
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/message', [TicketController::class, 'addMessage'])->name('tickets.message');
    Route::post('tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');

    // --- Notifications ---
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // --- Settings & AI Credits ---
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('ai-credits', [AICreditController::class, 'index'])->name('ai-credits.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Businesses
    Route::resource('businesses', AdminBusinessController::class)->only(['index', 'show']);
    Route::post('businesses/{business}/approve', [AdminBusinessController::class, 'approve'])->name('businesses.approve');
    Route::post('businesses/{business}/reject', [AdminBusinessController::class, 'reject'])->name('businesses.reject');
    Route::post('businesses/{business}/suspend', [AdminBusinessController::class, 'suspend'])->name('businesses.suspend');

    // Users
    Route::resource('users', AdminUserController::class)->only(['index', 'destroy']);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Plans & Subscriptions
    Route::resource('plans', AdminPlanController::class);
    Route::get('subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{subscription}', [AdminSubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('subscriptions/{subscription}/extend', [AdminSubscriptionController::class, 'extend'])->name('subscriptions.extend');

    // Payments
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('payments.refund');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);

    // AI Credits
    Route::get('ai-credits', [AdminAICreditController::class, 'index'])->name('ai-credits.index');
    Route::post('ai-credits/grant', [AdminAICreditController::class, 'grant'])->name('ai-credits.grant');

    // Reports
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('reports/revenue', [AdminReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/subscriptions', [AdminReportController::class, 'subscriptions'])->name('reports.subscriptions');
    Route::get('reports/businesses', [AdminReportController::class, 'businesses'])->name('reports.businesses');

    // Support Tickets
    Route::get('tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('tickets.assign');
    Route::post('tickets/{ticket}/message', [AdminTicketController::class, 'addMessage'])->name('tickets.message');
    Route::post('tickets/{ticket}/resolve', [AdminTicketController::class, 'resolve'])->name('tickets.resolve');

    // Settings & Logs
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{log}', [AdminActivityLogController::class, 'show'])->name('activity-logs.show');
});