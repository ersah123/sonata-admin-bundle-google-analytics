<?php
/**
 * Created by PhpStorm.
 * User: ersah
 * Date: 4/25/17
 * Time: 9:38 AM
 */

namespace Ersah\GABundle\Utils;


class GAMain extends GA
{
    public function getMainReport()
    {
        return [
            'from' => (new \DateTime($this->gaFrom))->format('Y-m-d'),
            'to' => (new \DateTime($this->gaTo))->format('Y-m-d'),
            'mainReport' => $this->getMainData(),
            'labels' => $this->mainMetrics
        ];
    }

    protected function getMainData()
    {
        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($this->gaFrom);
        $dateRange->setEndDate($this->gaTo);

        $metrics = $this->getMetrics($this->mainMetrics);

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($this->gaViewId);
        $request->setDateRanges($dateRange);
        $request->setMetrics($metrics);

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request));

        return $this->getAnalytics()->reports->batchGet($body);
    }
}