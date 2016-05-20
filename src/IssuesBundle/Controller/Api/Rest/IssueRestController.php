<?php

namespace IssuesBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Oro\Bundle\SecurityBundle\Annotation\Acl;

/**
 * @RouteResource("issue")
 * @NamePrefix("issues_api_")
 */
class IssueRestController extends RestController
{
    /**
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all issue items",
     *      resource=true
     * )
     *
     * @return Response
     */
    public function getListAction()
    {
        return $this->handleGetListRequest();
    }

    /**
     * @param int $id
     *
     * @ApiDoc(
     *      description="Delete issue",
     *      resource=true
     * )
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * @param string $id
     *
     * @ApiDoc(
     *      description="Get issue by ID",
     *      resource=true
     * )

     * @return Response
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all issue items",
     *      resource=true
     * )

     * @param Request $request
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $page = $request->request->get('page', 1);
        $limit = $request->request->get('limit', self::ITEMS_PER_PAGE);

        return $this->handleGetListRequest($page, $limit);
    }
    
    /**
     * @param int $id Issue id
     *
     * @ApiDoc(
     *      description="Update issue",
     *      resource=true
     * )
     *
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }
    
    /**
     * @ApiDoc(
     *      description="Create new issue",
     *      resource=true
     * )
     *
     * @return Response
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }

    public function getManager()
    {
        return $this->get('issues.issue_manager.api');
    }

    public function getFormHandler()
    {
        return $this->get('issues.form.handler.issue');
    }

    public function getForm()
    {
        return $this->get('issues.form.type.issue_api');
    }
}
