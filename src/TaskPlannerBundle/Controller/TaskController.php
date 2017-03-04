<?php

namespace TaskPlannerBundle\Controller;

use TaskPlannerBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Form\Extension\Core\Type\FileType; //niepotrzebne
//use Symfony\Component\Form\Extension\Core\Type\DateType; //niepotrzebne
//use Symfony\Component\Validator\Constraints\DateTime; //niepotrzebne
use TaskPlannerBundle\Entity\User;

//use TaskPlannerBundle\Entity\Commentary;

/**
 * Task controller.
 *
 * @Route("task")
 */
class TaskController extends Controller {

    private function verifyAccess(Task $task) {

        $tasksUser = $task->getUser();

        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();

        if ($user !== $tasksUser) {

            throw $this->createAccessDeniedException();
        }

        //jeżeli są to 2 różne osoby to rzuci mi exception i wykonywanie akcji zostanie przerwane
        //jeżeli to ta sama osoba to nic się nie zwróci i wywoła się dalsza część akcji
    }

    /**
     * Lists all task entities.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        //$tasks = $em->getRepository('TaskPlannerBundle:Task')->findAll();

        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();

        if ($user instanceof User) {

            $tasks = $em->getRepository('TaskPlannerBundle:Task')->getPersonalTasks($user->getId());
            //$tasksForToday = $em->getRepository('TaskPlannerBundle:Task')->getTasksForToday($user->getId());
        } else {

            $tasks = [];
        }

        return $this->render('task/index.html.twig', array(
                    'tasks' => $tasks,
                    'user' => $user,
                        //'tasksForToday' => $tasksForToday
        ));
    }

    /**
     * Creates a new task entity.
     *
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $task = new Task();
        $form = $this->createForm('TaskPlannerBundle\Form\TaskType', $task);
        $form->handleRequest($request);

        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();

        if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {
            $em = $this->getDoctrine()->getManager();

            $task->setUser($user); //dodać dodawanie daty

            $date = new \DateTime();
            $status = 0;

            $file = $task->getAttach(); //pobieram z formularza plik
            
            $fileName = $user->getId().'_'.mt_rand(1, 9999).date('Y-m-d').".".$file->guessExtension(); //podaję jego nazwę

            $file->move($this->getParameter('uploadsDirection'), $fileName); //podaję gdzie zapisać plik. parametr 'uploadsDirection' to ten, który ustawiłem sobie 


            $task->setAttach($fileName); //przypisuje załącznik do zadania

            $task->setDate($date);
            $task->setStatus($status);


            $em->persist($task);
            $em->flush($task);

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        return $this->render('task/new.html.twig', array(
                    'task' => $task,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $task) { // można dodać atrybut $id a następnie...
        $this->verifyAccess($task);

        $deleteForm = $this->createDeleteForm($task);

        $commentariesRepository = $this->getDoctrine()->getRepository("TaskPlannerBundle:Commentary");
        $commentariesToTask = $commentariesRepository->findCommentariesByTaskId($task->getId()); //...tutaj wstawić to $id, ale działa też bez tego

        return $this->render('task/show.html.twig', array(
                    'task' => $task,
                    'delete_form' => $deleteForm->createView(),
                    'commentaries' => $commentariesToTask
        ));
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task, $id) {

        $tasksRepository = $this->getDoctrine()->getRepository("TaskPlannerBundle:Task");
        $taskToEdit = $tasksRepository->find($id);

        $commentariesRepository = $this->getDoctrine()->getRepository("TaskPlannerBundle:Commentary");
        $commentariesToTask = $commentariesRepository->findCommentariesByTaskId($taskToEdit->getId()); //...tutaj wstawić to $id, ale działa też bez tego

        $this->verifyAccess($taskToEdit);

        if ($taskToEdit->getStatus() != 1) { //dodatkowe zabezpieczenie jeżeli task.status = 1 (completed) wówczas nie moge go edytować, przekieruje mnie do
            //widoku danego zadania
            $deleteForm = $this->createDeleteForm($task);
            $editForm = $this->createForm('TaskPlannerBundle\Form\TaskType', $task);
            $editForm->handleRequest($request);



            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                //return $this->redirectToRoute('task_edit', array('id' => $task->getId()));
                return $this->redirectToRoute('task_show', array('id' => $task->getId()));
            }

            return $this->render('task/edit.html.twig', array(
                        'task' => $task,
                        'edit_form' => $editForm->createView(),
                        'delete_form' => $deleteForm->createView(),
            ));
        } else {
            //return $this->redirectToRoute('task_show', ['id' => $id]);
            $deleteForm = $this->createDeleteForm($task);

            return $this->render('task/show.html.twig', array(
                        'task' => $task,
                        'delete_form' => $deleteForm->createView(),
                        'commentaries' => $commentariesToTask,
                        'message' => 'Impossible to edit completed task. Change task\'s status in order to edit.'
            ));
        }
    }

//    PRZED ZMIANAMI:
//        public function editAction(Request $request, Task $task) {
//        $deleteForm = $this->createDeleteForm($task);
//        $editForm = $this->createForm('TaskPlannerBundle\Form\TaskType', $task);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('task_edit', array('id' => $task->getId()));
//        }
//
//        return $this->render('task/edit.html.twig', array(
//                    'task' => $task,
//                    'edit_form' => $editForm->createView(),
//                    'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Deletes a task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $task) {

        $this->verifyAccess($task);

        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush($task);
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * @Route("/{id}/changeStatus", requirements={"id"="\d+"})
     */
    public function changeStatusAction($id) {

        $tasksRepository = $this->getDoctrine()->getRepository("TaskPlannerBundle:Task");
        $taskToModify = $tasksRepository->find($id);

        $this->verifyAccess($taskToModify);

        if ($taskToModify != null) {
            $status = $taskToModify->getStatus();

            if ($status == 0) {
                $newStatus = 1;
            } else {
                $newStatus = 0;
            }

            $em = $this->getDoctrine()->getManager();
            $taskToModify->setStatus($newStatus);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * @Route("/{id}/tasksForToday", requirements={"id"="\d+"})
     */
    public function tasksForTodayAction($id) {

        $em = $this->getDoctrine()->getManager();

        //$tasks = $em->getRepository('TaskPlannerBundle:Task')->findAll();

        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();

        if ($user instanceof User) {

            //$tasks = $em->getRepository('TaskPlannerBundle:Task')->getPersonalTasks($user->getId());
            $tasksForToday = $em->getRepository('TaskPlannerBundle:Task')->getTasksForToday($user->getId());
        } else {

            $tasks = [];
        }

        return $this->render('task/index.html.twig', array(
                    'tasks' => $tasksForToday,
                    'user' => $user,
                        //'tasksForToday' => $tasksForToday
        ));
    }

    /**
     * @Route("/{id}/myDelayedTasks", requirements={"id"="\d+"})
     */
    public function delayedTasksForConcreteUserAction($id) {

        $em = $this->getDoctrine()->getManager();



        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();

        if ($user instanceof User) {

            //$tasks = $em->getRepository('TaskPlannerBundle:Task')->getPersonalTasks($user->getId());
            $delayedTasks = $em->getRepository('TaskPlannerBundle:Task')->getDelayedTasksForConcreteUser($user->getId());
        } else {

            $tasks = [];
        }

        return $this->render('task/index.html.twig', array(
                    'tasks' => $delayedTasks,
                    'user' => $user,
                        //'tasksForToday' => $tasksForToday
        ));
    }

}
