<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JediAlliance Entity
 *
 * @property int $id
 * @property string $name
 * @property string $short
 * @property int $pic
 * @property string $description
 * @property int $leader
 * @property int $coleader
 * @property int $alignment
 * @property int $cash
 */
class JediAlliance extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'short' => true,
        'pic' => true,
        'description' => true,
        'leader' => true,
        'coleader' => true,
        'alignment' => true,
        'cash' => true,
    ];
}
