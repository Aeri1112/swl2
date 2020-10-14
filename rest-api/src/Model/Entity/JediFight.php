<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JediFight Entity
 *
 * @property int $fightid
 * @property string $type
 * @property int $opentime
 * @property string $startin
 * @property int $bet
 * @property int $minstr
 * @property int $maxstr
 * @property int $status
 */
class JediFight extends Entity
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
        'type' => true,
        'opentime' => true,
        'startin' => true,
        'bet' => true,
        'minstr' => true,
        'maxstr' => true,
        'status' => true,
    ];
}
