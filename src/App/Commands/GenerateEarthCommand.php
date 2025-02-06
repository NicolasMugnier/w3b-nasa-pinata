<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\App\Commands;

use Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\CreateEarthGifImage;
use Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\Request\CreateEarthGifImageRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateEarthCommand extends Command
{
    public function __construct(
        private readonly CreateEarthGifImage $createEarthGifImage
    ) {
        parent::__construct('anyvoid:generate-earth-gif');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generates an Earth GIF image.')
            ->addOption(
                'date',
                null,
                InputOption::VALUE_REQUIRED,
                'The date for which to generate the Earth GIF image',
                '2015-06-13'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = $input->getOption('date');
        $request = new CreateEarthGifImageRequest(new \DateTime($date));
        $response = $this->createEarthGifImage->execute($request);

        $output->writeln('<info>'.$response->id.'</info>');

        return 0;
    }
}
