<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JediItemsMisc Entity
 *
 * @property int $itemid
 * @property int $uniqueid
 * @property int $droptime
 * @property int $ownerid
 * @property string $position
 * @property string $name
 * @property string $img
 * @property int $sizex
 * @property int $sizey
 * @property int $price
 * @property int $qlvl
 * @property string $uni
 * @property int $crafted
 * @property string $nodrop
 * @property int $weight
 * @property int $reql
 * @property int $reqs
 * @property string $consumable
 * @property int $mindmg
 * @property int $maxdmg
 * @property string $stat1
 * @property string $stat2
 * @property string $stat3
 * @property string $stat4
 * @property string $stat5
 */
class JediItemsMisc extends Entity
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
        'uniqueid' => true,
        'droptime' => true,
        'ownerid' => true,
        'position' => true,
        'name' => true,
        'img' => true,
        'sizex' => true,
        'sizey' => true,
        'price' => true,
        'qlvl' => true,
        'uni' => true,
        'crafted' => true,
        'nodrop' => true,
        'weight' => true,
        'reql' => true,
        'reqs' => true,
        'consumable' => true,
        'mindmg' => true,
        'maxdmg' => true,
        'stat1' => true,
        'stat2' => true,
        'stat3' => true,
        'stat4' => true,
        'stat5' => true,
    ];
}
