<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('index');

Auth::routes(['verify' => true]);

Route::prefix('/ideas')->name('ideas.')->group(function(){
    Route::post('/search', 'IdeaController@search')->name('search');
});

Route::middleware(['verified', 'auth:admin,web'])->group(function() {
    Route::prefix('/nations')->name('nations.')->group(function(){
        Route::get('/', 'NationController@index')->name('index')->middleware('can:viewAny,App\Nation');
    });
    
    Route::prefix('/media')->name('media.')->group(function(){
        Route::post('/store/{user}', 'MediaController@store')->name('store')->middleware('can:create,App\Media,user');
        Route::post('/verification', 'MediaController@verification')->name('verification')->middleware('can:verification,App\Media');
        Route::delete('/{media}', 'MediaController@destroy')->name('delete')->middleware('can:delete,media');
    });
    
    Route::prefix('/passports')->name('passports.')->group(function(){
        Route::delete('/{passport}', 'PassportController@destroy')->name('delete')->middleware('can:delete,passport');
    });
    
    Route::prefix('/ideas')->name('ideas.')->group(function(){
        Route::match(['get', 'post'],'/indexes', 'IdeaController@indexes')->name('indexes')->middleware('can:viewAny,App\Idea');
        Route::match(['get', 'post'],'/indexes/popularity', 'IdeaController@popularity_indexes')->name('popularity_indexes')->middleware('can:viewAny,App\Idea');
        Route::get('/{idea}', 'IdeaController@show')->name('view')->middleware('can:view,idea')->where('idea', '[0-9]+');
        Route::get('/create', 'IdeaController@create')->name('create')->middleware('can:create,App\Idea');
        Route::post('/store', 'IdeaController@store')->name('store')->middleware('can:create,App\Idea');
        Route::post('/{idea}/like', 'IdeaController@like')->name('like')->middleware('can:like,idea')->where('idea', '[0-9]+');
        Route::get('/{idea}/unlike', 'IdeaController@unlike')->name('unlike')->middleware('can:unlike,idea')->where('idea', '[0-9]+');
    });
    
    Route::prefix('/users')->name('users.')->group(function(){
        Route::match(['get', 'post'], '/search', 'UserController@search')->name('search')->middleware('can:searchAny,App\User');        
        Route::get('/{hash}', 'UserController@ideological_profile')->name('ideological_profile')->middleware('can:ideological_profile,App\User,hash');
        Route::get('/{user}/edit', 'UserController@edit')->name('edit')->middleware('can:update,user');
        Route::post('/{user}/name', 'UserController@update_name')->name('update_name')->middleware('can:update,user');
        Route::post('/{user}/nation', 'UserController@update_nation')->name('update_nation')->middleware('can:update,user');
        Route::post('/{user}/contacts', 'UserController@update_contacts')->name('update_contacts')->middleware('can:update,user');
        Route::get('/{user}/withdraw_from_ilp', 'IlpController@withdraw_from_ilp')->name('withdraw_from_ilp')->middleware('can:withdraw_from_ilp,user');
        Route::post('/{user}/withdraw_from_ilp_process', 'IlpController@withdraw_from_ilp_process')->name('withdraw_from_ilp_process')->middleware('can:withdraw_from_ilp,user');
        Route::put('/{user}', 'UserController@update')->name('update')->middleware('can:update,user');
        Route::delete('/{user}/me', 'UserController@delete_by_user')->name('delete_by_user')->middleware('can:delete,user');
        Route::post('/{user}/follow', 'UserController@follow')->name('follow')->middleware('can:follow,user');
        Route::delete('/{user}/follow', 'UserController@unfollow')->name('unfollow')->middleware('can:follow,user');
        Route::get('/{user}/settings', 'UserController@settings')->name('settings')->middleware('can:settings,user');
        Route::get('/{user}/disqualify_membership', 'UserController@disqualify_membership')->name('disqualify_membership')->middleware('can:disqualify_membership,user');
        Route::get('/{user}/cancel_guardianship', 'UserController@cancel_guardianship')->name('cancel_guardianship')->middleware('can:cancel_guardianship,user');
        Route::get('/{user}/allow_guardianship', 'UserController@allow_guardianship')->name('allow_guardianship')->middleware('can:allow_guardianship,user');
        Route::put('/email/{token}', 'UserController@update_email')->name('update_email');
        Route::get('/email/{token}', 'UserController@update_email_confirm')->name('update_email_confirm');
        Route::put('/{user}/email', 'UserController@update_email_send_token')->name('update_email_send_token')->middleware('can:update,user');
        Route::put('/{user}/password', 'UserController@update_password')->name('update_password')->middleware('can:update,user');
        Route::put('/{user}/privacy', 'UserController@update_privacy')->name('update_privacy')->middleware('can:update,user');
        Route::get('/{user}/verification_id_image', 'UserController@verification_id_image')->name('verification_id_image')->middleware('can:verify,user');
        Route::put('/{user}/verify', 'UserController@verify')->name('verify')->middleware('can:verify,user');
        Route::delete('/{user}/verify', 'UserController@unverify')->name('unverify')->middleware('can:unverify,user');
    });
    
    Route::prefix('/ilp')->name('ilp.')->group(function(){
        Route::get('/', 'IlpController@index')->name('index')->middleware('can:submit_application,App\User');
        Route::get('/menu', 'IlpController@menu')->name('menu');
        Route::get('/principles', 'IlpController@principles')->name('principles');
        Route::get('/founding_members', 'IlpController@founding_members')->name('founding_members');
        Route::get('/guide', 'IlpController@guide')->name('guide');
        Route::get('/officer_petition', 'IlpController@officer_petition')->name('officer_petition');
        Route::post('/{user}/submit_officer_application', 'IlpController@submit_officer_application')->name('submit_officer_application')->middleware('can:submit_officer_application,user');
        Route::get('/{user}/cancel_officer_application', 'IlpController@cancel_officer_application')->name('cancel_officer_application')->middleware('can:cancel_officer_application,user');
        Route::post('/{user}/cancel_officer_application', 'IlpController@cancel_officer_application_proceed')->name('cancel_officer_application_proceed')->middleware('can:cancel_officer_application,user');
        Route::post('/{user}/submit_application', 'IlpController@submit_application')->name('submit_application')->middleware('can:submit_application,App\User');
        Route::get('/{user}/accept_application', 'IlpController@accept_application')->name('accept_application')->middleware('can:accept_application,user');
        Route::get('/{user}/support_officer_application', 'IlpController@support_officer_application')->name('support_officer_application')->middleware('can:support_officer_application,user');
        Route::get('/{user}/unsupport_officer_application', 'IlpController@unsupport_officer_application')->name('unsupport_officer_application')->middleware('can:unsupport_officer_application,user');
    });
    
    Route::prefix('/search_names')->name('search_names.')->group(function(){
        Route::get('/create', 'SearchNameController@create')->name('create')->middleware('can:create,App\SearchName');
        Route::post('/', 'SearchNameController@store')->name('store')->middleware('can:create,App\SearchName');
        Route::get('/{search_name}/edit', 'SearchNameController@edit')->name('edit')->middleware('can:update,search_name');
        Route::put('/{search_name}', 'SearchNameController@update')->name('update')->middleware('can:update,search_name');
    });
    
    Route::prefix('/meetings')->name('meetings.')->group(function(){
        Route::get('/', 'MeetingController@index')->name('index')->middleware('can:viewAny,App\Meeting');
        Route::get('/all', 'MeetingController@index')->name('all')->middleware('can:viewAny,App\Meeting');
        Route::post('/', 'MeetingController@store')->name('store')->middleware('can:create,App\Meeting');
        Route::get('/{meeting}', 'MeetingController@show')->name('view')->middleware('can:view,meeting');
        Route::delete('/{meeting}', 'MeetingController@destroy')->name('delete')->middleware('can:delete,meeting');
        Route::put('/{meeting}', 'MeetingController@restore')->name('restore')->middleware('can:restore,App\Meeting');
    });

    Route::prefix('/campaigns')->name('campaigns.')->group(function(){
        Route::get('/', 'CampaignController@index')->name('index')->middleware('can:viewAny,App\Campaign');
        Route::post('/', 'CampaignController@store')->name('store')->middleware('can:create,App\Campaign');
        Route::delete('/{campaign}', 'CampaignController@destroy')->name('delete')->middleware('can:delete,campaign');
        Route::put('/', 'CampaignController@index')->name('search');
    });

});

Route::middleware(['verified','auth:web'])->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::middleware(['auth:admin'])->group(function() {
    
    Route::prefix('/admin')->name('admin.')->group(function(){
        Route::get('/', 'HomeController@indexAdmin')->name('homeAdmin');
        Route::get('/settings', 'AdminController@settings')->name('settings');
        Route::put('/email/{token}', 'AdminController@update_email')->name('update_email');
        Route::get('/email/{token}', 'AdminController@update_email_confirm')->name('update_email_confirm');
        Route::put('/email', 'AdminController@update_email_send_token')->name('update_email_send_token');
        Route::put('/password', 'AdminController@update_password')->name('update_password');
    });
    
    Route::prefix('/users')->name('users.')->group(function(){
        Route::get('/', 'UserController@index')->name('index')->middleware('can:viewAny,App\User');
        Route::post('/', 'UserController@index')->name('index')->middleware('can:viewAny,App\User');
        Route::get('/{user}/profile', 'UserController@profile')->name('profile')->where('user', '[0-9]+');
        Route::delete('/{user}', 'UserController@destroy')->name('delete')->middleware('can:delete,user');
        Route::put('/{user}/restore', 'UserController@restore')->name('restore')->middleware('can:restore,user');
        Route::put('/{user}/deactivate', 'UserController@deactivate')->name('deactivate')->middleware('can:deactivate,user');
    });
    
    Route::prefix('/nations')->name('nations.')->group(function(){
        Route::get('/{nation}', 'NationController@show')->name('view')->middleware('can:view,nation');
        Route::delete('/{nation}', 'NationController@destroy')->name('delete')->middleware('can:delete,nation');
        Route::put('/{nation}', 'NationController@restore')->name('restore')->middleware('can:restore,App\Nation');
    });
    
    Route::prefix('/ideas')->name('ideas.')->group(function(){
        Route::get('/', 'IdeaController@index')->name('index')->middleware('can:administrate,App\Idea');
        Route::delete('/{idea}', 'IdeaController@destroy')->name('delete')->middleware('can:delete,idea')->where('idea', '[0-9]+');
        Route::put('/{idea}', 'IdeaController@restore')->name('restore')->middleware('can:restore,App\Idea')->where('idea', '[0-9]+');
    });
    
    Route::prefix('/meetings')->name('meetings.')->group(function(){
        Route::get('/all', 'MeetingController@index')->name('all')->middleware('can:viewAny,App\Meeting');
    });
    
    Route::prefix('/user_types')->name('user_types.')->group(function(){
        Route::get('/', 'UserTypeController@index')->name('index')->middleware('can:viewAny,App\UserType');
        Route::get('/{user_type}', 'UserTypeController@show')->name('view')->middleware('can:view,user_type');
        Route::delete('/{user_type}', 'UserTypeController@destroy')->name('delete')->middleware('can:delete,user_type');
        Route::put('/{user_type}', 'UserTypeController@restore')->name('restore')->middleware('can:restore,App\UserType');
    });
    
    Route::prefix('/campaigns')->name('campaigns.')->group(function(){
        Route::get('/list', 'CampaignController@list')->name('list')->middleware('can:viewAny,App\Campaign');
    });
    
    Route::prefix('/petitions')->name('petitions.')->group(function(){
        Route::get('/', 'PetitionController@index')->name('index')->middleware('can:viewAny,App\Petition');
        Route::get('/{petition}', 'PetitionController@show')->name('view')->middleware('can:view,petition');
        Route::delete('/{petition}', 'PetitionController@destroy')->name('delete')->middleware('can:delete,petition');
        Route::put('/{petition}', 'PetitionController@restore')->name('restore')->middleware('can:restore,App\Petition');
    });
    
    Route::prefix('/contents')->name('contents.')->group(function(){
        Route::get('/', 'ContentController@index')->name('index')->middleware('can:viewAny,App\Content');
        Route::get('/{content}', 'ContentController@show')->name('view')->middleware('can:view,content');
        Route::delete('/{content}', 'ContentController@destroy')->name('delete')->middleware('can:delete,content');
        Route::put('/{content}', 'ContentController@restore')->name('restore')->middleware('can:restore,App\Content');
    });
    
});