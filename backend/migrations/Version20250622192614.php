<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622192614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, ride_id INT NOT NULL, driver_id INT NOT NULL, passenger_id INT NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD013FC46CC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9B3D7CD013FC46CC ON ride
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride CHANGE driver_role_id driver_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9B3D7CD0545317D1 ON ride (vehicle_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33D302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD ride_ids JSON DEFAULT NULL COMMENT '(DC2Type:json)', ADD driver_preferences JSON DEFAULT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1B80E4867E3C61F9 ON vehicle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle CHANGE owner_id owner_id INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE avis
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0545317D1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9B3D7CD0545317D1 ON ride
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride CHANGE driver_id driver_role_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD013FC46CC FOREIGN KEY (driver_role_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9B3D7CD013FC46CC ON ride (driver_role_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33D302A8A70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP ride_ids, DROP driver_preferences
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle CHANGE owner_id owner_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1B80E4867E3C61F9 ON vehicle (owner_id)
        SQL);
    }
}
