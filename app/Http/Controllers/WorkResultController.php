<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DataTableRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\MasterMachine;
use App\Models\MasterRegion;
use App\Models\WorkingReport;
use App\Models\WorkResult;
use Illuminate\Support\Facades\DB;

class WorkResultController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(WorkResult $report)
  {
      return Inertia::render('WorkResult/Index', [
          'report'      => $report,
          // 'reports' => WorkResult::with(['machine', 'region', 'pengawal', 'reportuser'])->get(),
          'machines' => MasterMachine::select('id', 'name')->get(),
          'regions' => MasterRegion::select('id', 'name')->get(),
          'users' => User::select('id', 'name')->get(),
      ]);
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create(WorkResult $report)
  {
      return Inertia::render('WorkResult/Create', [
          'report' => $report,
          'machines' => MasterMachine::select('id', 'name')->get(),
          'regions' => MasterRegion::select('id', 'name')->get(),
          'users' => User::select('id', 'name')->get(),
      ]);
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
//   public function store(Request $request)
//   {
//     try {
//         $validated = $request->validate([
//             'working_report_id'  => 'required|exists:working_reports,id',
//             'machine_id'         => 'required|exists:master_machines,id',
//             'region_id'          => 'required|exists:master_regions,id',
//             'antara'             => 'nullable|string|max:255',
//             'km_hm'              => 'nullable|string|max:255',
//             'jumlah_msp'         => 'nullable|integer',
//             'waktu_start_engine' => 'nullable|date',
//             'jam_luncuran'       => 'nullable|date_format:H:i:s',
//             'jam_kerja'          => 'nullable|date_format:H:i:s',
//             'jam_mesin'          => 'nullable|date_format:H:i:s',
//             'jam_genset'         => 'nullable|date_format:H:i:s',
//             'counter_pecok'      => 'nullable|integer',
//             'oddometer'          => 'nullable|integer',
//             'penggunaan_hsd'     => 'nullable|numeric',
//             'hsd_tersedia'       => 'nullable|numeric',
//             'pengawal_id'        => 'nullable|exists:users,id',
//             'note'               => 'nullable|string|max:1000',
//             'user_id'            => 'nullable|array',
//             'user_id.*'          => 'exists:users,id',
//         ]);

//         $workResult = WorkResult::create([
//             'working_report_id' => $validated['working_report_id'],
//             'machine_id'        => $validated['machine_id'],
//             'region_id'         => $validated['region_id'],
//             'antara'            => $request->antara,
//             'km_hm'             => $request->km_hm,
//             'jumlah_msp'        => $request->jumlah_msp,
//             'waktu_start_engine'=> $request->waktu_start_engine,
//             'jam_luncuran'      => $request->jam_luncuran,
//             'jam_kerja'         => $request->jam_kerja,
//             'jam_mesin'         => $request->jam_mesin,
//             'jam_genset'        => $request->jam_genset,
//             'counter_pecok'     => $request->counter_pecok,
//             'oddometer'         => $request->oddometer,
//             'penggunaan_hsd'    => $request->penggunaan_hsd,
//             'hsd_tersedia'      => $request->hsd_tersedia,
//             'pengawal_id'       => $request->pengawal_id,
//             'note'              => $request->note,
//             'created_by_id'     => auth()->id(),
//         ]);

//         if (!empty($validated['user_id'])) {
//             $crewPivotData = collect($validated['user_id'])->map(function ($userId) use ($workResult) {
//                 return [
//                     'work_result_id' => $workResult->id,
//                     'user_id'        => $userId,
//                     'created_at'     => now(),
//                     'updated_at'     => now(),
//                 ];
//             })->toArray();

//             DB::table('workresult_user')->insert($crewPivotData);
//         }

//         DB::commit();

//         return redirect()->back()->with('success', 'Data berhasil disimpan.');

//     } catch (\Illuminate\Validation\ValidationException $e) {
//         return redirect()->back()->withErrors($e->errors())->withInput();
//     } catch (\Exception $e) {
//         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
//     }
//   }

public function store(Request $request)
  {
    try {
        $validated = $request->validate([
            'working_report_id'  => 'required|exists:working_reports,id',
            'machine_id'         => 'required|exists:master_machines,id',
            'region_id'          => 'nullable|exists:master_regions,id',
            'tanggal'            => 'nullable|date',
            'cuaca'              => 'nullable|string|max:255',
            'wilayah'            => 'nullable|string|max:255',
            'petak_jalan'        => 'nullable|string|max:255',
            'jalur'              => 'nullable|string|max:255',
            'kelas_jalan'        => 'nullable|string|max:255',
            'kecepatan_lintas'   => 'nullable|string|max:255',
            'lokasi_awal1'       => 'nullable|string|max:255',
            'lokasi_akhir1'      => 'nullable|string|max:255',
            'jumlah1'            => 'nullable|numeric',
            'lokasi_awal2'       => 'nullable|string|max:255',
            'lokasi_akhir2'      => 'nullable|string|max:255',
            'jumlah2'            => 'nullable|numeric',
            'lokasi_awal3'       => 'nullable|string|max:255',
            'lokasi_akhir3'      => 'nullable|string|max:255',
            'jumlah3'            => 'nullable|numeric',
            'total_distance'     => 'nullable|numeric',
            'no_wesel1'          => 'nullable|string|max:255',
            'km_hm1'             => 'nullable|string|max:255',
            'jumlah_wesel1'      => 'nullable|integer',
            'no_wesel2'          => 'nullable|string|max:255',
            'km_hm2'             => 'nullable|string|max:255',
            'jumlah_wesel2'      => 'nullable|integer',
            'no_wesel3'          => 'nullable|string|max:255',
            'km_hm3'             => 'nullable|string|max:255',
            'jumlah_wesel3'      => 'nullable|integer',
            'total_wesel'        => 'nullable|integer',
            'waktu_start_engine' => 'nullable|date',
            'jam_luncuran'       => 'nullable|date_format:H:i:s',
            'jam_kerja'          => 'nullable|date_format:H:i:s',
            'jam_mesin'          => 'nullable|date_format:H:i:s',
            'jam_genset'         => 'nullable|date_format:H:i:s',
            'waktu_stop_engine'  => 'nullable|date',
            'counter_pecok'      => 'nullable|integer',
            'oddometer'          => 'nullable|integer',
            'penggunaan_hsd'     => 'nullable|numeric',
            'penggunaan_hsd1'    => 'nullable|numeric',
            'hsd_tersedia'       => 'nullable|numeric',
            'oddometer_hsd'      => 'nullable|integer',
            'pengawal_id'        => 'nullable|exists:users,id',
            'note'               => 'nullable|string|max:1000',
            'operator_by1'       => 'nullable|integer',
            'operator_by2'       => 'nullable|integer',
            'operator_by3'       => 'nullable|integer',
        ]);

        $workResult = WorkResult::create([
            'working_report_id' => $validated['working_report_id'],
            'machine_id'        => $validated['machine_id'],
            'region_id'         => $validated['region_id'],
            'tanggal'           => $request->tanggal,
            'cuaca'             => $request->cuaca,
            'wilayah'           => $request->wilayah,
            'petak_jalan'       => $request->petak_jalan,
            'jalur'             => $request->jalur,
            'kelas_jalan'       => $request->kelas_jalan,
            'kecepatan_lintas'  => $request->kecepatan_lintas,
            'lokasi_awal1'      => $request->lokasi_awal1,
            'lokasi_akhir1'     => $request->lokasi_akhir1,
            'jumlah1'           => $request->jumlah1,
            'lokasi_awal2'      => $request->lokasi_awal2,
            'lokasi_akhir2'     => $request->lokasi_akhir2,
            'jumlah2'           => $request->jumlah2,
            'lokasi_awal3'      => $request->lokasi_awal3,
            'lokasi_akhir3'     => $request->lokasi_akhir3,
            'jumlah3'           => $request->jumlah3,
            'total_distance'    => $request->total_distance,
            'no_wesel1'         => $request->no_wesel1,
            'km_hm1'            => $request->km_hm1,
            'jumlah_wesel1'     => $request->jumlah_wesel1,
            'no_wesel2'         => $request->no_wesel2,
            'km_hm2'            => $request->km_hm2,
            'jumlah_wesel2'     => $request->jumlah_wesel2,
            'no_wesel3'         => $request->no_wesel3,
            'km_hm3'            => $request->km_hm3,
            'jumlah_wesel3'     => $request->jumlah_wesel3,
            'total_wesel'       => $request->total_wesel,
            'waktu_start_engine'=> $request->waktu_start_engine,
            'jam_luncuran'      => $request->jam_luncuran,
            'jam_kerja'         => $request->jam_kerja,
            'jam_mesin'         => $request->jam_mesin,
            'jam_genset'        => $request->jam_genset,
            'waktu_stop_engine' => $request->waktu_stop_engine,
            'counter_pecok'     => $request->counter_pecok,
            'oddometer'         => $request->oddometer,
            'penggunaan_hsd'    => $request->penggunaan_hsd,
            'penggunaan_hsd1'   => $request->penggunaan_hsd1,
            'hsd_tersedia'      => $request->hsd_tersedia,
            'oddometer_hsd'     => $request->oddometer_hsd,
            'pengawal_id'       => $request->pengawal_id,
            'note'              => $request->note,
            'operator_by1'      => $request->operator_by1,
            'operator_by2'      => $request->operator_by2,
            'operator_by3'      => $request->operator_by3,
            'created_by_id'     => auth()->id(),
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Data berhasil disimpan.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
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
      $report = WorkResult::findOrFail($id);

      return Inertia::render('WorkResult/Update', [
          'report' => $report,
          'machines' => MasterMachine::select('id', 'name')->get(),
          'regions' => MasterRegion::select('id', 'name')->get(),
          'users' => User::select('id', 'name')->get(),
      ]);
  }
  
  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
//   public function update(Request $request, $id)
//   {
//     DB::beginTransaction();

//     try {
//         $workResult = WorkResult::findOrFail($id);

//         $validated = $request->validate([
//             'machine_id'         => 'required|exists:master_machines,id',
//             'region_id'          => 'required|exists:master_regions,id',
//             'antara'             => 'nullable|string|max:255',
//             'km_hm'              => 'nullable|string|max:255',
//             'jumlah_msp'         => 'nullable|integer',
//             'waktu_start_engine' => 'nullable|date',
//             'jam_luncuran'       => 'nullable|date_format:H:i:s',
//             'jam_kerja'          => 'nullable|date_format:H:i:s',
//             'jam_mesin'          => 'nullable|date_format:H:i:s',
//             'jam_genset'         => 'nullable|date_format:H:i:s',
//             'counter_pecok'      => 'nullable|integer',
//             'oddometer'          => 'nullable|integer',
//             'penggunaan_hsd'     => 'nullable|numeric',
//             'hsd_tersedia'       => 'nullable|numeric',
//             'pengawal_id'        => 'nullable|exists:users,id',
//             'note'               => 'nullable|string|max:1000',
//             'user_id'            => 'nullable|array',
//             'user_id.*'          => 'exists:users,id',
//         ]);

//         $workResult->update([
//             'machine_id'        => $validated['machine_id'],
//             'region_id'         => $validated['region_id'],
//             'antara'            => $validated['antara'] ?? null,
//             'km_hm'             => $validated['km_hm'] ?? null,
//             'jumlah_msp'        => $validated['jumlah_msp'] ?? null,
//             'waktu_start_engine'=> $validated['waktu_start_engine'] ?? null,
//             'jam_luncuran'      => $validated['jam_luncuran'] ?? null,
//             'jam_kerja'         => $validated['jam_kerja'] ?? null,
//             'jam_mesin'         => $validated['jam_mesin'] ?? null,
//             'jam_genset'        => $validated['jam_genset'] ?? null,
//             'counter_pecok'     => $validated['counter_pecok'] ?? null,
//             'oddometer'         => $validated['oddometer'] ?? null,
//             'penggunaan_hsd'    => $validated['penggunaan_hsd'] ?? null,
//             'hsd_tersedia'      => $validated['hsd_tersedia'] ?? null,
//             'pengawal_id'       => $validated['pengawal_id'] ?? null,
//             'note'              => $validated['note'] ?? null,
//             'updated_by_id'     => auth()->id(),
//         ]);

//         if ($request->has('user_id')) {
//         $existingUserIds = $workResult->users()->pluck('users.id')->toArray();
//         $newUserIds = $validated['user_id'] ?? [];

//         sort($existingUserIds);
//         sort($newUserIds);

//         if ($existingUserIds !== $newUserIds) {
//                 $pivotData = [];
//                 foreach ($newUserIds as $userId) {
//                     $pivotData[$userId] = [
//                         'updated_at' => now(),
//                     ];
//                 }

//                 $workResult->users()->sync($pivotData);
//             }
//         }

//         DB::commit();

//         return redirect()->back()->with('success', 'Data berhasil diubah.');

//     } catch (\Illuminate\Validation\ValidationException $e) {
//         DB::rollBack();
//         return redirect()->back()->withErrors($e->errors())->withInput();
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
//     }
//   }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'working_report_id'  => 'required|exists:working_reports,id',
                'machine_id'         => 'required|exists:master_machines,id',
                'region_id'          => 'nullable|exists:master_regions,id',
                'tanggal'            => 'nullable|date',
                'cuaca'              => 'nullable|string|max:255',
                'wilayah'            => 'nullable|string|max:255',
                'petak_jalan'        => 'nullable|string|max:255',
                'jalur'              => 'nullable|string|max:255',
                'kelas_jalan'        => 'nullable|string|max:255',
                'kecepatan_lintas'   => 'nullable|string|max:255',
                'lokasi_awal1'       => 'nullable|string|max:255',
                'lokasi_akhir1'      => 'nullable|string|max:255',
                'jumlah1'            => 'nullable|numeric',
                'lokasi_awal2'       => 'nullable|string|max:255',
                'lokasi_akhir2'      => 'nullable|string|max:255',
                'jumlah2'            => 'nullable|numeric',
                'lokasi_awal3'       => 'nullable|string|max:255',
                'lokasi_akhir3'      => 'nullable|string|max:255',
                'jumlah3'            => 'nullable|numeric',
                'total_distance'     => 'nullable|numeric',
                'no_wesel1'          => 'nullable|string|max:255',
                'km_hm1'             => 'nullable|string|max:255',
                'jumlah_wesel1'      => 'nullable|integer',
                'no_wesel2'          => 'nullable|string|max:255',
                'km_hm2'             => 'nullable|string|max:255',
                'jumlah_wesel2'      => 'nullable|integer',
                'no_wesel3'          => 'nullable|string|max:255',
                'km_hm3'             => 'nullable|string|max:255',
                'jumlah_wesel3'      => 'nullable|integer',
                'total_wesel'        => 'nullable|integer',
                'waktu_start_engine' => 'nullable|date',
                'jam_luncuran'       => 'nullable|date_format:H:i:s',
                'jam_kerja'          => 'nullable|date_format:H:i:s',
                'jam_mesin'          => 'nullable|date_format:H:i:s',
                'jam_genset'         => 'nullable|date_format:H:i:s',
                'waktu_stop_engine'  => 'nullable|date',
                'counter_pecok'      => 'nullable|integer',
                'oddometer'          => 'nullable|integer',
                'penggunaan_hsd'     => 'nullable|numeric',
                'penggunaan_hsd1'    => 'nullable|numeric',
                'hsd_tersedia'       => 'nullable|numeric',
                'oddometer_hsd'      => 'nullable|integer',
                'pengawal_id'        => 'nullable|exists:users,id',
                'note'               => 'nullable|string|max:1000',
                'operator_by1'       => 'nullable|integer',
                'operator_by2'       => 'nullable|integer',
                'operator_by3'       => 'nullable|integer',
            ]);

            DB::beginTransaction();

            $workResult = WorkResult::findOrFail($id);

            $workResult->update([
                'working_report_id' => $validated['working_report_id'],
                'machine_id'        => $validated['machine_id'],
                'region_id'         => $validated['region_id'] ?? null,
                'tanggal'           => $request->tanggal,
                'cuaca'             => $request->cuaca,
                'wilayah'           => $request->wilayah,
                'petak_jalan'       => $request->petak_jalan,
                'jalur'             => $request->jalur,
                'kelas_jalan'       => $request->kelas_jalan,
                'kecepatan_lintas'  => $request->kecepatan_lintas,
                'lokasi_awal1'      => $request->lokasi_awal1,
                'lokasi_akhir1'     => $request->lokasi_akhir1,
                'jumlah1'           => $request->jumlah1,
                'lokasi_awal2'      => $request->lokasi_awal2,
                'lokasi_akhir2'     => $request->lokasi_akhir2,
                'jumlah2'           => $request->jumlah2,
                'lokasi_awal3'      => $request->lokasi_awal3,
                'lokasi_akhir3'     => $request->lokasi_akhir3,
                'jumlah3'           => $request->jumlah3,
                'total_distance'    => $request->total_distance,
                'no_wesel1'         => $request->no_wesel1,
                'km_hm1'            => $request->km_hm1,
                'jumlah_wesel1'     => $request->jumlah_wesel1,
                'no_wesel2'         => $request->no_wesel2,
                'km_hm2'            => $request->km_hm2,
                'jumlah_wesel2'     => $request->jumlah_wesel2,
                'no_wesel3'         => $request->no_wesel3,
                'km_hm3'            => $request->km_hm3,
                'jumlah_wesel3'     => $request->jumlah_wesel3,
                'total_wesel'       => $request->total_wesel,
                'waktu_start_engine'=> $request->waktu_start_engine,
                'jam_luncuran'      => $request->jam_luncuran,
                'jam_kerja'         => $request->jam_kerja,
                'jam_mesin'         => $request->jam_mesin,
                'jam_genset'        => $request->jam_genset,
                'waktu_stop_engine' => $request->waktu_stop_engine,
                'counter_pecok'     => $request->counter_pecok,
                'oddometer'         => $request->oddometer,
                'penggunaan_hsd'    => $request->penggunaan_hsd,
                'penggunaan_hsd1'   => $request->penggunaan_hsd1,
                'hsd_tersedia'      => $request->hsd_tersedia,
                'oddometer_hsd'     => $request->oddometer_hsd,
                'pengawal_id'       => $request->pengawal_id,
                'note'              => $request->note,
                'operator_by1'      => $request->operator_by1,
                'operator_by2'      => $request->operator_by2,
                'operator_by3'      => $request->operator_by3,
                'updated_by_id'     => auth()->id(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    $region = WorkResult::findOrFail($id);
    $region->delete();

    return redirect()->back()->with('success', __(
          'Data Laporan Kerja ":name" berhasil dihapus.',
          ['name' => $request->machine_id]
      ));
  }

  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
{
    $request->validated();
    $user = $request->user();

    $query = WorkResult::query();

    if ($request->search) {
        $search = '%' . $request->search . '%';
        $query->where(function (Builder $q) use ($search) {
            $searchable = ['antara','note','machine_id','region_id']; // kolom penting saja
            foreach ($searchable as $column) {
                $q->orWhere($column, 'like', $search);
            }
        });
    }

    $query->when(!$user->hasRole(['superuser','it']), fn(Builder $q) => $q->where('created_by_id', $user->id));

    $region = $query
        ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
        ->select([
            'id','machine_id','region_id','antara','km_hm','jumlah_msp',
            'waktu_start_engine','jam_luncuran','jam_kerja','jam_mesin','jam_genset',
            'counter_pecok','oddometer','penggunaan_hsd', 'penggunaan_hsd1', 'hsd_tersedia','pengawal_id','note',
            'created_by_id','updated_by_id'
        ])
        ->paginate($request->per_page ?: 10);

    return response()->json($region);
}

    public function approve(Request $request)
    {
        $user = auth()->user();
        $result = WorkResult::find($request->id);

        if (!$result) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $now = now();

        if ($result->operator_by1 == $user->id) {
            $result->operator_at1 = $now;
        } elseif ($result->operator_by2 == $user->id) {
            $result->operator_at2 = $now;
        } elseif ($result->operator_by3 == $user->id) {
            $result->operator_at3 = $now;
        } else {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk approve data ini.'], 403);
        }

        $result->save();

        return response()->json(['message' => 'Data berhasil disetujui.']);
    }
}
