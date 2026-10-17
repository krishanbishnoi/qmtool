<?php

namespace App\Http\Controllers;

use App\AuditAlertBox;
use App\AuditFormFieldOption;
use App\AuditFormFields;
use App\Process;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Crypt;
use DB;

class AuditAlertBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AuditAlertBox::where('company_id', Auth::user()->company_id)->get();
        return view('audit_alert_box.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('audit_alert_box.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name' => 'required|not_regex:/<[^>]*>/|not_regex:/<script[^>]*>/',
            'details' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('audit_alert_box/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $new_rc = new AuditAlertBox;
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('audit_alert_box')->with('success', 'Alert box created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = AuditAlertBox::find(Crypt::decrypt($id));
        return view('audit_alert_box.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name' => 'required|not_regex:/<[^>]*>/|not_regex:/<script[^>]*>/',
            'details' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('audit_alert_box/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $new_rc =  AuditAlertBox::find(Crypt::decrypt($id));
            $new_rc->fill($request->all());
            $new_rc->save();
        }
        return redirect('audit_alert_box')->with('success', 'Alert box edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AuditAlertBox::find(Crypt::decrypt($id))->delete();
        return redirect('audit_alert_box')->with('success', 'Alert box deleted successfully.');
    }

    public function addField()
    {

        $process = [230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264  ,265,266,267,268,269,270,271,271,272,273,274,275,276,277,278,279,280,281,285];
        $process = Process::whereIn('id', $process)->pluck('name', 'id');
        return view(
            'audit_alert_box.add_field',
            compact('process')
        );
    }
    public function saveField(Request $request)
    {
        $field = new AuditFormFields();
        $field->process_id = $request->process_id;
        $field->field_name = $request->field_name;
        $field->field_type = $request->field_type;
        $field->status = 1;
        $field->save();

        if ($request->field_type == 'dropdown') {
            foreach ($request->options as $option) {
                AuditFormFieldOption::create([
                    'field_id' => $field->id,
                    'option_value' => $option['value'],
                    'status' => 1
                ]);
            }
        }

        return redirect()
            ->route('field_list')
            ->with('success', 'Field Created Successfully');
    }
    public function fieldList()
    {
        $data = AuditFormFields::with('options', 'process')
            ->get();

        return view(
            'audit_alert_box.field_list',
            compact('data')
        );
    }
    public function editField($id)
    {
        $field = AuditFormFields::with('options')
            ->findOrFail(
                Crypt::decrypt($id)
            );

        $process_list = Process::pluck('name', 'id');

        return view(
            'audit_alert_box.field_edit',
            compact(
                'field',
                'process_list'
            )
        );
    }

    public function updateField(Request $request, $id)
    {
        $id = Crypt::decrypt($id);



        try {

            // Update Field
            DB::table('audit_form_fields')
                ->where('id', $id)
                ->update([
                    'process_id' => $request->process_id,
                    'field_name' => $request->field_name,
                    'field_type' => $request->field_type,
                    'updated_at' => now(),
                ]);

            // If field is dropdown
            if ($request->field_type == 'dropdown') {

                $existingIds = [];

                if ($request->has('options')) {

                    foreach ($request->options as $option) {

                        // Skip blank values
                        if (trim($option['value']) == '') {
                            continue;
                        }

                        // Update Existing Option
                        if (!empty($option['row_id'])) {

                            DB::table('audit_form_field_options')
                                ->where('id', $option['row_id'])
                                ->update([
                                    'option_value' => $option['value'],
                                    'updated_at' => now(),
                                ]);

                            $existingIds[] = $option['row_id'];
                        } else {

                            // Insert New Option
                            $newId = DB::table('audit_form_field_options')
                                ->insertGetId([
                                    'field_id' => $id,
                                    'option_value' => $option['value'],
                                    'status' => 1,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);

                            $existingIds[] = $newId;
                        }
                    }
                }

                // Delete Removed Options
                DB::table('audit_form_field_options')
                    ->where('field_id', $id)
                    ->whereNotIn('id', $existingIds)
                    ->delete();
            } else {

                // Remove all dropdown options if field type changed
                DB::table('audit_form_field_options')
                    ->where('field_id', $id)
                    ->delete();
            }

          

            return redirect()
                ->back()
                ->with('success', 'Field updated successfully.');
        } catch (\Exception $e) {

            

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
