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

class WorkingReportController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(WorkingReport $report)
  {
      return Inertia::render('WorkingReport/Index', [
          'report'      => $report,
          'machines'    => MasterMachine::select('id', 'name')->get(),
          'regions'     => MasterRegion::select('id', 'name')->get(),
          'users'       => User::select('id', 'name')->get(),
      ]);
  }

  public function detail(DataTableRequest $request, WorkingReport $report)
  {
      return Inertia::render('WorkingReport/Detail')->with([
          'report'      => $report,
          'machines'    => MasterMachine::select('id', 'name')->get(),
          'regions'     => MasterRegion::select('id', 'name')->get(),
          'users'       => User::select('id', 'name')->get(),
      ]);
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create(WorkingReport $report)
  {
      return Inertia::render('WorkingReport/Create', [
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
          'machine_id'  => 'required|exists:master_machines,id',
          'region_id'   => 'required|exists:master_regions,id',
          'date'        => 'nullable|date',
          'has_trouble' => 'nullable',
          'status'      => 'nullable|in:draft,checksheet_done,warming_up_done,photo_uploaded,work_done,verification,finished',
      ]);

      $validated['created_by_id'] = auth()->id();
      $validated['status'] = $validated['status'] ?? 'draft';

      $report = WorkingReport::create($validated);
      
    return redirect()->route('working-reports.index')->with('success', 'Working Report berhasil disimpan.');
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
      $report = WorkingReport::findOrFail($id);

      return Inertia::render('WorkingReport/Update', [
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
      $report = WorkingReport::findOrFail($id);

      $validated = $request->validate([
          'machine_id'        => 'required|exists:master_machines,id',
          'region_id'         => 'required|exists:master_regions,id',
          'date'              => 'nullable|date',
          'has_trouble'       => 'nullable',
          'status'            => 'nullable|in:draft,checksheet_done,warming_up_done,photo_uploaded,work_done,verification,finished',
      ]);

      $report->update($validated);

      return redirect()->route('working-reports.index')->with('success', 'Working Report berhasil diubah.');
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
    $region = WorkingReport::findOrFail($id);
    $region->delete();

    return redirect()->back()->with('success', __(
          'Data Working Report ":name" berhasil dihapus.',
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

    $report = WorkingReport::where(function (Builder $query) use ($request) {
        $search = '%' . $request->search . '%';
        $model = $query->getModel();

        foreach ($model->getFillable() as $column) {
            $query->orWhere($column, 'like', $search);
        }
    })
    ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
    ->when(!$user->hasRole(['superuser', 'it']), fn (Builder $query) => 
        $query->where('created_by_id', $user->id)
    )
    ->select([
      'id',
      'machine_id',
      'region_id',
      'status',
      'date'
      ])
    ->paginate($request->per_page ?: 10);

    return response()->json($report);
  }
}
