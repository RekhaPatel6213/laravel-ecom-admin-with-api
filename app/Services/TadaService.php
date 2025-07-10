<?php

namespace App\Services;

use App\Http\Resources\TadaResource;
use App\Models\Tada;
use App\Models\TadaType;
use App\Models\User;
use App\Repositories\TadaRepository;
use App\Traits\FileTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Arr;

// use Carbon\Carbon;

class TadaService
{
    use FileTrait;

    protected $repository;

    public function __construct(TadaRepository $tadaRepository)
    {
        $this->repository = $tadaRepository;
    }

    public function apiReport($requestData)
    {
        $startDate = $requestData->start_date;
        $endDate = $requestData->end_date;
        $user = Auth::user(); //User::find(3);
        $userId = $user->id;

        $tadaData = $this->repository->getQueryBuilder(null, 'date', 'asc')
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($endDate)));
            })
            ->orderBy('id', 'asc')
            ->with('tadatype:id,name')
            ->get();

        $tadaData = $tadaData->groupBy(function ($order) {
            return Carbon::parse($order->date)->format('d-m-Y');
        });

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $durations = $startDate->diffInDays($endDate) + 1;
        $pdfName = 'Expense Report (' . $user->firstname . ' ' . $user->lastname . ') (' . $startDate->format('d-m-Y').' To '.$endDate->format('d-m-Y') . ').pdf';


        //return view('pdf.expense_report', ['start_date' => $startDate, 'end_date' => $endDate, 'tada_data' => $tadaData, 'user_data' => Auth::user()]);
        $pdf = PDF::loadView('pdf.expense_report', ['start_date' => $startDate->format('d-m-Y'), 'end_date' => $endDate->format('d-m-Y'), 'durations' => $durations, 'tada_data' => $tadaData, 'user_data' => Auth::user(), 'pdfName' => $pdfName]);
        //$pdf->setPaper('a4', 'landscape');
        $pdfContent = $pdf->download()->getOriginalContent();

        
        //return $pdf->stream($pdfName);

        $client = Storage::createLocalDriver(['root' => storage_path('app/public') . '/expense_report']);
        $client->put($pdfName, $pdfContent);

        return [['pdf_path' => asset('report/expense_report/' . $pdfName)]];
    }

    public function apiList($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $startDate = $requestData->query('start_date', null) ?? null;
        $endDate = $requestData->query('end_date', null) ?? null;
        $userId = $requestData->query('user_id', null) ?? null;

        $tadas = $this->repository->getQueryBuilder(null, 'id', 'desc')
            // ->where('user_id', Auth::user()->id)
            ->when($userId !== null, function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            }, function (Builder $query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->with(['tadatype:id,name', 'user:id,firstname,lastname'])
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($endDate)));
            })
            ->when(($startDate !== null && $endDate === null), function ($query) use ($startDate) {
                $query->whereDate('date', date('Y-m-d', strtotime($startDate)));
            })
            ->when(($startDate === null && $endDate !== null), function ($query) use ($endDate) {
                $query->whereDate('date', date('Y-m-d', strtotime($endDate)));
            })
            ->when($search !== null, function (Builder $query) use ($search) {
                $query->where(function ($query_data) use ($search) {
                    $query_data->where('date', 'like', '%' . $search . '%')
                        ->orWhere('type', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhere('comment', 'like', '%' . $search . '%')
                        ->orWhere('km', 'like', '%' . $search . '%')
                        ->orWhere('per_km_price', 'like', '%' . $search . '%')
                        ->orWhere('from', 'like', '%' . $search . '%')
                        ->orWhere('to', 'like', '%' . $search . '%')
                        ->orWhereHas('tadatype', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                            $subQuery1->where('type', 'like', "%$search%");
                        });
                });
            })
            ->get();

        return TadaResource::collection($tadas);
    }

    public function apiCreate(array $requestData)
    {
        $requestData['user_id'] = Auth::user()->id;
        $requestData['date'] = $requestData['date'] !== null ? date('Y-m-d', strtotime($requestData['date'])) : null;
        // $requestData['end_date'] = $requestData['end_date'] !== null ? date('Y-m-d', strtotime($requestData['end_date'])): null;
        $requestData['to'] = $requestData['to']??null;
        $requestData['from'] = $requestData['location'] ?? ($requestData['from'] ?? null);
        $requestData['km'] = $requestData['km']??null;
        $requestData['per_km_price'] = $requestData['per_km_price']??null;
        $requestData['is_confirm'] = 0;
        $requestData['photo'] = $this->storeFile('photo');
        $tada = $this->repository->updateOrCreate($requestData, null);

        return [['tada_id' => $tada->id]];
    }

    public function apiTadaType(array $requestData)
    {
        $tadaType = TadaType::select('id', 'name', 'is_amount', 'is_photo', 'is_location', 'is_expense_name', 'is_from_to_location', 'is_km')
            ->where('status', config('constants.ACTIVE'))
            ->orderBy('name', 'ASC')
            ->get();

        $dailyTadaId = $tadaType->where('name', 'Daily Allowance')->first()->id??null;
        if($dailyTadaId != null){

            $tada = Tada::where('date', date('Y-m-d', strtotime($requestData['date'])))
                //->whereNotNull('daily_allowance')
                ->where('tadatype_id', $dailyTadaId)
                ->where('user_id', Auth::user()->id)
                ->orderBy('id', 'ASC')
                ->first();

            if($tada) {
                //$tadaType = $tadaType->whereNotIn('id', [$dailyTadaId])->all();
                $tadaType = TadaType::select('id', 'name', 'is_amount', 'is_photo', 'is_location', 'is_expense_name', 'is_from_to_location', 'is_km')
                    ->whereNotIn('id', [$dailyTadaId])
                    ->where('status', config('constants.ACTIVE'))
                    ->orderBy('name', 'ASC')
                    ->get();
            }
        }

        return [['tadaType' => $tadaType, 'daily_allowance' => $tada->daily_allowance ?? '']];
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'date') ?? 'date';
        $sortOrder = $requestData->query('sort', 'desc') ?? 'desc';
        $tadas = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->with([
                'tadatype:id,name',
                'user:id,firstname,lastname',
            ])
            ->get();
        $tadaArray = [];

        if ($tadas) {
            foreach ($tadas as $key => $tada) {

                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="' . $tada->id . '" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $tada->user->firstname . ' ' . $tada->user->lastname;
                $data['location'] = $tada->from . ($tada->to !== null ? ' To ' . $tada->to : '');
                $data['type'] = $tada->tadaType->name . ($tada->km > 0 ? ' - ' . $tada->km . ' KM' : '');
                $data['value'] = $tada->photo !== null ? '<a href="' . asset('storage/' . $tada->photo) . '" target="_blank" >Document</a>' : null;
                $data['expance'] = ((strpos($tada->tadatype->name, 'Travel') !== false) && $tada->expense_name === null) ? 'Auto' : $tada->expense_name; // $tada->expense_name??'';
                $data['amount'] = config('constants.currency_symbol') . ' ' . number_format($tada->amount, 2);
                $data['is_confirm'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='" . $tada->id . "' " . ($tada->is_confirm == 1 ? 'checked' : '') . '/></div>';
                // $data['date'] = ($tada->date !== null ? Carbon::parse($tada->date)->format(config('constant.DATE_FORMATE', 'd-m-Y')) : ''); // .($tada->end_date !== null ? Carbon::parse($tada->end_date)->format(config('constant.DATE_FORMATE', 'd-m-Y')): '');
                $data['date'] = !empty($tada->date) ? $tada->date->format(config('constants.DATE_FORMATE')) : "-";

                $data['created_at'] = Carbon::parse($tada->created_at)->format(config('constant.DATE_FORMATE', 'd-m-Y'));
                $data['comment'] = $tada->comment;
                $data['per_km_price'] = $tada->km > 0 ? (config('constants.currency_symbol') . ' ' . $tada->per_km_price) : '';
                $data['da'] = $tada->daily_allowance > 0 ? config('constants.currency_symbol') . ' ' . $tada->daily_allowance : '';
                $data['action'] = ''; // "<a href='".route('tada.edit', $tada->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $tadaArray[] = $data;
            }
        }

        return $tadaArray;
    }

    public function bulkUpdate(string $columnName, array $requestData)
    {
        $status = false;
        $message = __('message.oopsError');
        $requestData['status'] = $requestData['status'] === config('constants.ACTIVE') ? 1 : 0;

        if ($this->repository->bulkUpdate($columnName, $requestData)) {
            $status = true;
            $message = __('message.submitSuccess');
        }

        return ['status' => $status, 'message' => $message];
    }
}
