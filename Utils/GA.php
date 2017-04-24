<?php


namespace Ersah\GABundle\Utils;

use Google_Service_AnalyticsReporting;

class GA
{
    private $gaViewId;
    private $gaEventLimit;
    private $gaFrom;
    private $gaTo;
    private $sort;
    private $sortBy;
    private $search;
    private $dimensions;
    private $metrics;

    /**
     * @var Google_Service_AnalyticsReporting
     */
    private $analytics;

    function __construct(
        $gaViewId,
        $gaEventLimit,
        $gaFrom,
        $gaTo,
        $sort,
        $sortBy,
        $keyFileLocation,
        $dimensions,
        $metrics
    ) {
        if (!file_exists($keyFileLocation)) {
            throw new \Exception(
                "can't find file key location defined by google_analytics_json_key parameter, ex : ../data/analytics/analytics-key.json"
            );
        }

        $this->client = new \Google_Client();
        $this->client->setApplicationName("GoogleAnalytics");
        $this->client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->client->setAuthConfig($keyFileLocation);
        $this->analytics = new Google_Service_AnalyticsReporting($this->client);

        $this->gaViewId = $gaViewId;
        $this->gaEventLimit = $gaEventLimit;
        $this->gaFrom = $gaFrom;
        $this->gaTo = $gaTo;
        $this->sort = $sort;
        $this->sortBy = $sortBy;
        $this->dimensions = $dimensions;
        $this->metrics = $metrics;
    }


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
            'event' => $this->getData((string) $nextPageToken),
            'labels' =>  array_merge($this->dimensions, $this->metrics) // metrics should be at the end
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

        $metrics = $this->getMetrics();
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

    /**
     * Do string search on corresponding column
     *
     * @return \Google_Service_AnalyticsReporting_DimensionFilterClause
     */
    private function getSearch()
    {
        $searchVal = array_values($this->search)[0];
        $searchKey = array_keys($this->search)[0];
        // Create Dimension Filter.
        $dimensionFilter = new \Google_Service_AnalyticsReporting_DimensionFilter();
        $dimensionFilter->setDimensionName($searchKey);
        //$dimensionFilter->setOperator("EXACT");
        $dimensionFilter->setExpressions($searchVal);
        // Create the DimensionFilterClauses
        $dimensionFilterClause = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
        $dimensionFilterClause->setFilters(array($dimensionFilter));
        return $dimensionFilterClause;
    }

    /**
     * Sort corresponding column
     *
     * @return \Google_Service_AnalyticsReporting_OrderBy
     */
    private function getSortBy()
    {
        $ordering = new \Google_Service_AnalyticsReporting_OrderBy();
        $ordering->setFieldName($this->sortBy);
        $ordering->setOrderType("VALUE");
        $ordering->setSortOrder($this->sort);

        return $ordering;
    }

    /**
     * Get configured metrics
     *
     * @return array
     */
    private function getMetrics()
    {
        $metrics = array();
        foreach ($this->metrics as  $metric) {
            $met = new \Google_Service_AnalyticsReporting_Metric();
            $met->setExpression($metric['value']);
            array_push($metrics, $met);
        }

        return $metrics;
    }

    /**
     * Get configured Dimensions
     *
     * @return array
     */
    private function getDimensions()
    {
        $dimensions = array();
        foreach ($this->dimensions as  $dimension) {
            $dim = new \Google_Service_AnalyticsReporting_Dimension();
            $dim->setName($dimension['value']);
            array_push($dimensions, $dim);
        }

        return $dimensions;
    }

    /**
     * @return Google_Service_AnalyticsReporting
     */
    private function getAnalytics()
    {
        return $this->analytics;
    }
}
