<?php
namespace AdminBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MyCommand extends ContainerAwareCommand
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Hello from my command!");
        //$output->writeln("Username: " . $input->getArgument("username"));

        /**
         * @var EntityManager $container
         */
        $manager = $this->getContainer()->get("doctrine.orm.entity_manager");
        $pages = $manager->getRepository("MyShopBundle:Page")->findAll();

        foreach ($pages as $page) {
            $output->writeln("PageKey='".$page->getPageKey() . "', PageTitle='" . $page->getTitle() . "'");
        }
    }
    public function configure()
    {
        $this->setName("myshop:pages");
        $this->setDescription("Get myshop pages");
        $this->setHelp("For help description");


        //$this->addArgument("username", InputArgument::REQUIRED, 'Username for customer');
    }
}