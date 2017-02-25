<?php

namespace TaskPlannerBundle\Controller;

use TaskPlannerBundle\Entity\Commentary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use TaskPlannerBundle\Entity\User;

/**
 * Commentary controller.
 *
 * @Route("commentary")
 */
class CommentaryController extends Controller
{
    /**
     * Lists all commentary entities.
     *
     * @Route("/", name="commentary_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commentaries = $em->getRepository('TaskPlannerBundle:Commentary')->findAll();

        return $this->render('commentary/index.html.twig', array(
            'commentaries' => $commentaries,
        ));
    }
    
    //modyfikuję akcję poniższą dodaję new/{id} (było "/new") i będę z repozytorium zadań wynajdywał to konkretne i przypisywał do komentarza
    //będę musiał do niej przekazywać id zadania
    /**
     * Creates a new commentary entity.
     *
     * @Route("/new/{id}", name="commentary_new", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id)
    {
        $commentary = new Commentary();
        $form = $this->createForm('TaskPlannerBundle\Form\CommentaryType', $commentary);
        $form->handleRequest($request);
        
        $tasksRepo = $this->getDoctrine()->getRepository('TaskPlannerBundle:Task');
        $task = $tasksRepo->find($id);
        
        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();        

        if ($form->isSubmitted() && $form->isValid() && $user instanceof User && $task != null) { //dodaje sprawdzenie czy user jest zalogowany i czy znalazłem w repo zadanie o danym id
            $em = $this->getDoctrine()->getManager();
            
            $commentary->setUser($user);
            $commentary->setTask($task); //teraz w formularzu nie potrzebuję pola task, od razu doda sie komenarz do wybranego zadania
            
            $date = new \DateTime(); //obiekt daty, kótry domyślnie ma czas bieżący, nie potrzebuję w formularzu pola daty
            $commentary->setDate($date);
            
            $em->persist($commentary);
            $em->flush($commentary);

            //return $this->redirectToRoute('commentary_show', array('id' => $commentary->getId()));
            return $this->redirectToRoute('task_show', ['id' => $id]);
        }

        return $this->render('commentary/new.html.twig', array(
            'commentary' => $commentary,
            'form' => $form->createView(),
            'taskId' => $id
        ));
    }

    /**
     * Finds and displays a commentary entity.
     *
     * @Route("/{id}", name="commentary_show")
     * @Method("GET")
     */
    public function showAction(Commentary $commentary)
    {
        $deleteForm = $this->createDeleteForm($commentary);

        return $this->render('commentary/show.html.twig', array(
            'commentary' => $commentary,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing commentary entity.
     *
     * @Route("/{id}/edit", name="commentary_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Commentary $commentary)
    {
        $deleteForm = $this->createDeleteForm($commentary);
        $editForm = $this->createForm('TaskPlannerBundle\Form\CommentaryType', $commentary);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentary_edit', array('id' => $commentary->getId()));
        }

        return $this->render('commentary/edit.html.twig', array(
            'commentary' => $commentary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commentary entity.
     *
     * @Route("/{id}", name="commentary_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Commentary $commentary)
    {
        $form = $this->createDeleteForm($commentary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentary);
            $em->flush($commentary);
        }

        return $this->redirectToRoute('commentary_index');
    }

    /**
     * Creates a form to delete a commentary entity.
     *
     * @param Commentary $commentary The commentary entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commentary $commentary)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentary_delete', array('id' => $commentary->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
