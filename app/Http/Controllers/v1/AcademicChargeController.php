<?php

namespace App\Http\Controllers\v1;

use App\Models\AcademicCharge;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\AcademicChargeCollection;
use App\Http\Resources\AcademicChargeResource;
use App\Http\Requests\StoreAcademicChargeRequest as StoreRequest;
use App\Http\Requests\UpdateAcademicChargeRequest as UpdateRequest;


class AcademicChargeController extends Controller
{
    /**
     * @urlParam showHidden tells the API to show hidden academic charges.
     */
    public function index(Request $request): AcademicChargeCollection
    {
        // check if request has the showHidden query parameter
        $showHidden = $request->query('showHidden', false);

        $academicCharges = $showHidden
            ? AcademicCharge::all()
            : AcademicCharge::where('is_hidden', false)->get();

        return new AcademicChargeCollection($academicCharges);
    }

    public function show(AcademicCharge $charge): AcademicChargeResource
    {
        return new AcademicChargeResource($charge);
    }

    public function store(StoreRequest $request): AcademicChargeResource
    {
        $academicCharge = AcademicCharge::create([
            'name' => $request->name,
            'year' => $request->year,
            'semester' => $request->semester,
            'is_hidden' => $request->is_hidden,
        ]);

        return new AcademicChargeResource($academicCharge);
    }

    public function update(UpdateRequest $request, AcademicCharge $charge): AcademicChargeResource
    {
        $validated = $request->validated();
        $charge->update($validated);

        return new AcademicChargeResource($charge);
    }

    public function destroy(AcademicCharge $charge): Response
    {
        $charge->delete();

        return response()->json([
            'message' => 'Academic charge deleted successfully',
        ], Response::HTTP_OK);
    }
}