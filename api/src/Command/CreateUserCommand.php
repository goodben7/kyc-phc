<?php

namespace App\Command;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'phc:create-user',
    description: 'Create new user',
)]
class CreateUserCommand extends Command
{
    public function __construct(private UserManager $um)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('phone', InputArgument::REQUIRED, 'user unique identifier (phone)')
            ->addArgument('password', InputArgument::REQUIRED, 'user password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $phone = $input->getArgument('phone');
        $password = $input->getArgument('password');

        
        try {

            $u = new User();
            $u->setPhone($phone);
            $u->setRoles([User::ROLE_SUPER_ADMIN]);
            $u->setPlainPassword($password);

            $this->um->create($u);

            $io->success('user successfully created with login '. $u->getUsername());

            return Command::SUCCESS;
        }
        catch(\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}

