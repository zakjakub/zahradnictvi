<?php

namespace AppBundle\Command;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Controller\CustomerController;

class UkazLidiCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:ukazlidi')
            ->setDescription('Vypis zakazniku')
            ->setHelp("Vypise vsechny zakazniky.")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '',
            ' Vypis vsech zakazniku',
            ' =====================',
            '',
        ]);

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $users = $em->getRepository("AppBundle:Customer")->findAll();

        foreach ($users as $user) {
            $output->writeln("  - " . $user->getName());
        }

    }

}
