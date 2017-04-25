<?php


namespace Ersah\GABundle\Utils;

use Google_Service_AnalyticsReporting;

class GA
{
    protected $gaViewId;
    protected $gaEventLimit;
    protected $gaFrom;
    protected $gaTo;
    protected $sort;
    protected $sortBy;
    protected $search;
    protected $dimensions;
    protected $listMetrics;
    protected $mainMetrics;

    /**
     * @var Google_Service_AnalyticsReporting
     */
    protected $analytics;

    function __construct(
        $gaViewId,
        $gaEventLimit,
        $gaFrom,
        $gaTo,
        $sort,
        $sortBy,
        $keyFileLocation,
        $dimensions,
        $listMetrics,
        $mainMetrics
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
        $this->listMetrics = $listMetrics;
        $this->mainMetrics = $mainMetrics;
    }

    /**
     * Do string search on corresponding column
     *
     * @return \Google_Service_AnalyticsReporting_DimensionFilterClause
     */
    protected function getSearch()
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
    protected function getSortBy()
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
    protected function getMetrics($metrics)
    {
        $metricsArr = array();
        foreach ($metrics as $metric) {
            $metObj = new \Google_Service_AnalyticsReporting_Metric();
            $metObj->setExpression($metric['value']);
            array_push($metricsArr, $metObj);
        }

        return $metricsArr;
    }


    /**
     * Get configured Dimensions
     *
     * @return array
     */
    protected function getDimensions()
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
    protected function getAnalytics()
    {
        return $this->analytics;
    }
}
