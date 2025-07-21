<?php
// src/Command/CreatePassengerRidesCommand.php
namespace App\Command;

use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:passenger-rides')]
class CreatePassengerRidesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private VehicleRepository   $vehicleRepo
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1) Récupère passenger
        $passenger = $this->em->getRepository(User::class)
            ->findOneBy(['email' => 'passenger@example.com']);
        if (!$passenger) {
            $output->writeln('<error>passenger@example.com introuvable.</error>');
            return Command::FAILURE;
        }

        // 2) Récupère tous les users et filtre les drivers
        $allUsers   = $this->em->getRepository(User::class)->findAll();
        $drivers    = array_filter(
            $allUsers,
            fn(User $u) => in_array('ROLE_DRIVER', $u->getRoles(), true)
        );
        if (empty($drivers)) {
            $output->writeln('<error>Aucun conducteur trouvé.</error>');
            return Command::FAILURE;
        }

        // 3) Récupère les véhicules
        $vehicles = $this->vehicleRepo->findAll();
        if (empty($vehicles)) {
            $output->writeln('<error>Aucun véhicule trouvé.</error>');
            return Command::FAILURE;
        }

        // 4) Données fixes pour 3 trajets
        $dates  = [
            (new \DateTimeImmutable('+1 day')),
            (new \DateTimeImmutable('+2 days')),
            (new \DateTimeImmutable('+3 days')),
        ];
        $routes = [
            ['Paris','Lille'],
            ['Lyon','Grenoble'],
            ['Auch','Toulouse'],
        ];

        foreach ($routes as $i => [$dep, $arr]) {
            $date   = $dates[$i];
            $driver = $drivers[array_rand($drivers)];

            // Cherche un véhicule du driver
            $vehicle = current(array_filter(
                $vehicles,
                fn(Vehicle $v) => $v->getOwner() === $driver
            ));
            if (!$vehicle) {
                $output->writeln("<comment>Pas de véhicule pour {$driver->getPseudo()}, trajet ignoré.</comment>");
                continue;
            }

            $ride = (new Ride())
                ->setDriver($driver)
                ->setDeparture($dep)
                ->setArrival($arr)
                ->setDate((new \DateTime())->modify("+{$i} days"))
                ->setInitialSeats(4)
                ->setAvailableSeats(2)
                ->setPrice(5.00)
                ->setVehicle($vehicle)
                ->setExtras('Test for passenger@example.com')
                ->setStatus('en cours');

            // 5) Ajoute passenger@example.com sur chaque trajet
            $ride->addPassenger($passenger);

            $this->em->persist($ride);
            $output->writeln("✅ Ride {$dep}→{$arr} le {$date->format('Y-m-d')} créé.");
        }

        $this->em->flush();
        $output->writeln('<info>🎉 3 trajets créés pour passenger@example.com</info>');
        return Command::SUCCESS;
    }
}
