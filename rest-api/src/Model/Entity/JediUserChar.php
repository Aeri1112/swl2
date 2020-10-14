<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;

/**
 * JediUserChar Entity
 *
 * @property int $userid
 * @property string $username
 * @property string $alliance
 * @property int $fpreferences2
 * @property string $status
 * @property int $lastaccess
 * @property string $sex
 * @property string $species
 * @property int $age
 * @property string $size
 * @property string $homeworld
 * @property int $base
 * @property int $health
 * @property int $mana
 * @property int $energy
 * @property int $rank
 * @property int $actionid
 * @property int $targetid
 * @property int $targettime
 * @property string $lastfightid
 * @property string $location
 * @property int $masterid
 * @property int $pic
 * @property string $location2
 * @property int $tmpcast
 * @property int $cash
 * @property int $item_hand
 * @property int $fpreferences
 * @property int $item_finger1
 * @property int $item_finger2
 */
class JediUserChar extends Entity
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
        'alliance' => true,
        'fpreferences2' => true,
        'status' => true,
        'lastaccess' => true,
        'sex' => true,
        'species' => true,
        'age' => true,
        'size' => true,
        'homeworld' => true,
        'base' => true,
        'health' => true,
        'mana' => true,
        'energy' => true,
        'rank' => true,
        'actionid' => true,
        'targetid' => true,
        'targettime' => true,
        'lastfightid' => true,
        'location' => true,
        'masterid' => true,
        'pic' => true,
        'location2' => true,
        'tmpcast' => true,
        'cash' => true,
        'item_hand' => true,
        'fpreferences' => true,
        'item_finger1' => true,
        'item_finger2' => true,
    ];
	
	protected function _getOnlineStatus()
    {
		
		$this->Accounts = TableRegistry::get('Accounts');
		$user = $this->Accounts->get($this->userid);
		//Get diff
		$current_time = strtotime(date("Y-m-d H:i:s")); // CURRENT TIME
		$last_visit = strtotime($user->last_activity->i18nFormat("yyyy-MM-dd HH:mm:ss")); // LAST VISITED TIME
		$time_period = floor(round(abs($current_time - $last_visit)/60,2)); 
		
		if ($time_period <= 8)
		{
			$online_offline_status = 1; // Means User is ONLINE
		}
		else
		{
			$online_offline_status = 0; // Means User is OFFLINE
		}
        return $online_offline_status;
		
    }
}
