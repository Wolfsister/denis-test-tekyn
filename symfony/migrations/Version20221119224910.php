<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221119224910 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excluded_product (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE excluded_product_user (excluded_product_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_209C2A43A41CB89 (excluded_product_id), INDEX IDX_209C2A4A76ED395 (user_id), PRIMARY KEY(excluded_product_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE excluded_product_user ADD CONSTRAINT FK_209C2A43A41CB89 FOREIGN KEY (excluded_product_id) REFERENCES excluded_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE excluded_product_user ADD CONSTRAINT FK_209C2A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excluded_product_user DROP FOREIGN KEY FK_209C2A43A41CB89');
        $this->addSql('ALTER TABLE excluded_product_user DROP FOREIGN KEY FK_209C2A4A76ED395');
        $this->addSql('DROP TABLE excluded_product');
        $this->addSql('DROP TABLE excluded_product_user');
    }
}
