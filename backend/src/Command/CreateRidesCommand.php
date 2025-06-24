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
    description: 'Crée plusieurs trajets de test pour les conducteurs.',
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
        // On récupère un conducteur et un véhicule existants
        $driver = $this->em->getRepository(User::class)->findOneByRole('ROLE_DRIVER');
        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy([]);
    
        if (!$driver || !$vehicle) {
            $output->writeln('❌ Aucun conducteur ou véhicule trouvé.');
            return Command::FAILURE;
        }
    
        $trajets = [
            ['Paris', 'Versailles', 3, 4.0, '+1 day'],
            ['Lyon', 'Villeurbanne', 2, 2.5, '+2 days'],
            ['Marseille', 'Aubagne', 4, 3.0, '+3 days'],
            ['Toulouse', 'Blagnac', 1, 2.0, '+4 days'],
        ];
    
        foreach ($trajets as [$departure, $arrival, $seats, $price, $date]) {
            $ride = new Ride();
            $ride->setDriverID($driver->getId())
                 ->setDeparture($departure)
                 ->setArrival($arrival)
                 ->setAvailableSeats($seats)
                 ->setPrice($price)
                 ->setDate(new \DateTime($date))
                 ->setVehicle($vehicle);
    
            $this->em->persist($ride);
            $output->writeln("✅ Trajet $departure → $arrival créé.");
        }
    
        $this->em->flush();
        $output->writeln("🎉 Tous les trajets courts ont été enregistrés.");
    
        return Command::SUCCESS;
    }    

    
}
