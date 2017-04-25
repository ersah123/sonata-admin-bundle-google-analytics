<?php
/**
 * Created by PhpStorm.
 * User: ersah
 * Date: 4/24/17
 * Time: 3:03 PM
 */

namespace Ersah\GABundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ApiController extends FOSRestController
{
    /**
     * GA api endpoint
     *
     * @ApiDoc(
     *   resource = true,
     *   description="Returns Google Analytics list report",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @QueryParam(name="nextPageToken", default="null", requirements="\d+", nullable=true, description="offset")
     * @QueryParam(name="from", default="30daysAgo", nullable=true, description="Date From")
     * @QueryParam(name="to", default="today", description="Date to")
     * @QueryParam(name="sort", default="DESCENDING", nullable=true, description="Sorting type")
     * @QueryParam(name="sortBy", default="ga:totalEvents", nullable=true, description="Sort by dimension or metric")
     * @QueryParam(name="search", description="ga:dimention=test", nullable=true, requirements="ga:dimention or ga:metric = 'string'")
     *
     * @View()
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return Mixed
     */
    public function getListReportAction(ParamFetcherInterface $paramFetcher)
    {
        $nextPageToken = $paramFetcher->get('nextPageToken');
        $from = $paramFetcher->get('from');
        $to = $paramFetcher->get('to');
        $sort = $paramFetcher->get('sort');
        $sortBy = $paramFetcher->get('sortBy');
        $search = $paramFetcher->get('search');

        return $this->get('ersah.list.service.ga')
            ->getReport($nextPageToken, $from, $to, $sort, $sortBy, $search);
    }


    /**
     * @ApiDoc(
     *   resource = true,
     *   description="Returns Google Analytics main report",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @View()
     *
     * @return mixed
     */
    public function getMainReportAction()
    {
        return $this->get('ersah.main.service.ga')
            ->getMainReport();
    }
}