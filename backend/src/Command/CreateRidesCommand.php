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
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allUsers = $this->em->getRepository(User::class)->findAll();
        $drivers    = array_filter($allUsers, fn(User $u) => in_array('ROLE_DRIVER', $u->getRoles()));
        $passengers = array_filter($allUsers, fn(User $u) => in_array('ROLE_USER',   $u->getRoles()));

        $vehicles = $this->em->getRepository(Vehicle::class)->findAll();

        if (empty($drivers) || empty($vehicles)) {
            $output->writeln('<error>Aucun conducteur ou véhicule disponible.</error>');
            return Command::FAILURE;
        }

        $routes = [
            ['Paris','Lille'], ['Lyon','Grenoble'], ['Bordeaux','Toulouse'],
            ['Nantes','Rennes'], ['Nice','Marseille'], ['Amiens','Rouen'],
            ['Dijon','Strasbourg'], ['Tours','Orléans'], ['Avignon','Montpellier'],
            ['Clermont-Ferrand','Saint-Étienne'],
        ];

        for ($i = 0; $i < 10; $i++) {
            [$departure, $arrival] = $routes[$i];
            $driver  = $drivers[array_rand($drivers)];
            $vehicle = $vehicles[array_rand($vehicles)];

            if ($vehicle->getOwner() !== $driver) {
                $vehicle = current(array_filter(
                    $vehicles,
                    fn(Vehicle $v) => $v->getOwner() === $driver
                )) ?: null;
                if (!$vehicle) {
                    $output->writeln("<comment>Aucun véhicule de {$driver->getPseudo()} disponible, trajet ignoré.</comment>");
                    continue;
                }
            }

            $initialSeats   = rand(3, 5);
            $availableSeats = rand(1, $initialSeats);

            $ride = (new Ride())
                ->setDriver($driver)
                ->setDeparture($departure)
                ->setArrival($arrival)
                ->setDate((new \DateTime())->modify("+{$i} days"))
                ->setInitialSeats($initialSeats)
                ->setAvailableSeats($availableSeats)
                ->setPrice((float) rand(3, 6))
                ->setVehicle($vehicle)
                ->setExtras('')
                ->setStatus('en cours');

            shuffle($passengers);
            foreach (array_slice($passengers, 0, min(2, $availableSeats)) as $p) {
                if ($p !== $driver) {
                    $ride->addPassenger($p);
                }
            }

            $this->em->persist($ride);
            $output->writeln(sprintf(
                "🚗 Trajet #%d : %s → %s | Places : %d/%d | Véhicule : %s %s | Passagers : %d",
                $i + 1,
                $departure,
                $arrival,
                $availableSeats,
                $initialSeats,
                $vehicle->getBrand(),
                $vehicle->getModel(),
                count($ride->getPassengers())
            ));
        }

        $this->em->flush();
        $output->writeln('<info>✅ Les 10 trajets de test ont été créés avec succès.</info>');

        return Command::SUCCESS;
    }
}
