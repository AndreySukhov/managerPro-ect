<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as REST;
use AppBundle\Form\Type\ProjectType;
use AppBundle\Entity\Project;

class ProjectController extends FOSRestController
{
    /**
     * @Route("/api/v1/projects", name="app_get_projects")
     * @Method("GET")
     * @REST\QueryParam(map=true, name="filters", requirements=".+", description="List of ids")
     * @REST\QueryParam(map=true, name="order_by", requirements=".+", description="List of ids")
     * @REST\QueryParam(name="limit", requirements="\d+", default="12", description="Sort direction")
     * @REST\QueryParam(name="offset", requirements="\d+", default="0", description="Sort direction")
     */
    public function getProjectsAction(ParamFetcher $paramFetcher)
    {
        $filters = is_array($filters = $paramFetcher->get('filters')) ? $filters : [];
        $orderBy = is_array($orderBy = $paramFetcher->get('order_by')) ? $orderBy : [];
        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        $entities = $this->getDoctrine()->getRepository(Project::class)->findBy($filters, $orderBy, $limit, $offset);

        return $this->handleView($this->view($entities, 200));
    }

    /**
     * @Route("/api/v1/projects/{project}", name="app_get_project")
     * @Method("GET")
     */
    public function getProjectAction(Project $project)
    {
        return $this->handleView($this->view($project, 200));
    }

    /**
     * @Route("/api/v1/projects", name="app_post_project")
     * @Method("POST")
     */
    public function postProjectAction(Request $request)
    {
        $project = new Project();
        $form = $this->get('form.factory')->createNamed('', ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->handleView($this->view($project, 201));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * @Route("/api/v1/projects/{project}", name="app_put_project")
     * @Method("PUT")
     */
    public function putProjectAction(Request $request, Project $project)
    {
        $form = $this->get('form.factory')->createNamed('', ProjectType::class, $project, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->handleView($this->view($project, 200));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * @Route("/api/v1/projects/{project}", name="app_delete_project")
     * @Method("DELETE")
     */
    public function deleteProjectAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->handleView($this->view(null, 204));
    }
}
