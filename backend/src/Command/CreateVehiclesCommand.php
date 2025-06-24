<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:test-vehicles')]
class CreateVehiclesCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $driver = $this->em->getRepository(User::class)->findOneByRole('ROLE_DRIVER');

        if (!$driver) {
            $output->writeln('❌ Aucun utilisateur avec le rôle ROLE_DRIVER trouvé.');
            return Command::FAILURE;
        }

        $vehicules = [
            ['Toyota', 'Corolla', 'Hybride'],
            ['Peugeot', '208', 'Essence'],
            ['Tesla', 'Model 3', 'Électrique'],
            ['Renault', 'Clio', 'Diesel'],
        ];

        foreach ($vehicules as [$brand, $model, $energy]) {
            $vehicle = new Vehicle();
            $vehicle->setBrand($brand)
                    ->setModel($model)
                    ->setEnergy($energy)
                    ->setOwnerID($driver->getId());

            $this->em->persist($vehicle);
            $output->writeln("🚗 Véhicule $brand $model ($energy) créé.");
        }

        $this->em->flush();
        $output->writeln("✅ Tous les véhicules ont été enregistrés.");

        return Command::SUCCESS;
    }
}
