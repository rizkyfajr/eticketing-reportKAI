<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DataTableRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\MasterMachine;

class MasterMachineController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index(MasterMachine $machine)
  {
    return Inertia::render('Machine/Index', [
      'machine'      => $machine,
    ]);
  }
  
  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
  }
  
  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $request->validate([
        'code'      => 'required|string|unique:master_machines,code',
        'name'      => 'required|string|max:255',
        'type'      => 'nullable|string|max:255',
        'location'  => 'nullable|string|max:255',
        'status'    => 'in:aktif,nonaktif',
    ]);

    $machine = MasterMachine::create([
        'code'      => $request->code,
        'name'      => $request->name,
        'type'      => $request->type,
        'location'  => $request->location,
        'status'    => $request->status ?? 'aktif',
    ]);

    if ($machine) {
        return redirect()->back()->with('success', __(
            'Mesin ":name" berhasil ditambahkan.',
            ['name' => $request->name]
        ));
    }

      return redirect()->back()->with('error', __('Gagal menambahkan mesin.'));
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
    //
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
      $machine = MasterMachine::findOrFail($id);

      $validated = $request->validate([
          'code' => 'required|unique:master_machines,code,' . $id,
          'name' => 'required|string|max:255',
          'type' => 'nullable|string|max:255',
          'location' => 'nullable|string|max:255',
          'status' => 'in:aktif,nonaktif',
      ]);

      $machine->update($validated);

      return redirect()->back()->with('success', __(
            'Mesin ":name" berhasil diperbarui.',
            ['name' => $request->name]
      ));
  }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $id)
  {
      $machine = MasterMachine::findOrFail($id);
      $machine->delete();

      return redirect()->back()->with('success', __(
            'Mesin ":name" berhasil dihapus.',
            ['name' => $request->name]
        ));
  }

  /**
  * @return \Illuminate\Http\Response
  */
  public function get()
  {
    return MasterMachine::get();
  }

  /**
  * @param \App\Http\Requests\DataTableRequest $request
  * @return \Illuminate\Http\Response
  */
  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    $user = $request->user();

    $machines = MasterMachine::where(function (Builder $query) use ($request) {
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
    ->select(['id', 'code', 'name', 'type', 'location', 'status'])
    ->paginate($request->per_page ?: 10);

    return response()->json($machines);
  }
}
