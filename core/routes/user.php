<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->middleware('guest')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('sign-up', 'showRegistrationForm')->name('register');
        Route::post('sign-up', 'register');
        Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {

    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {

        Route::namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
            });

            //My-Businsesss - Gigs //
            Route::controller('GigController')->prefix('gig')->name('gig.')->group(function () {

                Route::get('create/overview/{id?}', 'overview')->name('overview');
                Route::post('store-overview/{id?}', 'storeOverview')->name('store.overview');

                Route::get('pricing/{id}', 'pricing')->name('pricing');
                Route::post('store-pricing/{id}', 'storePricing')->name('store.pricing');

                Route::get('requirement/{id}', 'requirement')->name('requirement');
                Route::post('store-requirement/{id}', 'storeRequirement')->name('store.requirement');

                Route::get('faqs/{id}', 'faqs')->name('faqs');
                Route::post('store-faqs/{id}', 'storeFAQs')->name('store.faqs');

                Route::get('gallery/{id}', 'gallery')->name('gallery');
                Route::post('store-gallery/{id}', 'storeGallery')->name('store.gallery');
                Route::post('remove-gallery/{id}', 'removeGallery')->name('remove.gallery');

                Route::get('publish/{id}', 'publish')->name('publish');
                Route::post('store-publish/{id}', 'gigPublished')->name('store.publish');

                Route::get('list', 'gigList')->name('list');
                Route::post('published/status/{id}', 'publishedStatus')->name('published.status');
            });


            //order //
            Route::controller('OrderController')->prefix('order')->name('order.')->group(function () {
                Route::post('processing/{gig}/{pricing}', 'order')->name('process');
            });

            //projects//
            Route::controller('ProjectController')->prefix('project')->name('project.')->group(function () {
                Route::get('details/{projectId}', 'details')->name('details');
                Route::post('message/{id}', 'message')->name('message');
                Route::post('history/delete/{id}', 'deleteHistory')->name('history.delete');

                Route::get('files/{projectId}', 'files')->name('files');
                Route::post('upload/files/{projectId}', 'uploadFiles')->name('upload.files');
                Route::get('file/download/{projectId}/{file}', 'downloadFile')->name('file.download');


                Route::post('accept/{id}', 'acceptProject')->name('accept');
                Route::post('reject/{id}', 'rejectProject')->name('reject');

                Route::post('complete/{id}', 'completeProject')->name('complete');
                Route::post('report/{id}', 'reportProject')->name('report');
                Route::post('review/rating/update/{id?}', 'reviewRatingUpdate')->name('review.rating.update');

                Route::get('pending', 'pending')->name('pending');
                Route::get('accepted', 'accepted')->name('accepted');
                Route::get('rejected', 'rejected')->name('rejected');
                Route::get('reported', 'reported')->name('reported');
                Route::get('completed/{id?}', 'completed')->name('completed');
            });

            //order //
            Route::controller('ConversationController')->prefix('conversation')->name('conversation.')->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('search', 'search')->name('search.list');
                Route::post('message-to', 'messages')->name('message.to');
                Route::post('messaging/{convId}', 'messaging')->name('messaging');
                Route::get('{convId}', 'details')->name('details');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');

                Route::get('profile-educations', 'educations')->name('profile.educations');
                Route::post('profile-educations', 'submitEducatins');

                Route::get('profile-portfolio', 'portfolio')->name('profile.portfolio');
                Route::post('profile-portfolio/{id?}', 'submitPortfolio')->name('profile.portfolio.store');
                Route::post('portfolio-delete/{id}', 'portfolioDelete')->name('profile.portfolio.delete');

                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });


            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });
        });

        // Payment
        Route::prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
