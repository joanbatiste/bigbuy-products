<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Controller\ProductController;

class GetProductsCommand extends Command
{
    protected static $defaultName = 'app:get-products';
    protected static $defaultDescription = 'Import products from external files';

    private ProductController $productController;

    public function __construct
    (
        ProductController $productController
    )
    {
        $this->productController = $productController;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fileExtension', InputArgument::REQUIRED, 'Type the extension file to import')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('fileExtension');

        if ($arg1) {
            $io->note(sprintf('You passed an extension: %s', $arg1));
            $this->productController->index($arg1);
        }else{
            $this->productController->index('json');
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
