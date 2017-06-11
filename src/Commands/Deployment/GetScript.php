<?php

namespace Sven\ForgeCLI\Commands\Deployment;

use Sven\ForgeCLI\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetScript extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('site:get-deploy-script')
            ->addArgument('server', InputArgument::REQUIRED, 'The id of the server the site is on.')
            ->addArgument('site', InputArgument::REQUIRED, 'The id of the site to get the deployment script of.')
            ->setDescription('Output the deployment script used for the given site.');
    }

    /**
     * {@inheritdoc}
     */
    public function perform(InputInterface $input, OutputInterface $output)
    {
        $script = $this->forge->siteDeploymentScript(
            $input->getArgument('server'), $input->getArgument('site')
        );

        $output->writeln($script);
    }
}