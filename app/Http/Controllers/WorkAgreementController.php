<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkAgreementRequest;
use App\Models\WorkAgreement;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class WorkAgreementController extends Controller
{
    use ApiResponser;

    private $table_name = 'work_agreements';
    private $prefix_name = 'WAG';

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
    public function store(WorkAgreementRequest $request)
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
        return $this->successResponse(WorkAgreement::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkAgreementRequest $request, $id)
    {
        return $this->successResponse($request->update($id), Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->is_permanent_delete) {
            DB::beginTransaction();

            $data = WorkAgreement::withTrashed()->findOrFail($id);

            $data->forceDelete();

            DB::commit();
        } else {
            $data = WorkAgreement::findOrFail($id);

            $data->delete();
        }

        return $this->successResponse('deleted data is successfull');
    }

    public function restore($id)
    {
        $data = WorkAgreement::withTrashed()->findOrFail($id);

        $data->restore();

        return $this->successResponse("Successfully restore data");
    }

    public function getByCode(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required',
            'code' => 'required'
        ]);

        $data = WorkAgreement::where('tenant_id', $request->tenant_id)
            ->where('code', $request->code)
            ->first();

        return $this->successResponse($data);
    }

    public function getCode(Request $request)
    {
        $data = Controller::getMasterCode($this->table_name, $this->prefix_name, $request->tenant_id);

        return $this->successResponse($data);
    }

    public function getData($request)
    {
        ini_set('memory_limit', '-1');

        $data = WorkAgreement::where('tenant_id', $request->tenant_id);

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
