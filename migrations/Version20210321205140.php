<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321205140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonus (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE "character" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, gender VARCHAR(1) NOT NULL, xp INTEGER NOT NULL, stuck INTEGER NOT NULL, tutorial_done BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB034A76ED395 ON "character" (user_id)');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, course_id INTEGER NOT NULL, parent_id INTEGER DEFAULT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C591CC992 ON comment (course_id)');
        $this->addSql('CREATE INDEX IDX_9474526C727ACA70 ON comment (parent_id)');
        $this->addSql('CREATE TABLE course (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, theme_id INTEGER NOT NULL, item_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, picture VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_169E6FB95E237E06 ON course (name)');
        $this->addSql('CREATE INDEX IDX_169E6FB959027487 ON course (theme_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9126F525E ON course (item_id)');
        $this->addSql('CREATE TABLE course_place (course_id INTEGER NOT NULL, place_id INTEGER NOT NULL, position INTEGER NOT NULL, PRIMARY KEY(course_id, place_id))');
        $this->addSql('CREATE INDEX IDX_9DB65425591CC992 ON course_place (course_id)');
        $this->addSql('CREATE INDEX IDX_9DB65425DA6A219 ON course_place (place_id)');
        $this->addSql('CREATE TABLE inventory (player_id INTEGER NOT NULL, item_id INTEGER NOT NULL, quantity INTEGER NOT NULL, quality INTEGER NOT NULL, PRIMARY KEY(player_id, item_id))');
        $this->addSql('CREATE INDEX IDX_B12D4A3699E6F5DF ON inventory (player_id)');
        $this->addSql('CREATE INDEX IDX_B12D4A36126F525E ON inventory (item_id)');
        $this->addSql('CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, picture VARCHAR(255) NOT NULL, price INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E5E237E06 ON item (name)');
        $this->addSql('CREATE INDEX IDX_1F1B251EC54C8C93 ON item (type_id)');
        $this->addSql('CREATE TABLE item_bonuses (item_id INTEGER NOT NULL, bonus_id INTEGER NOT NULL, value INTEGER NOT NULL, PRIMARY KEY(item_id, bonus_id))');
        $this->addSql('CREATE INDEX IDX_8E68E986126F525E ON item_bonuses (item_id)');
        $this->addSql('CREATE INDEX IDX_8E68E98669545666 ON item_bonuses (bonus_id)');
        $this->addSql('CREATE TABLE place (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, address VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_741D53CD5E237E06 ON place (name)');
        $this->addSql('CREATE TABLE theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9775E7085E237E06 ON theme (name)');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8CDE57295E237E06 ON type (name)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, is_player BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TABLE user_courses (in_real_life BOOLEAN NOT NULL, course_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(course_id, user_id, in_real_life))');
        $this->addSql('CREATE INDEX IDX_F1A84446591CC992 ON user_courses (course_id)');
        $this->addSql('CREATE INDEX IDX_F1A84446A76ED395 ON user_courses (user_id)');
        $this->addSql('CREATE TABLE user_places (in_real_life BOOLEAN NOT NULL, place_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(place_id, user_id, in_real_life))');
        $this->addSql('CREATE INDEX IDX_9BFD7768DA6A219 ON user_places (place_id)');
        $this->addSql('CREATE INDEX IDX_9BFD7768A76ED395 ON user_places (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE "character"');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_place');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_bonuses');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_courses');
        $this->addSql('DROP TABLE user_places');
    }
}
