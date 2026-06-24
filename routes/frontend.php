<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HdfcPaymentController;



// ------------------ Static Page Routes ------------------
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/refund/policy', [HomeController::class, 'refundPolicy'])->name('refund.policy');
Route::get('/shipping/policy', [HomeController::class, 'shippingPolicy'])->name('shipping.policy');
Route::get('/thankyou', [HomeController::class, 'thankyou'])->name('thankyou');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/paynow', [HomeController::class, 'payNow'])->name('paynow');
Route::post('/contact/submit', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/event', [HomeController::class, 'event'])->name('event');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogDetails'])->name('blog.details');
Route::get('/online-chess-course-for-beginners', function () { return view('Frontend.CourseDetails.beginner'); })->name('explore.course.details.beginner');
Route::get('/online-chess-course-for-intermediate', function () { return view('Frontend.CourseDetails.intermediate'); })->name('explore.course.details.intermediate');
Route::get('/online-chess-course-for-advanced', function () { return view('Frontend.CourseDetails.advanced'); })->name('explore.course.details.advanced');
Route::get('/online-chess-course-for-expert', function () { return view('Frontend.CourseDetails.expert'); })->name('explore.course.details.expert');


// New design routes
Route::get('/design/home', [HomeController::class, 'newHome'])->name('design.home');
Route::get('/design/about', [HomeController::class, 'newAbout'])->name('design.about');
Route::get('/design/contact', [HomeController::class, 'newContact'])->name('design.contact');
Route::get('/design/gallery', [HomeController::class, 'newGallery'])->name('design.gallery');
Route::get('/design/event', [HomeController::class, 'newEvent'])->name('design.event');

Route::get('/design/blogs', [HomeController::class, 'newBlogs'])->name('design.blogs');
Route::get('/design/blog_details', [HomeController::class, 'newBlogDetails'])->name('design.blog_details');

Route::get('/design/policy/privacy', [HomeController::class, 'newPrivacy'])->name('design.policy.privacy');
Route::get('/design/policy/terms', [HomeController::class, 'newTerms'])->name('design.policy.terms');
Route::get('/design/policy/refund', [HomeController::class, 'newRefundPolicy'])->name('design.policy.refund');
Route::get('/design/policy/shipping', [HomeController::class, 'newShippingPolicy'])->name('design.policy.shipping');

Route::get('/design/course/beginners', [HomeController::class, 'newBeginnerCourse'])->name('design.course.beginners');
Route::get('/design/course/intermediate', [HomeController::class, 'newIntermediateCourse'])->name('design.course.intermediate');
Route::get('/design/course/advanced', [HomeController::class, 'newAdvancedCourse'])->name('design.course.advanced');
Route::get('/design/course/expert', [HomeController::class, 'newExpertCourse'])->name('design.course.expert');

// ------------------ Dynamic Page Routes ------------------
Route::get('/load-booking-form', [HomeController::class, 'loadBookingForm'])->name('load.booking.form');
Route::get('/get/pricing/cards', [HomeController::class, 'getPricingCard'])->name('get.pricing.card');

Route::get('/book/trial/class', [HomeController::class, 'bookTrialClass'])->name('book.trial.class');
Route::post('/confirm/trial/class', [HomeController::class, 'storeBookATrailForm'])->name('confirm.trial.class');
Route::post('/get-time-slots', [HomeController::class, 'getTimeSlots'])->name('get.time.slots');

Route::get('/online-chess/{country}', [HomeController::class, 'countryView'])->name('country');
Route::post('/confirm/trial/class/country', [HomeController::class, 'storeBookATrailFormForCountry'])->name('confirm.trial.class.country');

Route::get('/book/trial/class/thankyou', [HomeController::class, 'bookTrialClassThankYou'])->name('book.trial.class.thankyou');

Route::post('/enquiry/submit', [HomeController::class, 'enquirySubmit'])->name('enquiry.submit');

Route::post('/student/order/thankyou', [HdfcPaymentController::class, 'studentOrderThankyou'])->name('student.order.thankyou');
