<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210202140711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE frage (id INT AUTO_INCREMENT NOT NULL, kurs_id INT NOT NULL, crdate DATETIME NOT NULL, tstamp DATETIME NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_4F17D4472CAAFBEC (kurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kurs (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, crdate DATETIME NOT NULL, tstamp DATETIME NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4B5C3E57A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, kurs_id INT NOT NULL, crdate DATETIME NOT NULL, tstamp DATETIME NOT NULL, code VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_D044D5D42CAAFBEC (kurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_member (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, crdate DATETIME NOT NULL, tstamp DATETIME NOT NULL, INDEX IDX_1F8FF021613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_member_frage (id INT AUTO_INCREMENT NOT NULL, frage_id INT NOT NULL, sessionmember_id INT NOT NULL, crdate DATETIME NOT NULL, tstamp DATETIME NOT NULL, content LONGTEXT NOT NULL, rating INT NOT NULL, ratingcount INT NOT NULL, UNIQUE INDEX UNIQ_E3ABFC357513DBE6 (frage_id), UNIQUE INDEX UNIQ_E3ABFC35D0FBF6F6 (sessionmember_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, crdate DATETIME NOT NULL, tstamp DATETIME NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frage ADD CONSTRAINT FK_4F17D4472CAAFBEC FOREIGN KEY (kurs_id) REFERENCES kurs (id)');
        $this->addSql('ALTER TABLE kurs ADD CONSTRAINT FK_4B5C3E57A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D42CAAFBEC FOREIGN KEY (kurs_id) REFERENCES kurs (id)');
        $this->addSql('ALTER TABLE session_member ADD CONSTRAINT FK_1F8FF021613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE session_member_frage ADD CONSTRAINT FK_E3ABFC357513DBE6 FOREIGN KEY (frage_id) REFERENCES frage (id)');
        $this->addSql('ALTER TABLE session_member_frage ADD CONSTRAINT FK_E3ABFC35D0FBF6F6 FOREIGN KEY (sessionmember_id) REFERENCES session_member (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session_member_frage DROP FOREIGN KEY FK_E3ABFC357513DBE6');
        $this->addSql('ALTER TABLE frage DROP FOREIGN KEY FK_4F17D4472CAAFBEC');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D42CAAFBEC');
        $this->addSql('ALTER TABLE session_member DROP FOREIGN KEY FK_1F8FF021613FECDF');
        $this->addSql('ALTER TABLE session_member_frage DROP FOREIGN KEY FK_E3ABFC35D0FBF6F6');
        $this->addSql('ALTER TABLE kurs DROP FOREIGN KEY FK_4B5C3E57A76ED395');
        $this->addSql('DROP TABLE frage');
        $this->addSql('DROP TABLE kurs');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE session_member');
        $this->addSql('DROP TABLE session_member_frage');
        $this->addSql('DROP TABLE user');
    }
}
