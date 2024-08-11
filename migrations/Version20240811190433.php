<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811190433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, compagny VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, service VARCHAR(255) DEFAULT NULL, car VARCHAR(255) DEFAULT NULL, departure_adress VARCHAR(255) NOT NULL, arrival_adress VARCHAR(255) NOT NULL, nb_passager INT NOT NULL, nb_bagage INT NOT NULL, num_transport VARCHAR(255) DEFAULT NULL, driver_id INT DEFAULT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation');
    }
}
