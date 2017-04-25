<?php

namespace Ersah\GABundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class GAAdminController extends Controller
{
    /**
     * List action.
     */
    public function listAction()
    {
        $request = $this->getRequest();
        //TODO: get these from config as query param
        $nextPageToken = $request->query->get('nextPageToken');
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $sort = $request->query->get('sort');
        $sortBy = $request->query->get('sortBy');

        $search = $request->query->all();
        unset($search['from'], $search['to'], $search['nextPageToken'], $search['sort'], $search['sortBy']);

        $report = $this->get('ersah.list.service.ga')
            ->getReport($nextPageToken, $from, $to, $sort, $sortBy, $search);

        return $this->render($this->admin->getTemplate('list'), array(
            'report' => $report
        ), null);
    }


}