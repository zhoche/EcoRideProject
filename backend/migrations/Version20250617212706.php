<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617212706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ride (id INT AUTO_INCREMENT NOT NULL, driver_role_id INT NOT NULL, departure VARCHAR(255) NOT NULL, arrival VARCHAR(255) NOT NULL, date DATETIME NOT NULL, available_seats INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_9B3D7CD013FC46CC (driver_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD013FC46CC FOREIGN KEY (driver_role_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD013FC46CC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ride
        SQL);
    }
}
