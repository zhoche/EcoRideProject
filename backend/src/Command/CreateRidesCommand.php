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

#[AsCommand(
    name: 'create:test-rides',
    description: 'Crée 10 trajets de test avec crédits simulés.',
)]
class CreateRidesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $driver = $this->em->getRepository(User::class)->createQueryBuilder('u')
        ->where('u.roles LIKE :role')
        ->setParameter('role', '%ROLE_DRIVER%')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy([]);

        if (!$driver || !$vehicle) {
            $output->writeln('❌ Aucun conducteur ou véhicule trouvé.');
            return Command::FAILURE;
        }

        $villes = [
            ['Paris', 'Lille'],
            ['Lyon', 'Grenoble'],
            ['Bordeaux', 'Toulouse'],
            ['Nantes', 'Rennes'],
            ['Nice', 'Marseille'],
            ['Amiens', 'Rouen'],
            ['Dijon', 'Strasbourg'],
            ['Tours', 'Orléans'],
            ['Avignon', 'Montpellier'],
            ['Clermont-Ferrand', 'Saint-Étienne'],
        ];

        for ($i = 0; $i < 10; $i++) {
            [$departure, $arrival] = $villes[$i];
            $initialSeats = rand(3, 5);
            $bookedSeats = rand(1, $initialSeats - 1);
            $availableSeats = $initialSeats - $bookedSeats;

            $ride = new Ride();
            $ride->setDriver($driver)
                ->setDeparture($departure)
                ->setArrival($arrival)
                ->setAvailableSeats($availableSeats)
                ->setInitialSeats($initialSeats)
                ->setPrice(rand(2, 6))
                ->setDate((new \DateTime())->modify("+$i day"))
                ->setVehicle($vehicle);

            $this->em->persist($ride);

            $output->writeln("✅ $departure → $arrival | places: $initialSeats ($availableSeats restantes)");
        }

        $this->em->flush();

        $output->writeln("🎉 10 trajets de test créés avec crédits simulés !");
        return Command::SUCCESS;
    }
}
