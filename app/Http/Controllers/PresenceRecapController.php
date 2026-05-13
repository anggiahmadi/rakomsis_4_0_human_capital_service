<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresenceRecapRequest;
use App\Models\PresenceRecap;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PresenceRecapController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        // Coding here
    }

    public function index(Request $request)
    {
        $data = $this->getData($request);

        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PresenceRecapRequest $request)
    {
        return $this->successResponse($request->store(), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->successResponse(PresenceRecap::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PresenceRecapRequest $request, $id)
    {
        return $this->successResponse($request->update($id), Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = PresenceRecap::findOrFail($id);

        $data->delete();

        return $this->successResponse('deleted data is successfull');
    }

    public function getByCode(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required',
            'code' => 'required'
        ]);

        $data = PresenceRecap::where('tenant_id', $request->tenant_id)
            ->where('code', $request->code)
            ->first();

        return $this->successResponse($data);
    }

    public function getData($request)
    {
        ini_set('memory_limit', '-1');

        $data = PresenceRecap::where('tenant_id', $request->tenant_id);

        ($request->has('search_query') && $request->has('search_by')) ? $data = $data->where($request->search_by, 'LIKE', "%$request->search_query%") : $data;

        ($request->has('select_query') && $request->has('select_by')) ? $data = $data->where($request->select_by, $request->select_query) : $data;

        ($request->has('order_by') && $request->has('sort_by')) ? $data = $data->orderBy($request->order_by, $request->sort_by) : $data;

        ($request->data_type == "softdelete") ? $data = $data->onlyTrashed() : $data;

        ($request->has('page')) ? $data = $data->paginate($this->getPerPage($request->total_per_page)) : $data = $data->get();

        return $data;
    }

    public function datatables(Request $request)
    {
        $data = $this->getData($request);

        return DataTables::of($data)->toJson();
    }
}
