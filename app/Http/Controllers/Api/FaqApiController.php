<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\AboutFaq;
use App\Models\AccomodationFaq;
use App\Models\CareFaq;
use App\Models\CommunityNursingFaq;
use App\Models\AliiedFaq;
use App\Models\PlanManagementFaq;
use App\Models\SupportCoordinationFaq;


class FaqApiController extends Controller{
    public function index()
{
    $faqs = collect()
        ->merge(Faq::where('status','active')->get())
        ->merge(AboutFaq::where('status','active')->get())
        ->merge(AccomodationFaq::where('status','active')->get())
        ->merge(CareFaq::where('status','active')->get())
        ->merge(CommunityNursingFaq::where('status','active')->get())
        ->merge(AliiedFaq::where('status','active')->get())
        ->merge(PlanManagementFaq::where('status','active')->get())
        ->merge(SupportCoordinationFaq::where('status','active')->get());

    return response()->json([
        'faqs' => $faqs
    ]);
}

}