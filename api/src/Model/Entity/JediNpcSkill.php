<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * JediNpcSkill Entity
 *
 * @property int $userid
 * @property int $xp
 * @property int $level
 * @property int $rsp
 * @property int $rfp
 * @property int $side
 * @property int $cns
 * @property int $agi
 * @property int $spi
 * @property int $itl
 * @property int $tac
 * @property int $dex
 * @property int $lsa
 * @property int $lsd
 * @property int $fspee
 * @property int $fjump
 * @property int $fpull
 * @property int $fpush
 * @property int $fseei
 * @property int $fsabe
 * @property int $fpers
 * @property int $fproj
 * @property int $fblin
 * @property int $fconf
 * @property int $fheal
 * @property int $ftheal
 * @property int $fprot
 * @property int $fabso
 * @property int $frvtl
 * @property int $fthrow
 * @property int $frage
 * @property int $fgrip
 * @property int $fdrain
 * @property int $fthun
 * @property int $fchai
 * @property int $fdest
 * @property int $fdead
 * @property int $ftnrg
 */
class JediNpcSkill extends Entity
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
        'xp' => true,
        'level' => true,
        'rsp' => true,
        'rfp' => true,
        'side' => true,
        'cns' => true,
        'agi' => true,
        'spi' => true,
        'itl' => true,
        'tac' => true,
        'dex' => true,
        'lsa' => true,
        'lsd' => true,
        'fspee' => true,
        'fjump' => true,
        'fpull' => true,
        'fpush' => true,
        'fseei' => true,
        'fsabe' => true,
        'fpers' => true,
        'fproj' => true,
        'fblin' => true,
        'fconf' => true,
        'fheal' => true,
        'ftheal' => true,
        'fprot' => true,
        'fabso' => true,
        'frvtl' => true,
        'fthrow' => true,
        'frage' => true,
        'fgrip' => true,
        'fdrain' => true,
        'fthun' => true,
        'fchai' => true,
        'fdest' => true,
        'fdead' => true,
        'ftnrg' => true,
    ];
}
