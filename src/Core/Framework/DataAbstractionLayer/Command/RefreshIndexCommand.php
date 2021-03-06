<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\Command;

use Shopware\Core\Framework\Adapter\Console\ShopwareStyle;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RefreshIndexCommand extends Command implements EventSubscriberInterface
{
    use ConsoleProgressTrait;

    protected static $defaultName = 'dal:refresh:index';

    /**
     * @var EntityIndexerRegistry
     */
    private $registry;

    public function __construct(EntityIndexerRegistry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Refreshes the shop indices')
            ->addOption('use-queue', null, InputOption::VALUE_NONE, 'Ignore cache and force generation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new ShopwareStyle($input, $output);

        $this->registry->index($input->getOption('use-queue'));

        return 0;
    }
}
