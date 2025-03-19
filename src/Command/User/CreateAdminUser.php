<?php

declare(strict_types=1);

namespace App\Command\User;

use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Service\Security\PasswordGenerator;
use App\Service\User\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'user:create-admin',
    description: 'Create new user with admin role.'
)]
final class CreateAdminUser extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordGenerator $passwordGenerator,
        private readonly UserService $userService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED, 'Login for new user')
            ->addOption('force-password', null, InputOption::VALUE_REQUIRED, 'Set this password instead of generating a random one.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'User creator',
            '===================',
            '',
        ]);

        $userLogin = strval($input->getArgument('login'));

        $user = $this->userRepository->getUserByLogin($userLogin);

        if ($user) {
            $output->writeln("Error: A user with the login {$user->getLogin()} is already registered in the system. Please use a different email address.");

            return Command::FAILURE;
        }

        $user = new User();

        if ($input->getOption('force-password')) {
            $userPassword = strval($input->getOption('force-password'));
        } else {
            $userPassword = $this->passwordGenerator->generatePassword();
        }

        $hashPassword = $this->userService->hashPassword($user, $userPassword);

        $user
            ->setRoles(["ROLE_ADMIN"])
            ->setLogin($userLogin)
            ->setPassword($hashPassword);

        try {
            $this->userService->save($user);

            $output->writeln("User with login {$user->getLogin()} has been successfully created. Password: $userPassword");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: Failed to create user. Reason: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
