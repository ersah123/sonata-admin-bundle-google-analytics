<?php
/**
 * Created by PhpStorm.
 * User: ersah
 * Date: 4/25/17
 * Time: 9:37 AM
 */

namespace Ersah\GABundle\Utils;


class GAList extends GA
{
    public function getReport($nextPageToken = 0, $from = null, $to = null, $sort = null, $sortBy = null, $search = null)
    {
        $this->gaFrom = $from ? $from : $this->gaFrom;
        $this->gaTo = $to ? $to : $this->gaTo;
        $this->sort = $sort ? $sort : $this->sort;
        $this->sortBy = $sortBy ? $sortBy : $this->sortBy;
        $this->search = $search;

        return [
            'status' => 200,
            'from' => (new \DateTime($this->gaFrom))->format('Y-m-d'),
            'to' => (new \DateTime($this->gaTo))->format('Y-m-d'),
            'listLimit' => $this->gaEventLimit,
            'list' => $this->getData((string) $nextPageToken),
            'labels' =>  array_merge($this->dimensions, $this->listMetrics) // metrics should be second arg
        ];
    }

    /**
     * Returns Event data
     *
     * @param $nextPageToken
     * @return \Google_Service_AnalyticsReporting_GetReportsResponse
     */
    private function getData($nextPageToken)
    {
        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($this->gaFrom);
        $dateRange->setEndDate($this->gaTo);

        $metrics = $this->getMetrics($this->listMetrics);
        $dimensions = $this->getDimensions();

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($this->gaViewId);
        $request->setDateRanges($dateRange);
        $request->setMetrics($metrics);
        $request->setDimensions($dimensions);
        $request->setPageSize($this->gaEventLimit);
        $request->setPageToken($nextPageToken);

        if($this->sortBy !== ''){
            $ordering = $this->getSortBy();
            $request->setOrderBys($ordering);
        }

        if($this->search){
            $dimensionFilterClause = $this->getSearch();
            $request->setDimensionFilterClauses($dimensionFilterClause);
        }


        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request));

        return $this->getAnalytics()->reports->batchGet($body);
    }
}