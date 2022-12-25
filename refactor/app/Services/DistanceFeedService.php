<?php

namespace App\Services;

use DTApi\Models\Job;
use DTApi\Models\Distance;

class DistanceFeedService
{
    protected array $affectedDistanceRowData;
    protected array $affectedJobRowsData;

    public function makeDistanceData($requestData): DistanceFeedService
    {
        $this->affectedDistanceRowData = [
            'distance' => $requestData->filled('distance') ? $requestData->get('distance'): '',
            'time' => $requestData->filled('time') ? $requestData->get('time'): '',
        ];

        return $this;
    }

    public function makeJobData($request): DistanceFeedService
    {
        $this->affectedJobRowsData = [
            'session_time' => $request->filled('session_time') ? $request->input('session_time'): '',
            'flagged' => $request->get('flagged') == 'true' ? 'yes' : 'no',
            'manually_handled' => $request->get('manually_handled') == 'true' ? 'yes' : 'no',
            'by_admin' => $request->get('by_admin') == 'true' ? 'yes' : 'no',
            'admin_comments' => $request->filled('admincomment') ? $request->get('admincomment'): '',
        ];

        return $this;
    }

    public function updateDistance($job_id): DistanceFeedService
    {
        if ($this->affectedDistanceRowData['time'] || $this->affectedDistanceRowData['distance']) {
            Distance::where('job_id', '=', $job_id)->update($this->affectedDistanceRowData);
        }

        return $this;
    }

    public function updateJob($job_id): DistanceFeedService
    {
        if ($this->affectedJobRowsData['admin_comments'] ||
            $this->affectedJobRowsData['session_time'] ||
            $this->affectedJobRowsData['flagged'] ||
            $this->affectedJobRowsData['manually_handled'] ||
            $this->affectedJobRowsData['by_admin'])
        {
            Job::where('id', '=', $job_id)->update($this->affectedJobRowsData);
        }

        return $this;
    }

    public function returnResponse(string $response = 'Record updated!')
    {
        return response($response);
    }
}
