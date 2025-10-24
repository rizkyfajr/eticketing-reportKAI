<?php

namespace App\Http\Controllers;

use App\Models\CheckSheetDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckSheetDayController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'working_report_id' => 'required|exists:working_reports,id',
            'no_seri' => 'nullable|integer',
            'jenis' => 'nullable|string|max:255',
            'jam_mesin' => 'nullable|date_format:H:i:s',
            'counter_pecok' => 'nullable|integer',
            'kilometer_mesin' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'lokasi' => 'nullable|string|max:255',
            'wilayah' => 'nullable|string|max:255',
            'region_id' => 'required|exists:master_regions,id',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['created_by_id'] = Auth::id();
            $validated['updated_by_id'] = Auth::id();

            $checkSheetDay = CheckSheetDay::create($validated);

            DB::commit();
            
            return redirect()->back()->with('success', __('Data berhasil ditambahkan.'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', __('Gagal menambahkan Data.'));
        }
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'working_report_id' => 'required|exists:working_reports,id',
            'no_seri' => 'nullable|integer',
            'jenis' => 'nullable|string|max:255',
            'jam_mesin' => 'nullable|date_format:H:i',
            'counter_pecok' => 'nullable|integer',
            'kilometer_mesin' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'lokasi' => 'nullable|string|max:255',
            'wilayah' => 'nullable|string|max:255',
            'region_id' => 'required|exists:master_regions,id',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $checkSheetDay = CheckSheetDay::findOrFail($id);
            $validated['updated_by_id'] = Auth::id();

            $checkSheetDay->update($validated);

            DB::commit();
            
            return redirect()->back()->with('success', __('Data berhasil diperbarui.'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('success', __('Data gagal diperbarui.'));
        }
    }
}
