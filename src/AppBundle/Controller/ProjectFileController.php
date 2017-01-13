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
     * Get project files.
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
     * @param Project $project The project
     *
     * @return JsonResponse
     */
    public function getProjectFilesAction(Project $project)
    {
        $view = $this->view($project->getFiles(), 200);

        return $this->handleView($view);
    }

    /**
     * Get project file.
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
     * @param ProjectFile $file The project file
     *
     * @return JsonResponse
     */
    public function getProjectFileAction(Request $request, ProjectFile $file)
    {
        return $this->handleView($this->view($file, 200));
    }

    /**
     * Create project file.
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
     * @param Project $project The project
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            return $this->handleView($this->view($file, 201));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Change project file.
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
     * @param ProjectFile $file The project file
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            return $this->handleView($this->view($file, 201));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Delete project file.
     *
     * @Route("/api/v1/projects/{project}/files/{file}", name="app_delete_project_file")
     * @Method("DELETE")
     * @ParamConverter("file", class="AppBundle:ProjectFile", options={"project" = "project_id"})
     * @ApiDoc(
     *  section = "ProjectFiles",
     *  statusCodes = {
     *      204 = "Returned when successful"
     *  },
     *  tags = {
     *      "in-development"
     *  }
     * )
     *
     * @param ProjectFile $file The project file
     *
     * @return JsonResponse
     */
    public function deleteProjectFileAction(ProjectFile $file)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($file);
        $em->flush();

        return $this->handleView($this->view(null, 204));
    }
}
