<?php

use App\Http\Controllers\admin\applicants\ApplicantsController;
use App\Http\Controllers\admin\users\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\dashboard\Crm;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\layouts\CollapsedMenu;
use App\Http\Controllers\layouts\ContentNavbar;
use App\Http\Controllers\layouts\ContentNavSidebar;
use App\Http\Controllers\layouts\NavbarFull;
use App\Http\Controllers\layouts\NavbarFullSidebar;
use App\Http\Controllers\layouts\Horizontal;
use App\Http\Controllers\layouts\Vertical;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\front_pages\Pricing;
use App\Http\Controllers\front_pages\Payment;
use App\Http\Controllers\front_pages\Checkout;
use App\Http\Controllers\front_pages\HelpCenter;
use App\Http\Controllers\front_pages\HelpCenterArticle;
use App\Http\Controllers\apps\Email;
use App\Http\Controllers\apps\Chat;
use App\Http\Controllers\apps\Calendar;
use App\Http\Controllers\apps\Kanban;
use App\Http\Controllers\apps\EcommerceDashboard;
use App\Http\Controllers\apps\EcommerceProductList;
use App\Http\Controllers\apps\EcommerceProductAdd;
use App\Http\Controllers\apps\EcommerceProductCategory;
use App\Http\Controllers\apps\EcommerceOrderList;
use App\Http\Controllers\apps\EcommerceOrderDetails;
use App\Http\Controllers\apps\EcommerceCustomerAll;
use App\Http\Controllers\apps\EcommerceCustomerDetailsOverview;
use App\Http\Controllers\apps\EcommerceCustomerDetailsSecurity;
use App\Http\Controllers\apps\EcommerceCustomerDetailsBilling;
use App\Http\Controllers\apps\EcommerceCustomerDetailsNotifications;
use App\Http\Controllers\apps\EcommerceManageReviews;
use App\Http\Controllers\apps\EcommerceReferrals;
use App\Http\Controllers\apps\EcommerceSettingsDetails;
use App\Http\Controllers\apps\EcommerceSettingsPayments;
use App\Http\Controllers\apps\EcommerceSettingsCheckout;
use App\Http\Controllers\apps\EcommerceSettingsShipping;
use App\Http\Controllers\apps\EcommerceSettingsLocations;
use App\Http\Controllers\apps\EcommerceSettingsNotifications;
use App\Http\Controllers\apps\AcademyDashboard;
use App\Http\Controllers\apps\AcademyCourse;
use App\Http\Controllers\apps\AcademyCourseDetails;
use App\Http\Controllers\apps\LogisticsDashboard;
use App\Http\Controllers\apps\LogisticsFleet;
use App\Http\Controllers\apps\InvoiceList;
use App\Http\Controllers\apps\InvoicePreview;
use App\Http\Controllers\apps\InvoicePrint;
use App\Http\Controllers\apps\InvoiceEdit;
use App\Http\Controllers\apps\InvoiceAdd;
use App\Http\Controllers\apps\UserList;
use App\Http\Controllers\apps\UserViewAccount;
use App\Http\Controllers\apps\UserViewSecurity;
use App\Http\Controllers\apps\UserViewBilling;
use App\Http\Controllers\apps\UserViewNotifications;
use App\Http\Controllers\apps\UserViewConnections;
use App\Http\Controllers\apps\AccessRoles;
use App\Http\Controllers\apps\AccessPermission;
use App\Http\Controllers\artist\ArtistController;
use App\Http\Controllers\artist\ArtworkController as ArtistArtworkController;
use App\Http\Controllers\artist\AwardController;
use App\Http\Controllers\artist\CollectionController;
use App\Http\Controllers\artist\EventController;
use App\Http\Controllers\artist\ExhibitionController;
use App\Http\Controllers\artwork\ArtworkController;
use App\Http\Controllers\pages\UserProfile;
use App\Http\Controllers\pages\UserTeams;
use App\Http\Controllers\pages\UserProjects;
use App\Http\Controllers\pages\UserConnections;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsSecurity;
use App\Http\Controllers\pages\AccountSettingsBilling;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\Faq;
use App\Http\Controllers\pages\Pricing as PagesPricing;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\pages\MiscComingSoon;
use App\Http\Controllers\pages\MiscNotAuthorized;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\LoginCover;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\RegisterCover;
use App\Http\Controllers\authentications\RegisterMultiSteps;
use App\Http\Controllers\authentications\VerifyEmailBasic;
use App\Http\Controllers\authentications\VerifyEmailCover;
use App\Http\Controllers\authentications\ResetPasswordBasic;
use App\Http\Controllers\authentications\ResetPasswordCover;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\ForgotPasswordCover;
use App\Http\Controllers\authentications\TwoStepsBasic;
use App\Http\Controllers\authentications\TwoStepsCover;
use App\Http\Controllers\BusinessClientController;
use App\Http\Controllers\wizard_example\Checkout as WizardCheckout;
use App\Http\Controllers\wizard_example\PropertyListing;
use App\Http\Controllers\wizard_example\CreateDeal;
use App\Http\Controllers\modal\ModalExample;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\cards\CardAdvance;
use App\Http\Controllers\cards\CardStatistics;
use App\Http\Controllers\cards\CardAnalytics;
use App\Http\Controllers\cards\CardGamifications;
use App\Http\Controllers\cards\CardActions;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\Avatar;
use App\Http\Controllers\extended_ui\BlockUI;
use App\Http\Controllers\extended_ui\DragAndDrop;
use App\Http\Controllers\extended_ui\MediaPlayer;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\StarRatings;
use App\Http\Controllers\extended_ui\SweetAlert;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\extended_ui\TimelineBasic;
use App\Http\Controllers\extended_ui\TimelineFullscreen;
use App\Http\Controllers\extended_ui\Tour;
use App\Http\Controllers\extended_ui\Treeview;
use App\Http\Controllers\extended_ui\Misc;
use App\Http\Controllers\icons\Tabler;
use App\Http\Controllers\icons\FontAwesome;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_elements\CustomOptions;
use App\Http\Controllers\form_elements\Editors;
use App\Http\Controllers\form_elements\FileUpload;
use App\Http\Controllers\form_elements\Picker;
use App\Http\Controllers\form_elements\Selects;
use App\Http\Controllers\form_elements\Sliders;
use App\Http\Controllers\form_elements\Switches;
use App\Http\Controllers\form_elements\Extras;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\form_layouts\StickyActions;
use App\Http\Controllers\form_wizard\Numbered as FormWizardNumbered;
use App\Http\Controllers\form_wizard\Icons as FormWizardIcons;
use App\Http\Controllers\form_validation\Validation;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\tables\DatatableBasic;
use App\Http\Controllers\tables\DatatableAdvanced;
use App\Http\Controllers\tables\DatatableExtensions;
use App\Http\Controllers\charts\ApexCharts;
use App\Http\Controllers\charts\ChartJs;
use App\Http\Controllers\contact\ContactController;
use App\Http\Controllers\dashboard\Dashboard;
use App\Http\Controllers\maps\Leaflet;
use App\Http\Controllers\users\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\VerifyBusinessEmailController;

Route::middleware(['auth', 'language'])->group(function () {
    Route::get('/', [Dashboard::class, 'index'])->name('dashboard-index');
    Route::get('/dashboard/analytics', [Analytics::class, 'index'])->name('dashboard-analytics');
    Route::get('/dashboard/crm', [Crm::class, 'index'])->name('dashboard-crm');
    // locale
    Route::get('/lang/{locale}', [LanguageController::class, 'swap']);




    Route::prefix('admin')->group(function () {
        Route::resource('users', UsersController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);

        Route::resource('applicants', ApplicantsController::class)->names([
            'index' => 'admin.applicants.index',
            'create' => 'admin.applicants.create',
            'store' => 'admin.applicants.store',
            'edit' => 'admin.applicants.edit',
            'update' => 'admin.applicants.update',
            'destroy' => 'admin.applicants.destroy',
        ]);
    });

    Route::prefix('users')->group(function () {
            Route::get('/researcher_board', [UserController::class, 'index'])->name(name: 'researcher_board');

        Route::resource('users', UserController::class)->names([
            'index' => 'users.users.index',
            'create' => 'users.users.create',
            'store' => 'users.users.store',
            'show' => 'users.users.show',
            'edit' => 'users.users.edit',
            'update' => 'users.users.update',
            'destroy' => 'users.users.destroy',
        ]);
        Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update');

        // Add the security route separately
        Route::get('users/{user}/security', [UserController::class, 'security'])
            ->name('users.users.security');

        Route::get('/users/security', action: [ArtworkController::class, 'view']);

        Route::get('/artwork/view/{artwork}', action: [ArtworkController::class, 'view']);

        Route::post('/artworks/{artwork}/favorite', action: [ArtworkController::class, 'favorite']);
        Route::delete('/artworks/{artwork}/favorite', [ArtworkController::class, 'unfavorite']);
    });

   

   
    /** Artist routes **/
    Route::get('/app/artist/list', [ArtistController::class, 'index'])->name('app-artist-list');
    Route::get('/app/artist/create', [ArtistController::class, 'create'])->name('app-artist-create-with-bulk');
    Route::get('/app/artist/create-with-bulk', [ArtistController::class, 'createWithBulk'])->name('app-artist-create-with-bulk');
    Route::post('/app/artist/store', [ArtistController::class, 'store'])->name('app-artist-store');
    Route::post('/app/artist/store-by-bulk', [ArtistController::class, 'storeByBulk'])->name('app-artist-store-by-bulk');
    Route::post('/app/artist/upload-additional-images', [ArtistController::class, 'uploadAdditionalImages'])->name('app-artist-upload-additional-images');
    Route::get('/app/artist/export-list', [ArtistController::class, 'exportList'])->name('app-artist-export-list');
    Route::get('/app/artist/edit/{id}', [ArtistController::class, 'edit'])->name('app-artist-edit');
    Route::put('/app/artist/update/{id}', [ArtistController::class, 'update'])->name('app-artist-update');
    Route::delete('/app/artist/destroy/{id}', [ArtistController::class, 'destroy'])->name('app-artist-destroy');
    /** Artist routes **/

    /** Contact routes **/
    Route::get('/app/contact/list', [ContactController::class, 'index'])->name('app-contact-list');
    Route::get('/app/contact/create', [ContactController::class, 'create'])->name('app-contact-create-with-bulk');
    Route::get('/app/contact/create-with-bulk', [ContactController::class, 'createWithBulk'])->name('app-contact-create-with-bulk');
    Route::post('/app/contact/store', [ContactController::class, 'store'])->name('app-contact-store');
    Route::post('/app/contact/store-by-bulk', [ContactController::class, 'storeByBulk'])->name('app-contact-store-by-bulk');
    Route::post('/app/contact/upload-additional-images', [ContactController::class, 'uploadAdditionalImages'])->name('app-contact-upload-additional-images');
    Route::get('/app/contact/export-list', [ContactController::class, 'exportList'])->name('app-contact-export-list');
    Route::get('/app/contact/edit/{id}', [ContactController::class, 'edit'])->name('app-contact-edit');
    Route::put('/app/contact/update/{id}', [ContactController::class, 'update'])->name('app-contact-update');
    Route::delete('/app/contact/destroy/{id}', [ContactController::class, 'destroy'])->name('app-contact-destroy');
    Route::get('/app/contact/view', [ContactController::class, 'show'])->name('app-contact-show');
    /** Contact routes **/

    // pages
    Route::get('/pages/profile-user', [UserProfile::class, 'index'])->name('pages-profile-user');
    Route::get('/pages/profile-teams', [UserTeams::class, 'index'])->name('pages-profile-teams');
    Route::get('/pages/profile-projects', [UserProjects::class, 'index'])->name('pages-profile-projects');
    Route::get('/pages/profile-connections', [UserConnections::class, 'index'])->name('pages-profile-connections');
    Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
    Route::get('/pages/account-settings-security', [AccountSettingsSecurity::class, 'index'])->name('pages-account-settings-security');
    Route::get('/pages/account-settings-billing', [AccountSettingsBilling::class, 'index'])->name('pages-account-settings-billing');
    Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
    Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
    Route::get('/pages/faq', [Faq::class, 'index'])->name('pages-faq');
    Route::get('/pages/pricing', [PagesPricing::class, 'index'])->name('pages-pricing');
    Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
    Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
    Route::get('/pages/misc-comingsoon', [MiscComingSoon::class, 'index'])->name('pages-misc-comingsoon');
    Route::get('/pages/misc-not-authorized', [MiscNotAuthorized::class, 'index'])->name('pages-misc-not-authorized');

    // authentication
    Route::get('/auth/login-cover', [LoginCover::class, 'index'])->name('auth-login-cover');
    Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
    Route::get('/auth/register-cover', [RegisterCover::class, 'index'])->name('auth-register-cover');
    Route::get('/auth/register-multisteps', [RegisterMultiSteps::class, 'index'])->name('auth-register-multisteps');
    Route::get('/auth/verify-email-basic', [VerifyEmailBasic::class, 'index'])->name('auth-verify-email-basic');
    Route::get('/auth/verify-email-cover', [VerifyEmailCover::class, 'index'])->name('auth-verify-email-cover');
    Route::get('/auth/reset-password-basic', [ResetPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
    Route::get('/auth/reset-password-cover', [ResetPasswordCover::class, 'index'])->name('auth-reset-password-cover');
    Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
    Route::get('/auth/forgot-password-cover', [ForgotPasswordCover::class, 'index'])->name('auth-forgot-password-cover');
    Route::get('/auth/two-steps-basic', [TwoStepsBasic::class, 'index'])->name('auth-two-steps-basic');
    Route::get('/auth/two-steps-cover', [TwoStepsCover::class, 'index'])->name('auth-two-steps-cover');

  
    
   
   


    ///////////////////////////////Starting official routes //////////////////////////////////////////////////////////////
    Route::get('app/admin/artworks/getAll', [ArtworkController::class, 'getAll'])->name(name: 'admin-artworks-data');
    Route::get('app/admin/artworks/suspend/{id}', action: [ArtworkController::class, 'destroy'])->name(name: 'artist-artworks-destroy');

    Route::resource('app/artworks', ArtworkController::class)->names([
        'index' => 'admin.artworks.index',
        'create' => 'admin.artworks.create',
        'store' => 'admin.artworks.store',
        'edit' => 'admin.artworks.edit',
        'update' => 'admin.artworks.update',
        'destroy' => 'admin.artworks.destroy',
    ]);;

    
    Route::get('/artist/profile-artist', [ArtistController::class, 'profile'])->name(name: 'artist-profile');
    Route::get('/artist/profile-exhibition', [ExhibitionController::class, 'index'])->name(name: 'artist-exhibition');
    Route::get('/artist/profile-artworks', [ArtistArtworkController::class, 'index'])->name(name: 'artist-artworks');
    Route::get('/artist/artwork/data', [ArtistArtworkController::class, 'getAll'])->name(name: 'artist-artworks-data');
    Route::get('/artist/artwork/add', [ArtistArtworkController::class, 'create'])->name(name: 'artist-artworks-create');
    Route::post('/artist/artwork/store', [ArtistArtworkController::class, 'store'])->name(name: 'artist-artworks-store');
    Route::get('/artist/artwork/edit/{id}', [ArtistArtworkController::class, 'edit'])->name(name: 'artist-artworks-edit');
    Route::put('/artist/artwork/update/{id}', [ArtistArtworkController::class, 'update'])->name(name: 'artist-artworks-update');
    Route::get('/artist/artwork/suspend/{id}', action: [ArtistArtworkController::class, 'destroy'])->name(name: 'artist-artworks-destroy');

    Route::get('/artist/profile-events', [EventController::class, 'index'])->name(name: 'artist-events');
    Route::get('/artist/profile-awards', [AwardController::class, 'index'])->name(name: 'artist-awards');

    Route::resource('artists.exhibitions', ExhibitionController::class)
        ->except(['show'])
        ->names([
            'index' => 'artists.exhibitions.index',
            'create' => 'artists.exhibitions.create',
            'store' => 'artists.exhibitions.store',
            'edit' => 'artists.exhibitions.edit',
            'update' => 'artists.exhibitions.update',
            'destroy' => 'artists.exhibitions.destroy',
        ]);

    Route::resource('artists.events', EventController::class)
        ->except(['show'])
        ->names([
            'index' => 'artists.events.index',
            'create' => 'artists.events.create',
            'store' => 'artists.events.store',
            'edit' => 'artists.events.edit',
            'update' => 'artists.events.update',
            'destroy' => 'artists.events.destroy',
        ]);

    Route::resource('artists.awards', AwardController::class)
        ->except(['show'])
        ->names([
            'index' => 'artists.awards.index',
            'create' => 'artists.awards.create',
            'store' => 'artists.awards.store',
            'edit' => 'artists.awards.edit',
            'update' => 'artists.awards.update',
            'destroy' => 'artists.awards.destroy',
        ]);

    Route::prefix('/artist/profile-collections')->group(function () {
        Route::get('/', [CollectionController::class, 'index'])->name('artists.collections.index');
        Route::post('/', [CollectionController::class, 'store'])->name('artists.collections.store');
        Route::put('{artist}/{collection}', [CollectionController::class, 'update'])->name('artists.collections.update');
        Route::delete('/{artist}/collections/{collection}', [CollectionController::class, 'destroy'])->name('artists.collections.destroy');
    });
});
Route::get('/auth/login', [LoginBasic::class, 'index'])->name('login');
Route::post('login', [LoginBasic::class, 'store']);
Route::post('logout', [LoginBasic::class, 'destroy'])->name('logout');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::post('/auth/register', [BusinessClientController::class, 'store'])->name('register');



Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $client = \App\Models\BusinessClient::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($client->getEmailForVerification()))) {
        abort(403);
    }

    if (!$client->hasVerifiedEmail()) {
        $client->markEmailAsVerified();
    }

    return redirect('email-verified-pending')->with('verified', true);
})->middleware('signed')->name('verification.verify');

Route::get('/email-verified-pending', function () {
    return view('content.authentications.verification-pending');
})->name('verification.pending');
Route::get('/email-verified', function () {
    if (!session('verified')) {
        return redirect('/');
    }
    return view('content.authentications.auth-verify-email-basic');
})->name('verification.success');