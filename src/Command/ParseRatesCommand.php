<?php

namespace App\Command;

use App\Entity\Rate;
use App\Service\Parser\Parser;
use Clue\React\Buzz\Browser;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseRatesCommand extends Command
{
    protected static $defaultName = 'app:parse-rates';

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Parse rates.')
            ->setHelp('This command parsing currency rates from setting sources.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->container->get('doctrine');
        $loop = \React\EventLoop\Factory::create();
        $parser = new Parser($doctrine->getManager(), new Browser($loop));
        $rateRepository = $doctrine->getRepository(Rate::class);
        $parser->eachParse($rateRepository);
        $loop->run();
    }
}
