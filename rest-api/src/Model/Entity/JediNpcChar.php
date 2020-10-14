<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JediNpcChar Entity
 *
 * @property int $userid
 * @property string $username
 * @property string $sex
 * @property int $health
 * @property int $mana
 * @property int $base
 * @property int $item_hand
 * @property int $fpreferences
 * @property int $masterid
 * @property string $alliance
 * @property int $fpreferences2
 * @property string $status
 * @property int $lastaccess
 * @property string $species
 * @property int $age
 * @property string $size
 * @property string $homeworld
 * @property int $energy
 * @property int $rank
 * @property int $actionid
 * @property int $targetid
 * @property int $targettime
 * @property int $lastfightid
 * @property string $location
 * @property int $pic
 * @property string $location2
 * @property int $tmpcast
 * @property int $cash
 */
class JediNpcChar extends Entity
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
        'userid' => true,
        'username' => true,
        'sex' => true,
        'health' => true,
        'mana' => true,
        'base' => true,
        'item_hand' => true,
        'fpreferences' => true,
        'masterid' => true,
        'alliance' => true,
        'fpreferences2' => true,
        'status' => true,
        'lastaccess' => true,
        'species' => true,
        'age' => true,
        'size' => true,
        'homeworld' => true,
        'energy' => true,
        'rank' => true,
        'actionid' => true,
        'targetid' => true,
        'targettime' => true,
        'lastfightid' => true,
        'location' => true,
        'pic' => true,
        'location2' => true,
        'tmpcast' => true,
        'cash' => true,
    ];
}
