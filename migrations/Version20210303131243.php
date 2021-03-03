<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303131243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, gender VARCHAR(1) NOT NULL, xp INT NOT NULL, stuck INT NOT NULL, tutorial_done TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_937AB034A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, course_id INT NOT NULL, parent_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C591CC992 (course_id), INDEX IDX_9474526C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (player_id INT NOT NULL, item_id INT NOT NULL, quantity INT NOT NULL, quality INT NOT NULL, INDEX IDX_B12D4A3699E6F5DF (player_id), INDEX IDX_B12D4A36126F525E (item_id), PRIMARY KEY(player_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_bonuses (item_id INT NOT NULL, bonus_id INT NOT NULL, value INT NOT NULL, INDEX IDX_8E68E986126F525E (item_id), INDEX IDX_8E68E98669545666 (bonus_id), PRIMARY KEY(item_id, bonus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_courses (in_real_life TINYINT(1) NOT NULL, course_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F1A84446591CC992 (course_id), INDEX IDX_F1A84446A76ED395 (user_id), PRIMARY KEY(course_id, user_id, in_real_life)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_places (in_real_life TINYINT(1) NOT NULL, place_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9BFD7768DA6A219 (place_id), INDEX IDX_9BFD7768A76ED395 (user_id), PRIMARY KEY(place_id, user_id, in_real_life)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3699E6F5DF FOREIGN KEY (player_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_bonuses ADD CONSTRAINT FK_8E68E986126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_bonuses ADD CONSTRAINT FK_8E68E98669545666 FOREIGN KEY (bonus_id) REFERENCES bonus (id)');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A84446591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE user_courses ADD CONSTRAINT FK_F1A84446A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_places ADD CONSTRAINT FK_9BFD7768DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE user_places ADD CONSTRAINT FK_9BFD7768A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_bonuses DROP FOREIGN KEY FK_8E68E98669545666');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3699E6F5DF');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C727ACA70');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE item_bonuses');
        $this->addSql('DROP TABLE user_courses');
        $this->addSql('DROP TABLE user_places');
    }
}
