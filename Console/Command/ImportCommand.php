<?php
declare(strict_types=1);

namespace LeoVirgilio\Infobase\Console\Command;

use LeoVirgilio\Infobase\Model\Import;
use LeoVirgilio\Infobase\Model\ProfileFactory;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;


class ImportCommand extends Command
{

    /**
     * Profile Name
     */
    const PROFILE_NAME = 'profile-name';

    /**
     * File path
     */
    const FILE_PATH = 'file-path';

    /**
     * @var ProfileFactory
     */
    private ProfileFactory $_profileFactory;

    /**
     * @var Import
     */
    private Import $_import;

    /**
     * @param ProfileFactory $profileFactory
     * @param Import $import
     */
    public function __construct(
        ProfileFactory $profileFactory,
        Import         $import
    )
    {
        $this->_profileFactory = $profileFactory;
        $this->_import = $import;

        parent::__construct();
    }

    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $options = [
            new InputArgument(self::PROFILE_NAME, InputArgument::REQUIRED, 'eg: sample-csv or sample-json', null),
            new InputArgument(self::FILE_PATH, InputArgument::REQUIRED, 'eg: ~/customers.json', null),
        ];

        $this->setName('customer:import')
            ->setDescription('ImportCommand data from files to customer data storage')
            ->setDefinition($options);

        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        try {
            $profile = $this->_profileFactory->create($input->getArgument(self::PROFILE_NAME));
            $profile->setFilePath($input->getArgument(self::FILE_PATH));
            $data = $profile->read();

            $section = $output->section();
            $table = new Table($section);
            $table->render();

            foreach ($data as $item) {
                $item['result'] = $this->_import->process($item) ? '<fg=green>successfully</>' : '<fg=red>failed</>';
                $table->appendRow($item);
            }
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

}
