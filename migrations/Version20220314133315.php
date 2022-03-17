<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314133315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34578D5E91');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34A76ED395');
        $this->addSql('DROP INDEX IDX_595AAE34578D5E91 ON grade');
        $this->addSql('DROP INDEX IDX_595AAE34A76ED395 ON grade');
        $this->addSql('ALTER TABLE grade ADD user INT NOT NULL, ADD exam INT NOT NULL, DROP user_id, DROP exam_id');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE348D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE3438BBA6C6 FOREIGN KEY (exam) REFERENCES exam (id)');
        $this->addSql('CREATE INDEX IDX_595AAE348D93D649 ON grade (user)');
        $this->addSql('CREATE INDEX IDX_595AAE3438BBA6C6 ON grade (exam)');
        $this->addSql('CREATE UNIQUE INDEX UK_grade_exam_user ON grade (grade, user, exam)');
        $this->addSql('CREATE UNIQUE INDEX UK_name_start_end ON lesson (name, start, end)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE exam CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE subject subject LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE348D93D649');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE3438BBA6C6');
        $this->addSql('DROP INDEX IDX_595AAE348D93D649 ON grade');
        $this->addSql('DROP INDEX IDX_595AAE3438BBA6C6 ON grade');
        $this->addSql('DROP INDEX UK_grade_exam_user ON grade');
        $this->addSql('ALTER TABLE grade ADD user_id INT NOT NULL, ADD exam_id INT NOT NULL, DROP user, DROP exam');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE34A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_595AAE34578D5E91 ON grade (exam_id)');
        $this->addSql('CREATE INDEX IDX_595AAE34A76ED395 ON grade (user_id)');
        $this->addSql('DROP INDEX UK_name_start_end ON lesson');
        $this->addSql('ALTER TABLE lesson CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
