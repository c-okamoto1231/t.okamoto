<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 */

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Eccube\Annotation as Eccube;

/**
 * ProductClass
 *
 * @Eccube\EntityExtension("Eccube\Entity\ProductClass")
 */
trait ProductClass
{
    /**
     * @var int|null
     *
     * @ORM\Column(
     *     name="stock_alert_threshold",
     *     type="integer",
     *     options={"unsigned":true},
     *     nullable=true
     * )
     *
     * @Assert\PositiveOrZero
     *
     * @Eccube\FormAppend(
     *     auto_render=true,
     *     type="Symfony\Component\Form\Extension\Core\Type\IntegerType",
     *     options={
     *         "required"=false,
     *         "label"="admin.product.stock_alert_threshold"
     *     }
     * )
     */
    private $stock_alert_threshold;

    /**
     * Set stock_alert_threshold
     *
     * @param int|null $stock_alert_threshold
     *
     * @return ProductClass
     */
    public function setStockAlertThreshold(?int $stock_alert_threshold)
    {
        $this->stock_alert_threshold = $stock_alert_threshold;

        return $this;
    }

    /**
     * Get stock_alert_threshold
     *
     * @return int|null
     */
    public function getStockAlertThreshold(): ?int
    {
        return $this->stock_alert_threshold;
    }

    /**
     * 在庫僅少（在庫アラートの対象）かどうか.
     *
     * 次のすべてを満たすときに true を返す.
     * - 閾値が設定されている（null はアラート機能を使わない）
     * - 在庫数無制限ではない（在庫数と比較できないため）
     * - 在庫が 1 以上ある（在庫 0 は品切れ表示を優先するため対象外）
     * - 在庫数が閾値以下
     *
     * 管理画面の「在庫僅少のみ」の絞り込み（ProductRepository）も同じ仕様で組んでいる.
     *
     * @return bool
     */
    public function isStockAlert(): bool
    {
        $threshold = $this->getStockAlertThreshold();
        $stock = $this->getStock();

        if ($threshold === null || $this->isStockUnlimited() || $stock === null) {
            return false;
        }

        return $stock > 0 && $stock <= $threshold;
    }
}
