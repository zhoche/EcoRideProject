<?php

namespace App\Command;

use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:test-rides')]
class CreateRidesCommand extends Command
{
    public function __construct(private EntityManagerInterface $em) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allUsers = $this->em->getRepository(User::class)->findAll();

        $drivers = array_filter($allUsers, fn(User $user) => in_array('ROLE_DRIVER', $user->getRoles()));
        

        $vehicles = $this->em->getRepository(Vehicle::class)->findAll();

        if (count($drivers) === 0 || count($vehicles) === 0) {
            $output->writeln('Aucun conducteur ou véhicule disponible.');
            return Command::FAILURE;
        }

        $villes = [
            ['Paris', 'Lille'], ['Lyon', 'Grenoble'], ['Bordeaux', 'Toulouse'],
            ['Nantes', 'Rennes'], ['Nice', 'Marseille'], ['Amiens', 'Rouen'],
            ['Dijon', 'Strasbourg'], ['Tours', 'Orléans'], ['Avignon', 'Montpellier'],
            ['Clermont-Ferrand', 'Saint-Étienne']
        ];

        for ($i = 0; $i < 10; $i++) {
            [$departure, $arrival] = $villes[$i];
            $driver = $drivers[array_rand($drivers)];
            $vehicle = $vehicles[array_rand($vehicles)];
            if ($vehicle->getOwner() !== $driver) continue;

            $initialSeats = rand(3, 5);
            $availableSeats = rand(1, $initialSeats);

            $ride = new Ride();
            $ride->setDriver($driver)
                ->setDeparture($departure)
                ->setArrival($arrival)
                ->setDate((new \DateTime())->modify("+{$i} days"))
                ->setAvailableSeats($availableSeats)
                ->setInitialSeats($initialSeats)
                ->setPrice(rand(3, 6))
                ->setVehicle($vehicle)
                ->setExtras("Trajet calme avec pauses");

            $this->em->persist($ride);
            $output->writeln("Trajet: $departure -> $arrival avec $availableSeats/$initialSeats places");
        }

        $this->em->flush();
        $output->writeln("10 trajets de test créés avec succès.");
        return Command::SUCCESS;
    }
}
