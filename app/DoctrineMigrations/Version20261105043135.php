<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * 商品規格に在庫アラート閾値（stock_alert_threshold）のカラムを追加する.
 */
final class Version20261105043135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'dtb_product_class に在庫アラート閾値（stock_alert_threshold）を追加する';
    }

    public function up(Schema $schema): void
    {
        // 既存の商品規格は NULL（アラート機能を使わない）になる.
        // 0 未満はエンティティのバリデーションで弾くが, DB でも負の値が入らないよう UNSIGNED にしている.
        // UNSIGNED は MySQL 固有の指定のため, PostgreSQL 等で使う場合はこの SQL を見直すこと.
        $this->addSql("ALTER TABLE dtb_product_class ADD stock_alert_threshold INT UNSIGNED DEFAULT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE dtb_product_class DROP COLUMN stock_alert_threshold");
    }
}
