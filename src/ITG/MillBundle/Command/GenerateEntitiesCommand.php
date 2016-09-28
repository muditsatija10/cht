<?php

namespace ITG\MillBundle\Command;

use ITG\MillBundle\Component\MillBundle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateEntitiesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mill:entities:generate')
            ->setAliases(['mill:generate:entities', 'generate:mill:entities'])
            ->setDescription('Generate entities for ITG bundles')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'Bundle to generate entities for')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundleTxt = $input->getArgument('bundle');
        $bundle = null;

        if($bundleTxt)
        {
            if(!$bundle = $this->getContainer()->get('kernel')->getBundle($bundleTxt))
            {
                $output->writeln("Bundle $bundleTxt not found");
                return;
            }
        }

        while(!$bundle)
        {
            $helper = $this->getHelper('question');
            $question = new Question('Bundle to generate entities for ');

            $bundles = $this->getContainer()->get('kernel')->getBundles();
            $availableBundles = [];
            foreach($bundles as $b)
            {
                if($b instanceof MillBundle)
                {
                    $availableBundles[] = $b->getName();
                }
            }

            $question->setAutocompleterValues($availableBundles);

            $choice = $helper->ask($input, $output, $question);

            $bundle = $this->getContainer()->get('kernel')->getBundle($choice);
        }

        // Move files
        $bundlePath = $bundle->getPath() . '/Resources/AppBundle';
        $appBundlePath = $this->getContainer()->get('kernel')->getBundle('AppBundle')->getPath();
        $root = str_replace('/app', '', $this->getContainer()->getParameter('kernel.root_dir'));
        //$relPath = str_replace($root, '', $path);

        $this->checkDir($bundlePath, $bundlePath, $appBundlePath, $output);
    }

    private function checkDir($directory, $root, $appBundlePath, $o)
    {
        $files = scandir($directory);
        $dir = str_replace($root, '', $directory);

        foreach ($files as $file)
        {
            if(is_dir("$directory/$file") && $file !== '.' && $file !== '..')
            {
                $this->checkDir("$directory/$file", $root, $appBundlePath, $o);
            }
            else if(is_file("$directory/$file"))
            {
                if(!file_exists($dest = $appBundlePath . $dir . '/' . explode('.', $file)[0] . '.php'))
                {
                    $o->writeln($dest);
                    if(!file_exists($appBundlePath . $dir))
                    {
                        mkdir($appBundlePath . $dir, 0777, true);
                    }
                    copy("$directory/$file", $dest);
                }
            }
        }
    }

}