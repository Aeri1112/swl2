<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class TreasureComponent extends Component
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->JediTreasures = TableRegistry::get('JediTreasures');
        $this->JediItemsJewelry = TableRegistry::get('JediItemsJewelry');
        $this->JediItemsWeapons = TableRegistry::get('JediItemsWeapons');
        $this->JediItemsMisc = TableRegistry::get('JediItemsMisc');
        $this->JediUserStatistics = TableRegistry::get('JediUserStatistics');
    }

    public function loot($lootitem, $userid, $type2, $type_of_creation = "fight", $choosen_price = 99)
    {
        $items = "";
        $mindmg = "";
        $maxdmg = "";
        $minlvl = "";
		$minskill = 0;
        $stat2calc = false;
        $stat3calc = false;
        $stat4calc = false;
        $stat5calc = false;
        $stat2chance = null;
        $stat3chance = null;
        $stat4chance = null;
        $stat5chance = null;
        $stat1 = "";
        $stat2 = "";
        $stat3 = "";
        $stat4 = "";
        $stat5 = "";

        if ($lootitem == "rat")
        {
            $mlvl = 3;
            $qlvl2 = rand($mlvl - 1, $mlvl +1 );
            $parttype = "rat";
        }
        elseif ($lootitem == "rats")
        {
            $mlvl = 6;
            $qlvl2 = rand($mlvl - 3, $mlvl +3 );
            $parttype = "rat";
        }
		elseif ($lootitem == "giant-rat")
		{
			$mlvl = 9;
            $qlvl2 = rand($mlvl - 3, $mlvl + 3);
            $parttype = "rat";
		}
		elseif ($lootitem == "droid")
		{
			$mlvl = 9;
            $qlvl2 = rand($mlvl - 1, $mlvl + 2);
            $parttype = "rat";
		}
		elseif ($lootitem == "reek")
		{
			$mlvl = 11;
            $qlvl2 = rand($mlvl - 2, $mlvl + 3);
            $parttype = "rat";
		}
        elseif ($lootitem == "ranc4") 
        {
            $mlvl = 15;
            $qlvl2 = rand($mlvl - 4, $mlvl +4 );
            $parttype = "ranc";
        }
        elseif ($lootitem == "ranc5")
        {
            $mlvl = 24;
            $qlvl2 = rand($mlvl - 4, $mlvl +4 );
            $parttype = "ranc";
        }
        elseif($lootitem == "ranc6")
        {
            $mlvl = 33;
            $qlvl2 = rand($mlvl - 4, $mlvl +4 );
            $parttype = "ranc";
        }
        elseif($lootitem == "ranc7") 
        {
            $mlvl = 45;
            $qlvl2 = rand($mlvl - 5, $mlvl +5 );
            $parttype = "ranc";
        }
        else
        {
            $mlvl = 2;
            $qlvl2 = rand($mlvl - 1, $mlvl +1 );
        }

        $wpntype = rand(0,1);
		
		//1 zu 100 chance auf vibro bei droid oder reek
		if($lootitem == "droid" || $lootitem == "reek") $number = rand(1,100);
		if(isset($number) && $number == 1 && $lootitem == "droid") $wpntype = 2;
		if(isset($number) && $number <= 10 && $lootitem == "reek") $wpntype = 2;
		
		if($type_of_creation == "price")
		{
			if($choosen_price == 99)
			{
				$wpntype = rand(0,3);
			}
			elseif($choosen_price == 0)
			{
				$wpntype = 0;
			}
			elseif($choosen_price == 1)
			{
				$wpntype = 1;
			}
			elseif($choosen_price == 2)
			{
				$wpntype = 2;
			}
			elseif($choosen_price == 3)
			{
				$wpntype = 3;
			}
		}
        //Ring
        if($wpntype == 0)
        {
            $type = "rings";
			
            $ringname = rand(0,3);
            if($type_of_creation == "price") $ringname = rand(1,4);
			if($lootitem == "droid") $ringname = rand(1,3);
			if($lootitem == "reek") $ringname = rand(2,4);
				
            if($ringname == 0)
            {
                $name = "Onion Ring";
                $img = "onion1";
                $qlvl = rand(1, 5);
                $qlvl=$qlvl+$qlvl2;
                $weight = rand(2, 11);
                $price = rand(1, 15) * $qlvl;
                $stat2chance = 8;
            }
            elseif($ringname == 1)
            {
                $name = "Rusty Ring";
                $r = rand(1, 2); $img = "rusty".$r;
                $qlvl = rand(1, 3);
                $qlvl=$qlvl+$qlvl2;
                $weight = rand(4, 22);
                $price = rand(1, 25) * $qlvl;
                $stat2chance = 15;
                $stat3chance = 8;
            }
            elseif($ringname == 2)
            {
                $name = "Silver Ring";
                $img = "silver1";
                $qlvl = rand(3, 20);
                $qlvl=$qlvl+$qlvl2;
                $weight = rand(3, 27);
                $price = rand(30, 60) * $qlvl;
                $stat2chance = 27;
                $stat3chance = 19;
                $stat4chance = 5;
            }
            elseif($ringname == 3)
            {
                $name = "Gold Ring";
                $img = "gold1";
                $qlvl = rand(8, 30);
                $qlvl=$qlvl+$qlvl2;
                $weight = rand(10, 15);
                $price = rand(80, 150) * $qlvl;
                $stat2chance = 40;
                $stat3chance = 23;
                $stat4chance = 10;
                $stat5chance = 5;
            }
			elseif($ringname == 4)
			{
				$name = "Platinum Ring";
				$img = "platin1";
				$qlvl = rand(17, 38);
				$qlvl = $qlvl + $qlvl2;
				$weight = rand(17, 35);
				$price = rand(150, 200) * $qlvl;
				$stat2chance = 50;
                $stat3chance = 30;
                $stat4chance = 20;
                $stat5chance = 10;
			}
            
            $minlvl = round(($mlvl + rand($qlvl*0.77,$qlvl*1.11)));

            if ($mlvl >= 10)
            {
                $q2 = 2.3;
                $stat1 = $this->addmagic($qlvl,$q2);
                $stat2calc = rand(0, 100);   // stat3-5 oben bonus vars hier adden
                if ($stat2calc < $stat2chance)
                {
                    $stat3calc = rand(0, 100);   // stat3-5 oben bonus vars hier adden
                }
            }
			elseif($mlvl == 9)
			{
				$q2 = 1.5;
                $stat1 = $this->addmagic($qlvl,$q2);
                $stat2calc = rand(0, 100);   // stat3-5 oben bonus vars hier adden
                if ($stat2calc < $stat2chance)
                {
                    $stat3calc = rand(0, 100);   // stat3-5 oben bonus vars hier adden
                }
			}
            else
            {
                $q2 = 0;
                $stat1 = $this->addmagic($qlvl,$q2);
                $stat2calc = rand(0, 1000)	;   //f�r rattenringe
            }

            if ($stat2calc < $stat2chance) 
                { $stat2 = $this->addmagic($qlvl,$q2); $price = $price + rand(150,1000); }
            if ($stat3calc != false && $stat3calc < $stat3chance) 
                { $stat3 = $this->addmagic($qlvl,$q2); $price = $price + rand(250,3000); }
            if ($stat4calc != false && $stat4calc < $stat4chance) 
                { $stat4 = $this->addmagic($qlvl,$q2); $price = $price + rand(350,5000); }
            if ($stat5calc != false && $stat5calc < $stat5chance) 
                { $stat5 = $this->addmagic($qlvl,$q2); $price = $price + rand(450,7000); }

            if($type2 != "raid")
            {
                //Insert in DB-regular
                $item_db = $this->JediItemsJewelry->newEntity();
                $item_db->ownerid = $userid;
                $item_db->position = "inv";
                $item_db->sizex = 16;
                $item_db->sizey = 16;
                $item_db->reql = $minlvl;
                $item_db->reqs = 0;
                $item_db->name = $name;
                $item_db->img = $img;
                $item_db->qlvl = $qlvl;
                $item_db->weight = $weight;
                $item_db->price = $price;
                $item_db->crafed = 0;
                $item_db->mindmg = 0;
                $item_db->maxdmg = 0;
                $item_db->stat1 = $stat1;
                $item_db->stat2 = $stat2;
                $item_db->stat3 = $stat3;
                $item_db->stat4 = $stat4;
                $item_db->stat5 = $stat5;
                $this->JediItemsJewelry->save($item_db);
                $item_db->type = "rings";
            }
            elseif($type2 == "raid")
            {
                $item_db = $this->JediTreasures->newEntity();
                $item_db->ownerid = $userid;
                $item_db->position = "inv";
                $item_db->type = "rings";
                $item_db->sizex = 16;
                $item_db->sizey = 16;
                $item_db->reql = $minlvl;
                $item_db->reqs = 0;
                $item_db->name = $name;
                $item_db->img = $img;
                $item_db->qlvl = $qlvl;
                $item_db->weight = $weight;
                $item_db->price = $price;
                $item_db->crafed = 0;
                $item_db->mindmg = 0;
                $item_db->maxdmg = 0;
                $item_db->stat1 = $stat1;
                $item_db->stat2 = $stat2;
                $item_db->stat3 = $stat3;
                $item_db->stat4 = $stat4;
                $item_db->stat5 = $stat5;
                $this->JediTreasures->save($item_db);                
            }
        }
        elseif($wpntype == 1)
        {
            $type = "weapons";
            // Weapon item generation
            $th = rand(0, 3);
			if($type_of_creation == "price") $th = rand(2,4);
			if($lootitem == "giant-rat" OR $lootitem == "droid" OR $lootitem == "reek") $th = rand(2,4);
            if ($th == 0)
            {
                $name = "Wood Staff";
                $img = "wood1";
                $qlvl = rand(1, 3);
				if ($qlvl2 > 0) $qlvl = round($qlvl + ($qlvl2/2));
                $weight = rand(600, 900);
                $price = rand(10, 17) * $qlvl;
                $stat1chance = rand(0, 25); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50,1000); }
                $mindmg = round(rand($qlvl * 1, $qlvl * 1.05));
                $maxdmg = round(rand($qlvl * 2, $qlvl * 2.25));
				if ($maxdmg > 22) $maxdmg = 22;
				if ($mindmg > $maxdmg) $mindmg = $maxdmg - 13;
				if ($mindmg <= 0 ) $mindmg = 1;
            }
            elseif ($th == 1)
            {
                $name = "Rusty Iron Staff";
                $img = "rusty1";
                $qlvl = rand(1, 3);
				if ($qlvl2 > 0) $qlvl = round($qlvl + ($qlvl2/2));
                $weight = rand(3000, 5200);
                $price = rand(12, 23) * $qlvl;
                $stat1chance = rand(0, 20); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50,1000); }
                $mindmg = round(rand($qlvl * 1, $qlvl * 1.15));
                $maxdmg = round(rand($qlvl * 3, $qlvl * 3.25));
				if ($maxdmg > 33) $maxdmg = 33;
				if ($mindmg > $maxdmg) $mindmg = $maxdmg - 15;
				if ($mindmg <= 0 ) $mindmg = 1;
            }
            elseif ($th == 2) 
            {
                $name = "Mahogany Training Staff";
                $img = "wood2";
                $qlvl = rand(2, 6);
				if ($qlvl2 > 0) $qlvl = round($qlvl + ($qlvl2/2));
                $weight = rand(250, 700);
                $price = rand(15, 25) * $qlvl;
                $stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50,1000); }
                $mindmg = round(rand($qlvl * 1, $qlvl * 1.25));
                $maxdmg = round(rand($qlvl * 2.5, $qlvl * 3.35));
				if ($maxdmg > 44) $maxdmg = 44;
				if ($mindmg > $maxdmg) $mindmg = $maxdmg - 9;
				if ($mindmg <= 0 ) $mindmg = 1;
            }
            elseif($th == 3) 
            {
                $name = "Stainless Steel Staff";
                $img = "stainless1";
                $qlvl = rand(4, 12);
				if ($qlvl2 > 0) $qlvl = round($qlvl + ($qlvl2/2));
                $weight = rand(2000, 4500);
                $price = rand(29, 45) * $qlvl;
                $stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50,1000); }
                $mindmg = round(rand($qlvl * 1, $qlvl * 1.5));
                $maxdmg = round(rand($qlvl * 2, $qlvl * 2.25));
				if ($maxdmg > 50) $maxdmg = 50;
				if ($mindmg > $maxdmg) $mindmg = $maxdmg - 11;
				if ($mindmg <= 0 ) $mindmg = 1;
            }
			elseif($th == 4)
			{
				$name = "Advanced Fight Staff";
				$img = "fight1";
				$qlvl = rand(13, 30);
				$weight = rand(2000, 4500);
				$price = rand(41, 55) * $qlvl;
				$stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50, 1000); }
				$mindmg = round(rand($qlvl * 1, $qlvl * 1.5));
				$maxdmg = round(rand($qlvl * 2, $qlvl * 2.25));
			}

            if($qlvl <= 15)
            {
                $minlvl = round(rand($qlvl * 0.78, $qlvl * 1.15));
            }
            else
            {
                $minlvl = round(rand($qlvl * 0.88, $qlvl * 1.35));
            }       
        }
		elseif($wpntype == 2)
		{
			$type = "weapons";
			$rnd = rand(0, 3);
			if ($rnd == 0) 
			{
				$name = "Vibroblade";
				$img = "vibro4";
				$qlvl = rand(23, 31);
				$weight = rand(900, 1500);
				$price = (rand(30, 44) * $qlvl)*3;
				$stat1chance = rand(0, 25); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50, 1000); }
				$mindmg = round(rand($qlvl * 1, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.5, $qlvl * 1.6));
			}
			elseif ($rnd == 1) 
			{
				$name = "Vibro Pike";
				$img = "vibro5";
				$qlvl = rand(29, 37);
				$weight = rand(3000, 5200);
				$price = (rand(42, 63) * $qlvl)*3;
				$stat1chance = rand(0, 20); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50, 1000); }
			    $mindmg = round(rand($qlvl * 1, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.5, $qlvl * 1.6));
			}
			elseif ($rnd == 2) 
			{
				$name = "Vibroknuckler";
				$img = "vibro2";
				$qlvl = rand(34, 48);
				$weight = rand(2500, 3700);
				$price = (rand(49, 67) * $qlvl)*3;
				$stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50, 1000); }
				$stat2chance = rand(0, 10); if ($stat1chance == 5) { $stat2 = $this->addmagic($qlvl); $price = $price + rand(100, 1000); }
				$mindmg = round(rand($qlvl * 1, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.5, $qlvl * 1.6));
			}
			else
			{
				$name = "Double Bladed Vibro";
				$img = "vibro1";
				$qlvl = rand(45, 60);
				$weight = rand(3800, 6500);
				$price = (rand(55, 77) * $qlvl)*3;
				$stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(50, 1000); }
				$stat2chance = rand(0, 10); if ($stat1chance == 5) { $stat2 = $this->addmagic($qlvl); $price = $price + rand(100, 1000); }
				$mindmg = round(rand($qlvl * 1, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.5, $qlvl * 1.6));
			}
			$minlvl = round(rand($qlvl * 1.29, $qlvl * 1.66));
			$minskill = round(rand($qlvl * 1.29, $qlvl * 1.66));
		}
		elseif($wpntype == 3)
		{
			$type = "weapons";
			$rnd = rand(0, 3);
			if ($rnd == 0) 
			{
				$name = "Vibro Dagger";
				$img = "vibsw1";
				//org $qlvl = rand(23, 31);
				$qlvl = rand(55, 60);
				$weight = rand(800, 1200);
				$price = (rand(35, 44) * $qlvl)*4;
				$stat1chance = rand(0, 25); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(1000, 2000); }
				$stat2chance = rand(0, 25); if ($stat2chance == 0 && $stat1) { $stat2 = $this->addmagic($qlvl); $price = $price + rand(3000, 3000); }
				$stat3chance = rand(0, 25); if ($stat3chance == 0 && $stat1 && $stat2) { $stat3 = $this->addmagic($qlvl); $price = $price + rand(3000, 4000); }
				$mindmg = round(rand($qlvl * 1.2, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.5, $qlvl * 1.6));
			}
			elseif ($rnd == 1) 
			{
				$name = "Vibro Sword";
				$img = "vibsw2";
				// org $qlvl = rand(29, 37);
				$qlvl = rand(56, 61);
				$weight = rand(2000, 4200);
				$price = (rand(42, 63) * $qlvl)*4;
				$stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(1000, 2000); }
				$stat2chance = rand(0, 15); if ($stat2chance == 0 && $stat1) { $stat2 = $this->addmagic($qlvl); $price = $price + rand(2000, 3000); }
				$stat3chance = rand(0, 20); if ($stat3chance == 0 && $stat1 && $stat2) { $stat3 = $this->addmagic($qlvl); $price = $price + rand(3000, 4000); }
				$mindmg = round(rand($qlvl * 1.2, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.5, $qlvl * 1.6));
			}
			elseif ($rnd == 2) 
			{
				$name = "Vibro Hatchet";
				$img = "vibsw3";
				// $qlvl = rand(34, 48);
				$qlvl = rand(57, 62);
				$weight = rand(1800, 2700);
				$price = (rand(49, 67) * $qlvl)*4;
				$stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(1000, 2000); }
				$stat2chance = rand(0, 15); if ($stat2chance == 0 && $stat1) { $stat2 = $this->addmagic($qlvl); $price = $price + rand(2000, 3000); }
				$stat3chance = rand(0, 20); if ($stat3chance == 0 && $stat1 && $stat2) { $stat3 = $this->addmagic($qlvl); $price = $price + rand(3000, 4000); }
				$mindmg = round(rand($qlvl * 1.1, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.5, $qlvl * 1.6));
			}
			else
			{
				$name = "Double Bladed Lance";
				$img = "vibsw4";
				// $qlvl = rand(45, 60);
				$qlvl = rand(58, 63);
				$weight = rand(1800, 2900);
				$price = (rand(55, 77) * $qlvl)*4;
				$stat1chance = rand(0, 10); if ($stat1chance == 0) { $stat1 = $this->addmagic($qlvl); $price = $price + rand(1000, 2000); }
				$stat2chance = rand(0, 15); if ($stat2chance == 0 && $stat1) { $stat2 = $this->addmagic($qlvl); $price = $price + rand(2000, 3000); }
				$stat3chance = rand(0, 20); if ($stat3chance == 0 && $stat1 && $stat2) { $stat3 = $this->addmagic($qlvl); $price = $price + rand(3000, 4000); }
				$mindmg = round(rand($qlvl * 1.1, $qlvl * 1.3));
				$maxdmg = round(rand($qlvl * 1.55, $qlvl * 1.66));
			}
			
			$minlvl = round(rand($qlvl * 1.44, $qlvl * 1.99));
			$minskill = round(rand($qlvl * 1.44, $qlvl * 1.99));
		}
		
		if($wpntype == 1 OR $wpntype == 2 OR $wpntype == 3) //Weapons into DB
		{
			if($type2 != "raid")
            {
                //Insert in DB-regular
                $item_db = $this->JediItemsWeapons->newEntity();
                $item_db->ownerid = $userid;
                $item_db->position = "inv";
                $item_db->sizex = 16;
                $item_db->sizey = 16;
                $item_db->reql = $minlvl;
                $item_db->reqs = $minskill;
                $item_db->name = $name;
                $item_db->img = $img;
                $item_db->qlvl = $qlvl;
                $item_db->weight = $weight;
                $item_db->price = $price;
                $item_db->crafed = 0;
                $item_db->mindmg = $mindmg;
                $item_db->maxdmg = $maxdmg;
                $item_db->stat1 = $stat1;
                $item_db->stat2 = $stat2;
                $item_db->stat3 = $stat3;
                $item_db->stat4 = $stat4;
                $item_db->stat5 = $stat5;
                $this->JediItemsWeapons->save($item_db);
                $item_db->type = "weapons";
            }
            elseif($type2 == "raid")
            {
                $item_db = $this->JediTreasures->newEntity();
                $item_db->ownerid = $userid;
                $item_db->position = "inv";
                $item_db->type = "weapons";
                $item_db->sizex = 16;
                $item_db->sizey = 16;
                $item_db->reql = $minlvl;
                $item_db->reqs = $minskill;
                $item_db->name = $name;
                $item_db->img = $img;
                $item_db->qlvl = $qlvl;
                $item_db->weight = $weight;
                $item_db->price = $price;
                $item_db->crafed = 0;
                $item_db->mindmg = $mindmg;
                $item_db->maxdmg = $maxdmg;
                $item_db->stat1 = $stat1;
                $item_db->stat2 = $stat2;
                $item_db->stat3 = $stat3;
                $item_db->stat4 = $stat4;
                $item_db->stat5 = $stat5;
                $this->JediTreasures->save($item_db);                
            }
		}
		
		if($type_of_creation != "price" OR $type_of_creation != "box")
		{
			$statistics = $this->JediUserStatistics->get($userid);
            if($lootitem != "giant-rat" && $lootitem != "reek") {
                $statistics->loots += 1;
            }
			elseif($lootitem == "reek" OR $lootitem == "giant-rat") {
                $statistics->raidloots += 1;
            }
			$this->JediUserStatistics->save($statistics);
		}

        return $item_db;
    //Ende function loot()
    }

    public function addmagic($qlvl,$q2="0")
    {
        $th = rand(1,6);

        if ($th == 1) 
        {      // Main Abilities
            $incabi = rand(1,8);
            if ($incabi == 1) { $incabi = "cns"; }
            elseif ($incabi == 2) { $incabi = "agi"; }
            elseif ($incabi == 3) { $incabi = "spi"; }
            elseif ($incabi == 4) { $incabi = "itl"; }
            elseif ($incabi == 5) { $incabi = "tac"; }
            elseif ($incabi == 6) { $incabi = "dex"; }
            elseif ($incabi == 7) { $incabi = "lsa"; }
            elseif ($incabi == 8) { $incabi = "lsd"; }
            $incby = rand($qlvl*(0.023*$q2),$qlvl*(0.115*$q2)); if ($incby <= 0) { $incby = 1; }
            $form = "inc,".$incabi.",".$incby;
        }
        /*
        elseif ($th == 1) 
        {      // Secondary Talents
            $inctal = rand(1,8);
            if ($inctal == 1) { $inctal = "tint"; }
            elseif ($inctal == 2) { $inctal = "tper"; }
            elseif ($inctal == 3) { $inctal = "tfor"; }
            elseif ($inctal == 4) { $inctal = "tcha"; }
            elseif ($inctal == 5) { $inctal = "tcom"; }
            elseif ($inctal == 6) { $inctal = "tbio"; }
            elseif ($inctal == 7) { $inctal = "tsle"; }
            elseif ($inctal == 8) { $inctal = "tbod"; }
            $incby = rand($qlvl*(0.023*$q2),$qlvl*(0.115*$q2)); if ($incby <= 0) { $incby = 1; }
            $form = "inc,".$inctal.",".$incby;
        }
        */
        elseif ($th == 2) 
        {  // Neutral Skills
            $incfor = rand(1,6);
            if ($incfor == 1) { $incfor = "fspee"; }
            elseif ($incfor == 2) { $incfor = "fjump"; }
            elseif ($incfor == 3) { $incfor = "fpull"; }
            elseif ($incfor == 4) { $incfor = "fpush"; }
            elseif ($incfor == 5) { $incfor = "fseei"; }
            elseif ($incfor == 6) { $incfor = "fsabe"; }
            $incby = rand($qlvl*(0.027*$q2),$qlvl*(0.129*$q2)); if ($incby <= 0) { $incby = 1; }
            $form = "inc,".$incfor.",".$incby;
        }
        elseif ($th == 3) 
        {  // Light Side Skills
            $incfor = rand(1,8);
            if ($incfor == 1) { $incfor = "fpers"; }
            elseif ($incfor == 2) { $incfor = "fproj"; }
            elseif ($incfor == 3) { $incfor = "fblin"; }
            elseif ($incfor == 4) { $incfor = "fconf"; }
            elseif ($incfor == 5) { $incfor = "fheal"; }
            elseif ($incfor == 6) { $incfor = "fteam"; }
            elseif ($incfor == 7) { $incfor = "fprot"; }
            elseif ($incfor == 8) { $incfor = "fabso"; }
            $incby = rand($qlvl*(0.025*$q2),$qlvl*(0.121*$q2)); if ($incby <= 0) { $incby = 1; }
            $form = "inc,".$incfor.",".$incby;
        }
        elseif ($th == 4) 
        {  // Dark Side Skills
            $incfor = rand(1,8);
            if ($incfor == 1) { $incfor = "fthro"; }
            elseif ($incfor == 2) { $incfor = "frage"; }
            elseif ($incfor == 3) { $incfor = "fgrip"; }
            elseif ($incfor == 4) { $incfor = "fdrai"; }
            elseif ($incfor == 5) { $incfor = "fthun"; }
            elseif ($incfor == 6) { $incfor = "fchai"; }
            elseif ($incfor == 7) { $incfor = "fdest"; }
            elseif ($incfor == 8) { $incfor = "fdead"; }
            $incby = rand($qlvl*(0.021*$q2),$qlvl*(0.119*$q2)); if ($incby <= 0) { $incby = 1; }
            $form = "inc,".$incfor.",".$incby;
        }
        elseif ($th == 5) 
        {
            $incsta = rand(1,2);
            if ($incsta == 1) { $incsta = "health"; }
            elseif ($incsta == 2) { $incsta = "mana"; }
            $incby = rand($qlvl*(0.185*$q2),$qlvl*(0.600*$q2)); if ($incby <= 0) { $incby = 1; }
            $form = "inc,".$incsta.",".$incby;
        }
        elseif ($th == 6) 
        {        // Ultra Rares
            $inccha = rand(1,99);
            if ($inccha == 1) { $inccha = "inc,phealth";        $incby = rand($qlvl*(0.001*$q2),$qlvl*(0.240*$q2)); if ($incby <= 0) { $incby = 1; } if ($incby > 15) { $incby = 15; }}
            elseif ($inccha == 2) { $inccha = "inc,pmana";      $incby = rand($qlvl*(0.001*$q2),$qlvl*(0.290*$q2)); if ($incby <= 0) { $incby = 1; } if ($incby > 20) { $incby = 20; }}
            elseif ($inccha == 3) { $inccha = "inc,lhealth";    $incby = rand($qlvl*(0.028*$q2),$qlvl*(0.085*$q2)); if ($incby <= 0) { $incby = 1; } if ($incby > 3) { $incby = 3; }  }
            elseif ($inccha == 4) { $inccha = "inc,lmana";      $incby = rand($qlvl*(0.0048*$q2),$qlvl*(0.115*$q2)); if ($incby <= 0) { $incby = 1; } if ($incby > 5) { $incby = 5; }}
            elseif ($inccha == 5) { $inccha = "inc,pxp";        $incby = rand($qlvl*(0.001*$q2),$qlvl*(0.050*$q2)); if ($incby <= 0) { $incby = 1; } if ($incby > 5) { $incby = 5; }}
            elseif ($inccha == 6) { $inccha = "inc,xp";         $incby = rand($qlvl*(0.001*$q2),$qlvl*(0.050*$q2)); if ($incby <= 0) { $incby = 1; } }
            elseif ($inccha == 7) { $inccha = "inc,allabi";     $incby = rand($qlvl*(0.015*$q2),$qlvl*(0.035*$q2)); if ($incby <= 0) { $incby = 1; } }
            elseif ($inccha == 8) { $inccha = "inc,alltal";     $incby = rand($qlvl*(0.015*$q2),$qlvl*(0.035*$q2)); if ($incby <= 0) { $incby = 1; } }
            elseif ($inccha == 9) { $inccha = "inc,allnf";      $incby = rand($qlvl*(0.019*$q2),$qlvl*(0.040*$q2)); if ($incby <= 0) { $incby = 1; } }
            elseif ($inccha == 10) { $inccha = "inc,alllf";     $incby = rand($qlvl*(0.017*$q2),$qlvl*(0.037*$q2)); if ($incby <= 0) { $incby = 1; } }
            elseif ($inccha == 11) { $inccha = "inc,alldf";     $incby = rand($qlvl*(0.016*$q2),$qlvl*(0.035*$q2)); if ($incby <= 0) { $incby = 1; } }
            elseif ($inccha == 12) { $inccha = "inc,allfor";    $incby = rand($qlvl*(0.016*$q2),$qlvl*(0.035*$q2)); if ($incby <= 0) { $incby = 1; } }
            else { $inccha = "inc,allnf";                       $incby = rand($qlvl*(0.019*$q2),$qlvl*(0.040*$q2)); if ($incby <= 0) { $incby = 1; } }
            $form = $inccha.",".$incby;
        }
        return $form;
    }
//Ende Class
}

?>