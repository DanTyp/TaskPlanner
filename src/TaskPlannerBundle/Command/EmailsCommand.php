<?php

namespace TaskPlannerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand; //teraz mamy dostęp do dependency injection -worek na usługi w symfony i mogę użyć getContainer w execute
use TaskPlannerBundle\Entity\Task;

class EmailsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('TaskPlanner:sendReminder');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {


        $delayedTasks = $this->getContainer()->get("doctrine")->getRepository("TaskPlannerBundle:Task")->getDelayedTasks(); //odpowiednik w kontrolerze -> getDoctrine

        foreach ($delayedTasks as $task) { // dla każdego przeterminowanego
            //echo $task->getId();
            
            $emailToSend = $task->getUser()->getEmail();
            $username = $task->getUser()->getUsername();
            $taskName = $task->getName();
            $taskDescription = $task->getDescription();
            $deadline = $task->getDeadline()->format('Y-m-d');
            
            $message = \Swift_Message::newInstance()
                    ->setSubject('Task reminder!')
                    ->setFrom('coderslabworkshop@gmail.com')
                    ->setTo($emailToSend)
                    ->setBody("Hello $username! You have delayed task: $taskName - $taskDescription. Deadline was $deadline.");

            $this->getContainer()->get('mailer')->send($message); //getContainer()->dobieram sie do worka z usługami
            
            //./app/console TaskPlanner:sendReminder -> wysyłanie maili przez konsolę

        }
    }

}
