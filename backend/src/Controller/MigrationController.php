<?php

namespace App\Controller;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\MigratorConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MigrationController extends AbstractController
{
    #[Route('/run-migrations', name: 'run_migrations')]
    public function run(DependencyFactory $migrations): Response
    {
        $migrator = $migrations->getMigrator();
        $planCalculator = $migrations->getMigrationPlanCalculator();
        $aliasResolver = $migrations->getVersionAliasResolver();

        $latestVersion = $aliasResolver->resolveVersionAlias('latest');
        /** @var \Traversable $plan */
        $plan = $planCalculator->getPlanUntilVersion($latestVersion);
        
        if (iterator_count($plan) === 0) {
            return new Response('✅ Aucune migration à appliquer.');
        }

        /** @var \Doctrine\Migrations\Metadata\MigrationPlanList $plan */
        $migrator->migrate($plan, new MigratorConfiguration());

        return new Response('✅ Migrations exécutées avec succès.');
    }
}
