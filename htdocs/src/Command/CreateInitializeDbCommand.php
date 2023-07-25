<?php

namespace App\Command;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:initialize-db')]
class CreateInitializeDbCommand extends Command
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $coach = $this->roleRepository->find(1);
        $client = $this->roleRepository->find(2);
        if (isset($coach) && isset($client)) {
            if ('coach' == $coach->getName() && 'client' == $client->getName()) {
                return Command::SUCCESS;
            }
        }

        $roles = $this->roleRepository->findAll();

        foreach ($roles as $role) {
            $this->em->remove($role);
            $this->em->flush();
        }

        $coachRole = new Role();
        $coachRole->setId(1);
        $coachRole->setName('coach');
        $this->em->persist($coachRole);
        $this->em->flush();

        $clientRole = new Role();
        $clientRole->setId(2);
        $clientRole->setName('client');
        $this->em->persist($clientRole);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
