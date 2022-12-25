<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

use App\Http\Requests\DistanceFeedRequest;
use App\Http\Requests\NotificationRequest;
use App\Services\DistanceFeedService;
use DB;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     * @var DistanceFeedService
     */
    protected $repository;
    protected $distanceFeedService;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     * @param DistanceFeedService $distanceFeedService
     */

    public function __construct(BookingRepository $bookingRepository, DistanceFeedService $distanceFeedService)
    {
        $this->repository = $bookingRepository;
        $this->distanceFeedService = $distanceFeedService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $response = 'No response';

        if($request->get('user_id')) {

            $response = $this->repository->getUsersJobs($request->get('user_id'));

        } elseif($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID')) {

            $response = $this->repository->getAll($request);

        }

        return response($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $job = $this->repository->with('translatorJobRel.user')->find($id);

        return response($job);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $response = $this->repository->store($request->get('__authenticatedUser'), $request->all());

        return response($response);

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $response = $this->repository->updateJob($id, $request->except(['_token', 'submit']), $request->get('__authenticatedUser'));

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        $response = $this->repository->storeJobEmail($request->all());

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if($request->get('user_id')) {

            $response = $this->repository->getUsersJobsHistory($request->get('user_id'), $request);

            return response($response);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        $response = $this->repository->acceptJob($request->all(), $request->get('__authenticatedUser'));

        return response($response);
    }

    public function acceptJobWithId(Request $request)
    {
        $response = $this->repository->acceptJobWithId($request->get('job_id'), $request->get('__authenticatedUser'));

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        $response = $this->repository->cancelJobAjax($request->all(), $request->get('__authenticatedUser'));

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        $response = $this->repository->endJob($request->all());

        return response($response);

    }

    public function customerNotCall(Request $request)
    {
        $response = $this->repository->customerNotCall($request->all());

        return response($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        $response = $this->repository->getPotentialJobs($request->get('__authenticatedUser'));

        return response($response);
    }

    public function distanceFeed(DistanceFeedRequest $request)
    {
        if ($request->get('flagged') == 'true' && ! $request->filled('admincomment')) {
            return $this->distanceFeedService->returnResponse('Please, add comment');
        }

        return DB::transaction(function () use ($request) {
            return $this->distanceFeedService
                ->makeDistanceData($request)
                ->makeJobData($request)
                ->updateDistance($request->input('jobid'))
                ->updateJob($request->input('jobid'))
                ->returnResponse();
        });

    }

    public function reopen(Request $request)
    {
        return response($this->repository->reopen($request->all()));
    }

    public function resendNotifications(NotificationRequest $request)
    {
        $job_data = $this->repository->jobToData($request->get('jobid'));

        $this->repository->sendNotificationTranslator($request->get('jobid'), $job_data, '*');

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(NotificationRequest $request)
    {
        $job = $this->repository->find($request->get('jobid'));

        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }

}
