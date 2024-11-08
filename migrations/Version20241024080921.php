<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024080921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting CHANGE show_bank show_bank TINYINT(1) DEFAULT NULL, CHANGE show_no_tva_text show_no_tva_text TINYINT(1) DEFAULT NULL, CHANGE show_late_payment_text show_late_payment_text TINYINT(1) DEFAULT NULL, CHANGE show_date_operation show_date_operation TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting CHANGE show_bank show_bank TINYINT(1) NOT NULL, CHANGE show_no_tva_text show_no_tva_text TINYINT(1) NOT NULL, CHANGE show_late_payment_text show_late_payment_text TINYINT(1) NOT NULL, CHANGE show_date_operation show_date_operation TINYINT(1) NOT NULL');
    }
}
