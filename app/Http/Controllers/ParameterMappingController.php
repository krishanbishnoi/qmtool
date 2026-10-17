<?php

namespace App\Http\Controllers;

use App\PerameterType;
use App\QmSheet;
use App\QmSheetParameter;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ParameterMappingController extends Controller
{

    public function index()
    {
        $data = QmSheet::where('company_id', Auth::user()->company_id)->with(['client', 'parameter'])->get();
        return view('qm_sheet_parameter.list', compact('data'));
    }

    public function list_parameter($sheet_id)
    {
        $qm_sheet_data = QmSheet::find(Crypt::decrypt($sheet_id));
        $data = QmSheetParameter::where('qm_sheet_id', $qm_sheet_data->id)->with('qm_sheet_sub_parameter')->get();
        $subParameterIds = $data->pluck('qm_sheet_sub_parameter')->flatten()->pluck('id')->toArray();
        $existingParameterTypes = PerameterType::whereIn('parameter_ids', $subParameterIds)->get();

        return view('qm_sheet_parameter.list_parameter', compact('data', 'qm_sheet_data', 'existingParameterTypes'));
    }



    public function add_parameter_types(Request $request)
    {

        foreach ($request->input('parameter_types') as $subparameterId => $parameterType) {

            $existing_type = PerameterType::where('parameter_ids', $subparameterId)
                ->first();

            if ($existing_type) {

                $existing_type->parameter_type = $parameterType;
                $existing_type->save();
            } else {
                PerameterType::create([
                    'parameter_ids' => $subparameterId,
                    'parameter_type' => $parameterType,

                ]);
            }
        }

        return redirect()->back()->with('success', 'Parameter types saved successfully!');
    }
}
