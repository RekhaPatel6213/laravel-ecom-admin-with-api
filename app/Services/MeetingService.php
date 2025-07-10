<?php

namespace App\Services;

use App\Http\Resources\MeetingResource;
use App\Models\Distributor;
use App\Models\Meeting;
use App\Models\User;
use App\Repositories\MeetingRepository;
use App\Traits\FileTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class MeetingService
{
    use FileTrait;

    protected $repository;

    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->repository = $meetingRepository;
    }

    public function getReportMeetings(string $startDate, string $endDate, int $userId)
    {
        $meetings = $this->repository->getQueryBuilder(null, 'meeting_date', 'asc')
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('meeting_date', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('meeting_date', '<=', date('Y-m-d', strtotime($endDate)));
            })
            ->with([
                'type:id,name',
                'distributor' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'mobile', 'address', 'country_id', 'state_id', 'city_id', 'zone_id');
                    $query->with(['country:id,name', 'state:id,name', 'city:id,name', 'zone:id,name']);
                },
            ])
            ->get();

        $meetings = $meetings->groupBy(function ($order) {
            return Carbon::parse($order->meeting_date)->format('d-m-Y');
        });

        return $meetings;
    }

    public function apiReport($requestData)
    {
        $startDate = $requestData->start_date;
        $endDate = $requestData->end_date;
        $user = Auth::user();
        $userId = $user->id;

        // Get Meeting Data
        $meetingList = $this->getReportMeetings($startDate, $endDate, $userId);

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $durations = $startDate->diffInDays($endDate) + 1;
        $pdfName = 'Meeting Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') (' . $startDate->format('d-m-Y') . ' To ' . $endDate->format('d-m-Y') . ').pdf';

        $pdf = PDF::loadView('pdf.meeting_report_pdf', ['start_date' => $startDate->format('d-m-Y'), 'end_date' => $endDate->format('d-m-Y'), 'durations' => $durations, 'user_data' => Auth::user(), 'meetingList' => $meetingList, 'pdfName' => $pdfName]);
        $pdfContent = $pdf->download()->getOriginalContent();


        $client = Storage::createLocalDriver(['root' => storage_path('app/public') . '/meeting_report']);
        $client->put($pdfName, $pdfContent);

        return [['pdf_path' => asset('report/meeting_report/' . $pdfName)]];
    }

    public function generateLocalPdfReport($startDate, $endDate)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Get Meeting Data
        $meetingList = $this->getReportMeetings($startDate, $endDate, $userId);

        $startDateObj = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);
        $durations = $startDateObj->diffInDays($endDateObj) + 1;

        $pdfName = 'Meeting Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') (' . $startDateObj->format('d-m-Y') . ' To ' . $endDateObj->format('d-m-Y') . ').pdf';

        $pdf = PDF::loadView('pdf.meeting_report_pdf', [
            'start_date' => $startDateObj->format('d-m-Y'),
            'end_date' => $endDateObj->format('d-m-Y'),
            'durations' => $durations,
            'user_data' => $user,
            'meetingList' => $meetingList,
            'pdfName' => $pdfName
        ]);

        $storagePath = storage_path('app/public/meeting_report/');

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $pdf->save($storagePath . $pdfName);

        return [
            'pdf_path' => $storagePath . $pdfName,
            'pdf_name' => $pdfName
        ];
    }

    public function apiList(Request $requestData, $resource = true)
    {
        $meetingIds = Auth::user()->meeting_id;
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'meeting_date') ?? 'meeting_date';
        $sortOrder = $requestData->query('sort', 'desc') ?? 'desc';

        $startDate = $requestData->query('start_date', null) ?? null;
        $endDate = $requestData->query('end_date', null) ?? null;
        $distributorId = $requestData->query('distributor_id', null) ?? null;
        $userId = (int) $requestData->query('user_id', null);
        //\Log::info('User ID:', ['user_id' => $userId]);
        //\Log::info('User ID Type:', ['type' => gettype($userId), 'value' => $userId]);
        //\Log::info('Auth User ID:', ['auth_user_id' => Auth::user()->id]);

        $meetings = $this->repository->getQueryBuilder(null, $sortOn, $sortOrder)
            ->select('id', 'meeting_date', 'user_id', 'distributor_id', 'start_time', 'shop_id', 'type_id', 'start_latitude', 'start_longitude', 'end_time', 'end_latitude', 'end_longitude', 'comments', 'purpose', 'attachment1', 'attachment2', 'attachment3')
            ->whereNotNull('end_time')
            ->when($meetingIds !== null, function (Builder $query) use ($meetingIds) {
                $query->whereIn('id', $meetingIds);
            })
            ->when($resource === true, function (Builder $query) use ($userId) {
                $query->when(($userId !== null && $userId > 0), function (Builder $query) use ($userId) {
                    $query->where('user_id', $userId)->withoutGlobalScopes();
                }, function (Builder $query) {
                    $query->where('user_id', Auth::user()->id);
                });
            })
            ->when($search !== null, function (Builder $query) use ($search, $resource) {
                $query->where(function ($query_data) use ($search, $resource) {
                    $query_data->where('meeting_date', 'like', '%' . $search . '%')
                        ->orWhereHas('distributor', function ($subQuery1) use ($search) {
                            $subQuery1->where('firstname', 'like', "%$search%");
                            $subQuery1->orWhere('lastname', 'like', "%$search%");
                        })
                        ->orWhereHas('shop', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('type', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->when($resource === false, function (Builder $subQuery2) use ($search) {
                            $subQuery2->orWhereHas('user', function ($subQuery3) use ($search) {
                                $subQuery3->where('firstname', 'like', "%$search%");
                                $subQuery3->orWhere('lastname', 'like', "%$search%");
                            });
                        });
                });
            })
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('meeting_date', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('meeting_date', '<=', date('Y-m-d', strtotime($endDate)));
            })
            ->when(($startDate !== null && $endDate === null), function ($query) use ($startDate) {
                $query->whereDate('meeting_date', date('Y-m-d', strtotime($startDate)));
            })
            ->when(($startDate === null && $endDate !== null), function ($query) use ($endDate) {
                $query->whereDate('meeting_date', date('Y-m-d', strtotime($endDate)));
            })
            ->when($distributorId !== null, function ($query) use ($distributorId) {
                $query->where('distributor_id', $distributorId);
            })
            ->when($resource === false, function (Builder $query) {
                $query->with(['user:id,firstname,lastname', 'distributor.city:id,name']);
            })
            ->with([
                // 'user:id,firstname,lastname',
                'distributor:id,firstname,lastname,mobile,city_id,is_interested',
                'shop:id,name',
                'type:id,name',
            ])
            ->get();

        return $resource === true ? MeetingResource::collection($meetings) : $meetings;
    }

    public function create(array $requestData)
    {
        $meeting = Meeting::where('user_id', Auth::user()->id)->whereNull(['end_time', 'end_latitude', 'end_longitude'])->first();
        if (empty($meeting)) {
            $meeting = $this->repository->updateOrCreate($requestData, null);
        }

        return [['meeting_id' => $meeting->id]];
    }

    public function update(array $requestData)
    {
        // $meeting = Meeting::whereNull(['end_time','end_latitude','end_longitude'])->find($requestData['id']);
        $meeting = Meeting::where('user_id', Auth::user()->id)->whereNull(['end_time', 'end_latitude', 'end_longitude'])->first();
        if ($meeting) {
            \Log::info('update');
            $requestData['attachment1'] = $this->storeFile('attachment1');
            $requestData['attachment2'] = $this->storeFile('attachment2');
            $requestData['attachment3'] = $this->storeFile('attachment3');
            $requestData['purpose'] = $requestData['purposeOfMeeting'];

            $meeting = $this->repository->updateOrCreate($requestData, $meeting);

            $distributor = $meeting->distributor;

            if (isset($requestData['type_id']) && (int) $requestData['type_id'] === 2 && (int) $distributor->is_interested === 0) {
                Distributor::where('id', $meeting->distributor_id)->update(['is_interested' => config('constants.MEETING_NOT_INTERESTED')]);
            } elseif (isset($requestData['type_id']) && (int) $requestData['type_id'] !== 2 && (int) $distributor->is_interested === 0) {
                Distributor::where('id', $meeting->distributor_id)->update(['is_interested' => config('constants.MEETING_INTERESTED')]);
            }

            return [['meeting_id' => $meeting->id]];
        }

        return [['meeting_id' => $requestData['id']]];
    }

    public function detail(Meeting $meeting, bool $isApi = true)
    {
        $meeting = new MeetingResource($meeting);

        return $isApi === true ? [$meeting] : $meeting;
    }

    public function list($requestData)
    {
        $meetings = $this->apiList($requestData, false);
        $meetingArray = [];

        if ($meetings) {
            foreach ($meetings as $key => $meeting) {
                // $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$meeting->id.'" class="form-check-input checkboxes">';

                // $attachment1 = $meeting->attachment1 != '' ? "<a href='".route('meeting.show', $meeting->attachment1)."' title='Attachment 1'><i class='fa fa-eye'></i></a>": '';
                // $attachment2 = $meeting->attachment2 != '' ? "<a href='".route('meeting.show', $meeting->attachment2)."' title='Attachment 2'><i class='fa fa-eye'></i></a>": '';
                // $attachment3 = $meeting->attachment3 != '' ? "<a href='".route('meeting.show', $meeting->attachment3)."' title='Attachment 3'><i class='fa fa-eye'></i></a>": '';
                $meetingDate = $meeting->meeting_date->format(config('constants.DATE_FORMATE'));

                $data['srno'] = $key + 1;
                $data['sale_person'] = ($meeting->user->firstname ?? '') . ' ' . ($meeting->user->lastname ?? '');
                $data['distributor'] = ($meeting->distributor->firstname ?? '') . ' ' . ($meeting->distributor->lastname ?? '');
                $data['is_interested'] = $meeting->distributor->is_interested ?? 0;
                $data['shop'] = $meeting->shop->name ?? '';
                $data['city'] = $meeting->distributor->city->name ?? '';
                $data['mobile'] = $meeting->distributor->mobile ?? '';
                $data['type'] = $meeting->type->name ?? '';
                $data['start_time'] = $meetingDate . ' ' . Carbon::parse($meeting->start_time)->format(config('constants.TIME_FORMATE'));
                $data['end_time'] = $meeting->end_time !== null ? $meetingDate . ' ' . Carbon::parse($meeting->end_time)->format(config('constants.TIME_FORMATE')) : null;
                $data['action'] = "<a href='" . route('meeting.show', $meeting->id) . "' title='Show'><i class='fa fa-eye'></i></a>";
                // $data['start_latitude'] = $meeting->start_latitude;
                // $data['start_longitude'] = $meeting->start_longitude;
                // $data['end_latitude'] = $meeting->end_latitude;
                // $data['end_longitude'] = $meeting->end_longitude;
                // $data['comments'] = ($meeting->comments??'').$attachment1.$attachment2.$attachment3;

                $data['start_map_link'] = getGoogleMapLink($meeting->start_latitude, $meeting->start_longitude);
                $data['end_map_link'] = getGoogleMapLink($meeting->end_latitude, $meeting->end_longitude);

                $meetingArray[] = $data;
            }
        }

        return $meetingArray;
    }
}
