<?php
namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCleanupCommand extends Command
{
    private $userService;

    protected static $defaultName = 'app:user:cleanup';

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Deletes user with expired activation_token')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);
        $count = $this->userService->deleteALlUsersWithDeprecatedToken();
        $io->success(sprintf('Deleted "%d" account with expired activation_token', $count));
        return 0;
    }
}