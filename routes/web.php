<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SessionAuthenticate;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');



//user all routes

Route::post('/user-registration', [UserController::class, 'UserRegistration'])->name('user.registration');
Route::post('/user-login', [UserController::class, 'UserLogin'])->name('user.login');
Route::post('/send-otp', [UserController::class, 'sendOTPCode'])->name('send.otp.code');
Route::post('/verify-otp', [UserController::class, 'verifyOTPCode'])->name('verify.otp.code');

Route::middleware(SessionAuthenticate::class)->group(function () {
    Route::get('/dashboardPage', [UserController::class, 'dashboardPage']);
    //Reset Password
    //Route::get('/reset-password-page', [UserController::class, 'ResetPasswordPage'])->name('reset.password.page');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('reset.password');
    Route::get('/user-logout', [UserController::class, 'UserLogout'])->name('user.logout');

    //Brand all routes
    Route::post('/create-brand', [BrandController::class, 'CreateBrand'])->name('create.brand');
    // Route::get('/brand-list', [BrandController::class, 'BrandList'])->name('brand.list');
    // Route::post('/brand-by-id', [BrandController::class, 'BrandById'])->name('brand.by.id');
    // Route::post('/update-brand', [BrandController::class, 'UpdateBrand'])->name('update.brand');
    // Route::get('/delete-brand/{id}', [BrandController::class, 'DeleteBrand'])->name('delete.brand');

    //Category all routes
    Route::get('/category-page', [CategoryController::class, 'CategoryPage'])->name('category.page');
    Route::post('/create-category', [CategoryController::class, 'CreateCategory'])->name('create.category');
    Route::get('/category-list', [CategoryController::class, 'CategoryList'])->name('category.list');
    Route::post('/category-by-id', [CategoryController::class, 'CategoryById'])->name('category.by.id');
    Route::post('/update-category', [CategoryController::class, 'UpdateCategory'])->name('update.category');
    Route::get('/delete-category/{id}', [CategoryController::class, 'DeleteCategory'])->name('delete.category');
    Route::get('/category-save-page', [CategoryController::class, 'CategorySavePage'])->name('category.save.page');

    //Product all routes
    Route::post('/create-product', [ProductController::class, 'CreateProduct'])->name('create.product');
    Route::get('/product-list', [ProductController::class, 'ProductList'])->name('product.list');
    Route::post('/product-by-id', [ProductController::class, 'ProductById'])->name('product.by.id');
    Route::post('/update-product', [ProductController::class, 'UpdateProduct'])->name('update.product');
    Route::get('/delete-product/{id}', [ProductController::class, 'DeleteProduct'])->name('delete.product');
    Route::get('/product-page', [ProductController::class, 'ProductPage'])->name('product.page');
    Route::get('/product-save-page', [ProductController::class, 'ProductSavePage'])->name('product.save.page');

    //Customer all routes
    Route::post('/create-customer', [CustomerController::class, 'CreateCustomer'])->name('create.customer');
    Route::get('/customer-list', [CustomerController::class, 'CustomerList'])->name('customer.list');
    Route::post('/customer-by-id', [CustomerController::class, 'CustomerById'])->name('customer.by.id');
    Route::post('/update-customer', [CustomerController::class, 'UpdateCustomer'])->name('update.customer');
    Route::get('/delete-customer/{id}', [CustomerController::class, 'DeleteCustomer'])->name('delete.customer');
    Route::get('/customer-page', [CustomerController::class, 'CustomerPage'])->name('customer.page');
    Route::get('/customer-save-page', [CustomerController::class, 'CustomerSavePage'])->name('customer.save.page');

    //Invoice all routes
    Route::post('/create-invoice', [InvoiceController::class, 'CreateInvoice'])->name('create.invoice');
    Route::get('/invoice-list', [InvoiceController::class, 'InvoiceList'])->name('invoice.list');
    Route::post('/invoice-details', [InvoiceController::class, 'InvoiceDetails'])->name('invoice.details');
    // Route::post('/update-invoice', [InvoiceController::class, 'UpdateInvoice'])->name('update.invoice');
    Route::get('/delete-invoice/{id}', [InvoiceController::class, 'DeleteInvoice'])->name('delete.invoice');
    Route::get('/invoice-list-page', [InvoiceController::class, 'InvoiceListPage'])->name('InvoiceListPage');

    //Sale all routes
    Route::get('/sale-page', [SaleController::class, 'SalePage'])->name('sale.page');
    //Route::post('/create-sale', [SaleController::class, 'CreateSale'])->name('create.sale');

    //Dashboard Page
    Route::get('/dashboard-summery', [DashboardController::class, 'DashboardSummery'])->name('dashboard.summery');
    //reset password page
    Route::get('/reset-password-page', [UserController::class, 'ResetPasswordPage'])->name('reset.password.page');

    //Profile all routes
    Route::get('/profile-page', [UserController::class, 'ProfilePage'])->name('profile.page');
     Route::post('/user-update', [UserController::class, 'UserUpdate'])->name('user.update');
});

//User Login and Registration Page
Route::get('/login', [UserController::class, 'LoginPage'])->name('login.page');
Route::get('/registration', [UserController::class, 'RegistrationPage'])->name('registration.page');
Route::get('/send-otp', [UserController::class, 'SendOTPPage'])->name('send.otp.page');
Route::get('/verify-otp', [UserController::class, 'VerifyOTPPage'])->name('verify.otp.page');
