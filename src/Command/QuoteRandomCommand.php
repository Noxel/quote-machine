<?php

namespace App\Command;

use App\Entity\Quote;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class QuoteRandomCommand extends Command
{
    protected static $defaultName = 'app:quote-random';

    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $em) {
        parent::__construct($name);

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Show a random quote')
            ->setHelp('This command allows you to show a quote, you can chose between each category')
            ->addOption(
                'category',
                'c',
                InputOption::VALUE_NONE,
                'diplay the category choice'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
          "   ___                     _           _______                        __",
   " .'   `.                  / |_        |_   __ \                      |  ] ",
" /  .-.  \  __   _    .--.`| |-'.---.    | |__) |  ,--.  _ .--.   .--.| |  .--.  _ .--..--.",
" | |   | | [  | | | / .'`\ \ | / /__\\ \   |  __ /  `'_\ :[ `.-. |/ /'`\' |/ .'`\ [ `.-. .-. |",
" \  `-'  \_ | \_/ |,| \__. | |,| \__.,  _| |  \ \_// | |,| | | || \__/  || \__. || | | | | |",
 " `.___.\__|'.__.'_/ '.__.'\__/ '.__.' |____| |___\'-;__[___||__]'.__.;__]'.__.'[___||__||__]"
        ]);

        $io = new SymfonyStyle($input, $output);
        $displayCat = $input->getOption('category');

        $arg = array('none');
        $cats = $this->em->getRepository('App:Category')->findAll();
        foreach ($cats as $cat){
            array_push($arg,$cat->getName());
        }
        $catChoice = 'none';
        if($displayCat) {
            $catChoice = $io->choice('Select a category', $arg, 'none');
        }

        if ($catChoice == 'none' ) {
            $quotes = $this->em->getRepository('App:Quote')->findAll();
        } else {
            $quotes = $this->em->getRepository('App:Category')->findOneBy(['name' => $catChoice])->getQuotes();
        }


        $quote = $quotes[rand(0, sizeof($quotes) -1)];
        $io->success($quote->getQuote());
    }
}
