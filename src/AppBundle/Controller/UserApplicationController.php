<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Form\Type\ClientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Client controller.
 *
 * @Route("/user/application")
 * @Security("has_role('ROLE_USER')")
 */
class UserApplicationController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="app_user_application_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $clients = $this->getUser()->getClients();

        return $this->render('app/user/application/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="app_user_application_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->addClient($client);
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->persist($this->getUser());
            $em->flush();

            return $this->redirectToRoute('app_user_application_show', ['id' => $client->getId()]);
        }

        return $this->render('app/user/application/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="app_user_application_show")
     * @Method("GET")
     */
    public function showAction(Client $client)
    {
        if (!$this->getUser()->getClients()->contains($client)) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($client);

        return $this->render('app/user/application/show.html.twig', [
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="app_user_application_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        if (!$this->getUser()->getClients()->contains($client)) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm(ClientType::class, $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_user_application_edit', ['id' => $client->getId()]);
        }

        return $this->render('app/user/application/edit.html.twig', [
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a client entity.
     *
     * @Route("/{id}", name="app_user_application_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Client $client)
    {
        if (!$this->getUser()->getClients()->contains($client)) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->getUser()->removeClient($client);
            $em->remove($client);
            $em->persist($this->getUser());
            $em->flush($client);
        }

        return $this->redirectToRoute('app_user_application_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_user_application_delete', ['id' => $client->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
