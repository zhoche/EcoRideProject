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
        // Chaque entrée commence par l'email du propriétaire
        // [ownerEmail, brand, model, plateNumber, isElectric]
        $vehicles = [
            ['driver1@example.com', 'Toyota',  'Corolla',  'ABC-123-EF', false],
            ['driver2@example.com', 'Peugeot', '208',      'GH-456-IJ', false],
            ['driver3@example.com', 'Tesla',   'Model 3',  'EV-789-KL', true],
            ['driver4@example.com', 'Renault', 'Clio',     'MN-012-OP', false],
            ['driver5@example.com', 'Volkswagen',  'ID 3', 'VW-333-ID3',  true],
        ];

        foreach ($vehicles as [$ownerEmail, $brand, $model, $plate, $isElectric]) {
            // 1) Récupération du propriétaire par email
            $owner = $this->em
                ->getRepository(User::class)
                ->findOneBy(['email' => $ownerEmail]);

            if (!$owner) {
                $output->writeln("<error>Utilisateur “{$ownerEmail}” introuvable, véhicule ignoré.</error>");
                continue;
            }

            // 2) Création et affectation
            $v = new Vehicle();
            $v->setOwner($owner)
              ->setBrand($brand)
              ->setModel($model)
              ->setPlateNumber($plate)
              ->setIsElectric($isElectric);

            $this->em->persist($v);

            $output->writeln(sprintf(
                '🚗 Véhicule créé : %s %s — immatriculation %s — propriétaire : %s — électrique : %s',
                $brand,
                $model,
                $plate,
                $ownerEmail,
                $isElectric ? 'oui' : 'non'
            ));
        }

        $this->em->flush();
        $output->writeln('<info>✅ Tous les véhicules valides ont été enregistrés.</info>');

        return Command::SUCCESS;
    }
}
