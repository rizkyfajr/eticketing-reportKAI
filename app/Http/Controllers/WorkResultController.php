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
use App\Models\WorkResult;

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
  public function store(Request $request)
{
    $validated = $request->validate([
        'machine_id' => 'required|exists:master_machines,id',
        'region_id' => 'required|exists:master_regions,id',
        'antara' => 'nullable|string',
        'km_hm' => 'nullable|numeric',
        'jumlah_msp' => 'nullable|numeric',
        'counter_pecok' => 'nullable|numeric',
        'oddometer' => 'nullable|numeric',
        'penggunaan_hsd' => 'nullable|numeric',
        'hsd_tersedia' => 'nullable|numeric',
        'waktu_start_engine' => 'nullable|string',
        'jam_luncuran' => 'nullable|string',
        'jam_kerja' => 'nullable|string',
        'jam_mesin' => 'nullable|string',
        'jam_genset' => 'nullable|string',
        'pengawal_id' => 'nullable|exists:users,id',
        'note' => 'nullable|string',
    ]);

    $validated['created_by_id'] = auth()->id();

    $report = WorkResult::create($validated);

    // // Jika ada relasi crew
    // if ($request->has('users')) {
    //     foreach ($request->users as $userId) {
    //         $report->reportUsers()->create([
    //             'user_id' => $userId,
    //         ]);
    //     }
    // }

    // return redirect()->back()->with('success', 'Data berhasil disimpan.');
    
   return redirect()->route('work-results.index')->with('success', 'Laporan pekerjaan berhasil disimpan.');
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
  public function update(Request $request, $id)
  {
      $report = WorkResult::findOrFail($id);

      $validated = $request->validate([
          'machine_id'        => 'required|exists:master_machines,id',
          'region_id'         => 'required|exists:master_regions,id',
          'antara'            => 'nullable|string|max:255',
          'km_hm'             => 'nullable|string|max:255',
          'jumlah_msp'        => 'nullable|numeric',
          'waktu_start_engine'=> 'nullable|date_format:H:i',
          'jam_luncuran'      => 'nullable|string|max:255',
          'jam_kerja'         => 'nullable|string|max:255',
          'jam_mesin'         => 'nullable|string|max:255',
          'jam_genset'        => 'nullable|string|max:255',
          'counter_pecok'     => 'nullable|string|max:255',
          'oddometer'         => 'nullable|string|max:255',
          'penggunaan_hsd'    => 'nullable|string|max:255',
          'hsd_tersedia'      => 'nullable|string|max:255',
          'pengawal_id'       => 'nullable|exists:users,id',
          'note'              => 'nullable|string|max:1000',
      ]);

      $report->update($validated);

      return redirect()->route('work-results.index')
                      ->with('success', 'Laporan pekerjaan berhasil diubah.');
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
            'counter_pecok','oddometer','penggunaan_hsd','hsd_tersedia','pengawal_id','note',
            'created_by_id','updated_by_id'
        ])
        ->paginate($request->per_page ?: 10);

    return response()->json($region);
}
}
