<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends Command
{
    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

        /**
         * @var $exportHelper \Kodhub\Reporter\Helper\Export
         */
        $exportHelper = $objectManager->get('Kodhub\Reporter\Helper\Export');

        $name = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);

        $exportFile = $exportHelper->export(1, 2);

        $output->writeln("Generate report file ->  " . $exportFile);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("kodhub:reporter:export");
        $this->setDescription("Export your Report");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
    }
}

