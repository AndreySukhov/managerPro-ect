<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectFile;
use AppBundle\Form\Type\ProjectFileType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProjectFileController extends ProjectController
{
    /**
     * Получение коллекции файлов проекта.
     *
     * @Route("/api/v1/projects/{project}/files", name="app_get_project_files")
     * @Method("GET")
     * @ApiDoc(
     *  section = "ProjectFiles",
     *  output="array<AppBundle\Entity\ProjectFile>",
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param Project $project Проект
     *
     * @return JsonResponse
     */
    public function getProjectFilesAction(Project $project)
    {
        $view = $this->view($project->getFiles(), 200);

        return $this->handleView($view);
    }

    /**
     * Получение файла проекта.
     *
     * @Route("/api/v1/projects/{project}/files/{file}", name="app_get_project_file")
     * @Method("GET")
     * @ParamConverter("file", class="AppBundle:ProjectFile", options={"project" = "project_id"})
     * @ApiDoc(
     *  section = "ProjectFiles",
     *  output="AppBundle\Entity\ProjectFile",
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param ProjectFile $file Файл проекта
     *
     * @return JsonResponse
     */
    public function getProjectFileAction(ProjectFile $file)
    {
        return $this->handleView($this->view($file, 200));
    }

    /**
     * Создание файла проекта.
     *
     * @Route("/api/v1/projects/{project}/files", name="app_post_project_file")
     * @Method("POST")
     * @ApiDoc(
     *  section = "ProjectFiles",
     *  input="AppBundle\Form\Type\ProjectFileType",
     *  output="AppBundle\Entity\ProjectFile",
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param Project $project Проект
     *
     * @return JsonResponse
     */
    public function postProjectFileAction(Request $request, Project $project)
    {
        $file = new ProjectFile();

        $form = $this->createForm(ProjectFileType::class, $file);

        $form->handleRequest($request);
        $file->setProject($project);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($file);
            $manager->flush();

            return $this->handleView($this->view($file, 201));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Изменение файла проекта.
     *
     * @Route("/api/v1/projects/{project}/files/{file}", name="app_put_project_file")
     * @Method("PUT")
     * @ParamConverter("file", class="AppBundle:ProjectFile", options={"project" = "project_id"})
     * @ApiDoc(
     *  section = "ProjectFiles",
     *  input="AppBundle\Form\Type\ProjectFileType",
     *  output="AppBundle\Entity\ProjectFile",
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param ProjectFile $file Файл проекта
     *
     * @return JsonResponse
     */
    public function putProjectFileAction(Request $request, ProjectFile $file)
    {
        $project = $file->getProject();

        $form = $this->createForm(ProjectFileType::class, $file, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);
        $file->setProject($project);

        return $this->handleView($this->view($file, 200));
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($file);
            $manager->flush();

            return $this->handleView($this->view($file, 201));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Удаление файла проекта.
     *
     * @Route("/api/v1/projects/{project}/files/{file}", name="app_delete_project_file")
     * @Method("DELETE")
     * @ParamConverter("file", class="AppBundle:ProjectFile", options={"project" = "project_id"})
     * @ApiDoc(
     *  section = "ProjectFiles",
     *  statusCodes = {
     *      204 = "В слечае успеха"
     *  },
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param ProjectFile $file Файл проекта
     *
     * @return JsonResponse
     */
    public function deleteProjectFileAction(ProjectFile $file)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($file);
        $manager->flush();

        return $this->handleView($this->view(null, 204));
    }
}
