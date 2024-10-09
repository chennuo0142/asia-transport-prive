<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241009045205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, ref VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, user INT NOT NULL, company JSON NOT NULL COMMENT \'(DC2Type:json)\', customer JSON NOT NULL COMMENT \'(DC2Type:json)\', product JSON NOT NULL COMMENT \'(DC2Type:json)\', total JSON NOT NULL COMMENT \'(DC2Type:json)\', bank JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', creat_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', op_date DATETIME DEFAULT NULL, show_tva_text TINYINT(1) DEFAULT NULL, invoice_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE invoice');
    }
}
