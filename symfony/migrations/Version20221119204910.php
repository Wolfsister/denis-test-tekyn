<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221119204910 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_product (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_product_user (favorite_product_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EF6DF6875B4F84A7 (favorite_product_id), INDEX IDX_EF6DF687A76ED395 (user_id), PRIMARY KEY(favorite_product_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_product_user ADD CONSTRAINT FK_EF6DF6875B4F84A7 FOREIGN KEY (favorite_product_id) REFERENCES favorite_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_product_user ADD CONSTRAINT FK_EF6DF687A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_product_user DROP FOREIGN KEY FK_EF6DF6875B4F84A7');
        $this->addSql('ALTER TABLE favorite_product_user DROP FOREIGN KEY FK_EF6DF687A76ED395');
        $this->addSql('DROP TABLE favorite_product');
        $this->addSql('DROP TABLE favorite_product_user');
    }
}
