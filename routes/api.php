<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\AboutApiController;
use App\Http\Controllers\Api\CommitmentApiController;
use App\Http\Controllers\Api\TeamApiController;
use App\Http\Controllers\Api\PrivacyApiController;
use App\Http\Controllers\Api\TermsApiController;
use App\Http\Controllers\Api\NdisApiController;
use App\Http\Controllers\Api\AgedcareApiController;
use App\Http\Controllers\Api\BlogsApiController;
use App\Http\Controllers\Api\NiisqApiController;
use App\Http\Controllers\Api\DvaApiController;
use App\Http\Controllers\Api\ClientResourcesApiController;
use App\Http\Controllers\Api\StaffResourcesApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\CareerApiController;
use App\Http\Controllers\Api\HomeServiceApiController;
use App\Http\Controllers\Api\CommunityParticipationApiController;
use App\Http\Controllers\Api\supportIndependentApiController;
use App\Http\Controllers\Api\careCoordinationApiController;
use App\Http\Controllers\Api\communityNursingApiController;
use App\Http\Controllers\Api\alliedHealthApiController;
use App\Http\Controllers\Api\planManagementApiController;
use App\Http\Controllers\Api\supportCoordinationApiController;


Route::get('/ping', function () {
    return response()->json(['status' => 'API working']);
});

Route::get('/home', [HomeApiController::class, 'index']);
Route::get('/about-us', [AboutApiController::class, 'index']);
Route::get('/our-commitment', [CommitmentApiController::class, 'index']);
Route::get('/our-team', [TeamApiController::class, 'index']);
Route::get('/privacy-policy', [PrivacyApiController::class, 'index']);
Route::get('/terms-conditions', [TermsApiController::class, 'index']);  
Route::get('/ndis', [NdisApiController::class, 'index']);  
Route::get('/aged-care', [AgedcareApiController::class, 'index']);  
Route::get('/blogs', [BlogsApiController::class, 'index']);  
Route::get('/niisq', [NiisqApiController::class, 'index']);  
Route::get('/dva', [DvaApiController::class, 'index']);  
Route::get('/client-resource', [ClientResourcesApiController::class, 'index']);  
Route::get('/staff-resource', [StaffResourcesApiController::class, 'index']);  
Route::get('/faqs', [FaqApiController::class, 'index']);  
Route::get('/jobs', [CareerApiController::class, 'index']);  
Route::get('/jobs/{slug}', [CareerApiController::class, 'show']);
Route::get('/home-service', [HomeServiceApiController::class, 'index']);
Route::get('/community-participation-service', [CommunityParticipationApiController::class, 'index']);
Route::get('/support-independent-service', [supportIndependentApiController::class, 'index']);
Route::get('/care-coordination-service', [careCoordinationApiController::class, 'index']);
Route::get('/community-nursing-service', [communityNursingApiController::class, 'index']);
Route::get('/allied-health-service', [alliedHealthApiController::class, 'index']);
Route::get('/plan-management-service', [planManagementApiController::class, 'index']);
Route::get('/support-coordination-service', [supportCoordinationApiController::class, 'index']);
