<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ProjectFileType;
use AppBundle\Entity\ProjectFile;
use AppBundle\Entity\Project;

class ProjectFileController extends ProjectController
{
    /**
     * @Route("/api/v1/projects/{project}/files", name="app_get_project_files")
     * @Method("GET")
     */
    public function getProjectFilesAction(Project $project)
    {
        $view = $this->view($project->getFiles(), 200);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/v1/projects/{project}/files/{file}", name="app_get_project_file")
     * @Method("GET")
     * @ParamConverter("file", class="AppBundle:ProjectFile", options={"project" = "project_id"})
     */
    public function getProjectFileAction(Request $request, ProjectFile $file)
    {
        return $this->handleView($this->view($file, 200));
    }

    /**
     * @Route("/api/v1/projects/{project}/files", name="app_post_project_file")
     * @Method("POST")
     */
    public function postProjectFileAction(Request $request, Project $project)
    {
        $file = new ProjectFile();
        $file->setProject($project);

        $form = $this->get('form.factory')->createNamed('', ProjectFileType::class, $file);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            $view = $this->view($file, 201);

            return $this->handleView($view);
        }

        $view = $this->view($form, 400);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/v1/projects/{project}/files/{file}", name="app_put_project_file")
     * @Method("PUT")
     * @ParamConverter("file", class="AppBundle:ProjectFile", options={"project" = "project_id"})
     */
    public function putProjectFileAction(Request $request, ProjectFile $file)
    {
        $form = $this->get('form.factory')->createNamed('', ProjectFileType::class, $file, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            $view = $this->view($file, 201);

            return $this->handleView($view);
        }

        $view = $this->view($form, 400);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/v1/projects/{project}/files/{file}", name="app_delete_project_file")
     * @Method("DELETE")
     * @ParamConverter("file", class="AppBundle:ProjectFile", options={"project" = "project_id"})
     */
    public function deleteProjectFileAction(ProjectFile $file)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($file);
        $em->flush();

        return $this->handleView($this->view(null, 204));
    }
}
