<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Form\Type\ProjectType;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends FOSRestController
{
    /**
     * Получение коллекции проектов.
     *
     * @Route("/api/v1/projects", name="app_get_projects")
     * @Method("GET")
     * @REST\QueryParam(map=true, name="filters", requirements=".+", description="Фильты. Например: filters[name]=foo")
     * @REST\QueryParam(map=true, name="order_by", requirements=".+", description="Сортировка. Например: order_by[name]=acs&order_by[id]=desc")
     * @REST\QueryParam(name="limit", requirements="\d+", default="12", description="Лимит")
     * @REST\QueryParam(name="offset", requirements="\d+", default="0", description="Смещение")
     * @ApiDoc(
     *  resource=true,
     *  section="Project",
     *  output="array<AppBundle\Entity\Project>",
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @return JsonResponse
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
     * Получение проекта.
     *
     * @Route("/api/v1/projects/{project}", name="app_get_project")
     * @Method("GET")
     * @ApiDoc(
     *  section = "Project",
     *  output="AppBundle\Entity\Project",
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param Project $project Проект
     *
     * @return JsonResponse
     */
    public function getProjectAction(Project $project)
    {
        return $this->handleView($this->view($project, 200));
    }

    /**
     * Создание проекта.
     *
     * @Route("/api/v1/projects", name="app_post_project")
     * @Method("POST")
     * @ApiDoc(
     *  section = "Project",
     *  input="AppBundle\Form\Type\ProjectType",
     *  statusCodes = {
     *      201 = "При успешном создании",
     *      400 = "При ошибке"
     *  },
     *  responseMap = {
     *      201 = {
     *          "class" = Project::class,
     *      },
     *      400 = {
     *          "class" = ProjectType::class,
     *          "form_errors" = true,
     *          "name" = ""
     *     },
     *  },
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @return JsonResponse
     */
    public function postProjectAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($project);
            $manager->flush();

            return $this->handleView($this->view($project, 201));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Изменение проекта.
     *
     * @Route("/api/v1/projects/{project}", name="app_put_project")
     * @Method("PUT")
     * @ApiDoc(
     *  section = "Project",
     *  input="AppBundle\Form\Type\ProjectType",
     *  output="AppBundle\Entity\Project",
     *  statusCodes = {
     *      200 = "В случае успеха",
     *      400 = "В случае ощибки"
     *  },
     *  responseMap = {
     *      200 = {
     *          "class" = Project::class,
     *      },
     *      400 = {
     *          "class" = ProjectType::class,
     *          "form_errors" = true,
     *          "name" = ""
     *      },
     *  },
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param Project $project Проект
     *
     * @return JsonResponse
     */
    public function putProjectAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectType::class, $project, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($project);
            $manager->flush();

            return $this->handleView($this->view($project, 200));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Удаление проекта.
     *
     * @Route("/api/v1/projects/{project}", name="app_delete_project")
     * @Method("DELETE")
     * @ApiDoc(
     *  section = "Project",
     *  statusCodes = {
     *      204 = "В случае успеха"
     *  },
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param Project $project Проект
     *
     * @return JsonResponse
     */
    public function deleteProjectAction(Project $project)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($project);
        $manager->flush();

        return $this->handleView($this->view(null, 204));
    }
}
