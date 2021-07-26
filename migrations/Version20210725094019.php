<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725094019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE crypto CHANGE value_usd value_usd DOUBLE PRECISION DEFAULT NULL, CHANGE value_eur value_eur DOUBLE PRECISION DEFAULT NULL, CHANGE value_btc value_btc DOUBLE PRECISION DEFAULT NULL, CHANGE value_eth value_eth DOUBLE PRECISION DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE etf CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE historical CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investment CHANGE statut statut TINYINT(1) DEFAULT NULL, CHANGE total_value total_value DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE row CHANGE invest_attach_id invest_attach_id INT DEFAULT NULL, CHANGE devise devise VARCHAR(50) DEFAULT NULL, CHANGE value_usd value_usd DOUBLE PRECISION DEFAULT NULL, CHANGE total_value_usd total_value_usd DOUBLE PRECISION DEFAULT NULL, CHANGE symbol symbol VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE stock CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE account_total_value account_total_value DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE crypto CHANGE value_usd value_usd DOUBLE PRECISION DEFAULT \'NULL\', CHANGE value_eur value_eur DOUBLE PRECISION DEFAULT \'NULL\', CHANGE value_btc value_btc DOUBLE PRECISION DEFAULT \'NULL\', CHANGE value_eth value_eth DOUBLE PRECISION DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE etf CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE historical CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investment CHANGE statut statut TINYINT(1) DEFAULT \'NULL\', CHANGE total_value total_value DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE row CHANGE invest_attach_id invest_attach_id INT DEFAULT NULL, CHANGE devise devise VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE value_usd value_usd DOUBLE PRECISION DEFAULT \'NULL\', CHANGE total_value_usd total_value_usd DOUBLE PRECISION DEFAULT \'NULL\', CHANGE symbol symbol VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE stock CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE lastname lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE account_total_value account_total_value DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}
