<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class maxHealthComponent extends Component
{
    public function tempBonus($jewelry_model, $weapon_model)
    {
            $tmphealth = 0;
            $lhealth = 0;
            $phealth = 0;
            $tmpmana = 0;
            $lmana = 0;
            $pmana = 0;
            $tmpcns = 0;
            $tmpspi = 0;
            $tmpitl = 0;
            $tmpagi = 0;
            $tmptac = 0;
            $tmpdex = 0;
            $tmplsa = 0;
            $tmplsd = 0;
            $tmpxp = 0;
            $tmplxp = 0;
            $tmppxp = 0;

            foreach ($jewelry_model as $key => $jewelry)
            {
                for ($i = 1; $i <= 5; $i++)
                {
                  $temp = explode(",", $jewelry["stat{$i}"]);
                  if($temp[0] != "")
                  {
                  if ($temp[1] == "health") {
                    $tmphealth = $tmphealth + $temp[2];
                  }
                  elseif ($temp[1] == "lhealth") {
                    if($temp[2] > 3) $temp[2] = 3;
                    $lhealth = $lhealth + $temp[2];
                    if($lhealth > 3) $lhealth = 3;
                  }
                  elseif ($temp[1] == "phealth") {
                    if($temp[2] > 15) $temp[2] = 15;
                    $phealth = $phealth + $temp[2];
                    if($phealth > 30) $phealth = 30;
                  }
                  elseif ($temp[1] == "mana") {
                    $tmpmana = $tmpmana + $temp[2];
                  }
                  elseif ($temp[1] == "lmana") {
                    if($temp[2] > 5) $temp[2] = 5;
                    $lmana = $lmana + $temp[2];
                    if($lmana > 5) $lmana = 5;
                  }
                  elseif ($temp[1] == "pmana") {
                    if($temp[2] > 15) $temp[2] = 3;
                    $pmana = $pmana + $temp[2];
                    if($pmana > 15) $pmana = 15;
                  }
                  elseif ($temp[1] == "cns") {
                    $tmpcns = $tmpcns + $temp[2];
                  }
                  elseif ($temp[1] == "spi") {
                    $tmpspi = $tmpspi + $temp[2];
                  }
                  elseif ($temp[1] == "itl") {
                    $tmpitl = $tmpitl + $temp[2];
                  }
                  elseif ($temp[1] == "agi") {
                    $tmpagi = $tmpagi + $temp[2];
                  }
                  elseif ($temp[1] == "tac") {
                    $tmptac = $tmptac + $temp[2];
                  }
                  elseif ($temp[1] == "dex") {
                    $tmpdex = $tmpdex + $temp[2];
                  }
                  elseif ($temp[1] == "lsa") {
                    $tmplsa = $tmplsa + $temp[2];
                  }
                  elseif ($temp[1] == "lsd") {
                    $tmplsd = $tmplsd + $temp[2];
                  }
                  elseif ($temp[1] == "xp") {
                    $tmpxp = $tmpxp + $temp[2];
                  }
                  elseif ($temp[1] == "lxp") {
                    $tmplxp = $tmplxp + $temp[2];
                  }
                  elseif ($temp[1] == "pxp") {
                    $tmppxp = $tmppxp + $temp[2];
                  }
                  elseif ($temp[1] == "allabi") {
                    $tmpcns = $tmpcns + $temp[2];
                    $tmpspi = $tmpspi + $temp[2];
                    $tmpitl = $tmpitl + $temp[2];
                    $tmpagi = $tmpagi + $temp[2];
                    $tmptac = $tmptac + $temp[2];
                    $tmpdex = $tmpdex + $temp[2];
                    $tmplsa = $tmplsa + $temp[2];
                    $tmplsd = $tmplsd + $temp[2];
                  }
                }
                }
              }
              foreach ($weapon_model as $key => $weapon)
              {
                  for ($i = 1; $i <= 5; $i++)
                  {
                    $temp = explode(",", $weapon["stat{$i}"]);
                    if($temp[0] != "")
                    {
                    if ($temp[1] == "health") {
                      $tmphealth = $tmphealth + $temp[2];
                    }
                    elseif ($temp[1] == "lhealth") {
                      if($temp[2] > 3) $temp[2] = 3;
                      $lhealth = $lhealth + $temp[2];
                      if($lhealth > 3) $lhealth = 3;
                    }
                    elseif ($temp[1] == "phealth") {
                      if($temp[2] > 15) $temp[2] = 15;
                      $phealth = $phealth + $temp[2];
                      if($phealth > 30) $phealth = 30;
                    }
                    elseif ($temp[1] == "mana") {
                      $tmpmana = $tmpmana + $temp[2];
                    }
                    elseif ($temp[1] == "lmana") {
                      if($temp[2] > 5) $temp[2] = 5;
                      $lmana = $lmana + $temp[2];
                      if($lmana > 5) $lmana = 5;
                    }
                    elseif ($temp[1] == "pmana") {
                      if($temp[2] > 15) $temp[2] = 3;
                      $pmana = $pmana + $temp[2];
                      if($pmana > 15) $pmana = 15;
                    }
                    elseif ($temp[1] == "cns") {
                      $tmpcns = $tmpcns + $temp[2];
                    }
                    elseif ($temp[1] == "spi") {
                      $tmpspi = $tmpspi + $temp[2];
                    }
                    elseif ($temp[1] == "itl") {
                      $tmpitl = $tmpitl + $temp[2];
                    }
                    elseif ($temp[1] == "agi") {
                      $tmpagi = $tmpagi + $temp[2];
                    }
                    elseif ($temp[1] == "tac") {
                      $tmptac = $tmptac + $temp[2];
                    }
                    elseif ($temp[1] == "dex") {
                      $tmpdex = $tmpdex + $temp[2];
                    }
                    elseif ($temp[1] == "lsa") {
                      $tmplsa = $tmplsa + $temp[2];
                    }
                    elseif ($temp[1] == "lsd") {
                      $tmplsd = $tmplsd + $temp[2];
                    }
                    elseif ($temp[1] == "xp") {
                      $tmpxp = $tmpxp + $temp[2];
                    }
                    elseif ($temp[1] == "lxp") {
                      $tmplxp = $tmplxp + $temp[2];
                    }
                    elseif ($temp[1] == "pxp") {
                      $tmppxp = $tmppxp + $temp[2];
                    }
                    elseif ($temp[1] == "allabi") {
                      $tmpcns = $tmpcns + $temp[2];
                      $tmpspi = $tmpspi + $temp[2];
                      $tmpitl = $tmpitl + $temp[2];
                      $tmpagi = $tmpagi + $temp[2];
                      $tmptac = $tmptac + $temp[2];
                      $tmpdex = $tmpdex + $temp[2];
                      $tmplsa = $tmplsa + $temp[2];
                      $tmplsd = $tmplsd + $temp[2];
                    }
                }
                }
                }
            $array = array("tmphealth" => "$tmphealth", "lhealth" => "$lhealth", "phealth" => "$phealth",
                            "tmpmana" => "$tmpmana", "lmana" => "$lmana", "pmana" => "$pmana", "tmpcns" => "$tmpcns",
                            "tmpspi" => "$tmpspi", "tmpitl" => "$tmpitl", "tmpagi" => "$tmpagi", "tmptac" => "$tmptac",
                            "tmpdex" => "$tmpdex", "tmplsa" => "$tmplsa", "tmplsd" => "$tmplsd", "tmpxp" => "$tmpxp",
                            "tmplxp" => "$tmplxp", "tmppxp" => "$tmppxp");
            return $array;        
        }

    public function calc_maxHp($cns, $level, $jewelry_model, $weapon_model)
    {
        $tempBonus = $this->tempBonus($jewelry_model, $weapon_model);

        if($tempBonus["tmphealth"] != "") $tempBonus["tmphealth"] = intval($tempBonus["tmphealth"]);

        if($tempBonus["phealth"] != "") $tempBonus["phealth"] = intval($tempBonus["phealth"]);
        
        if($tempBonus["lhealth"] != "") $tempBonus["lhealth"] = intval($tempBonus["lhealth"]);
        
        $maxhealth = ($level * 2) + (($cns+$tempBonus["tmpcns"]) * 3) + 20 ;
        $phealth = $maxhealth * $tempBonus["phealth"] / 100;
        $maxhealth = $maxhealth + $tempBonus["tmphealth"] + ($level * $tempBonus["lhealth"]);
        $maxhealth = round($maxhealth + $phealth);
        return $maxhealth;
    }
    function calc_maxMana($spi, $itl, $level, $jewelry_model, $weapon_model) {

        $tempBonus = $this->tempBonus($jewelry_model, $weapon_model);

        if($tempBonus["tmpmana"] != "") $tempBonus["tmpmana"] = intval($tempBonus["tmpmana"]);

        if($tempBonus["pmana"] != "") $tempBonus["pmana"] = intval($tempBonus["pmana"]);
        
        if($tempBonus["lmana"] != "") $tempBonus["lmana"] = intval($tempBonus["lmana"]);
        
        if($tempBonus["tmpspi"] != "") $tempBonus["tmpspi"] = intval($tempBonus["tmpspi"]);

        if($tempBonus["tmpitl"] != "") $tempBonus["tmpitl"] = intval($tempBonus["tmpitl"]);

        $spi = $spi+$tempBonus["tmpspi"];
        $itl = $itl+$tempBonus["tmpitl"];

        $maxmana = ($level * 1.5) + ($spi * 4) + ($itl / 2.5) + 10 ;
        $pmana = $maxmana * $tempBonus["pmana"] / 100;
        $maxmana = $maxmana + $tempBonus["tmpmana"] + ($tempBonus["lmana"] * $level);
        $maxmana = round($maxmana + $pmana);
        return $maxmana;
      }

      public function calc_maxEnergy($cns, $agi, $level, $jewelry_model, $weapon_model)
      {
        $tempBonus = $this->tempBonus($jewelry_model, $weapon_model);

        if($tempBonus["tmpcns"] != "") $tempBonus["tmpcns"] = intval($tempBonus["tmpcns"]);

        if($tempBonus["tmpagi"] != "") $tempBonus["tmpagi"] = intval($tempBonus["tmpagi"]);

        $cns = $cns+$tempBonus["tmpcns"];
        $agi = $agi+$tempBonus["tmpagi"];   

          return round(((($level / 12.5) + ($cns / 33) + ($agi / 66))  * 3.3)+50,0);
      }
      public function tempBonusForces($jewelry_model, $weapon_model)
      {
        $fspee[2] = 0;
        $fjump[2] = 0;
        $fpull[2] = 0;
        $fpush[2] = 0;
        $fseei[2] = 0;
        $fsabe[2] = 0;

        $fpers[2] = 0;
        $fproj[2] = 0;
        $fblin[2] = 0;
        $fconf[2] = 0;
        $fheal[2] = 0;
        $fteam[2] = 0;
        $fprot[2] = 0;
        $fabso[2] = 0;
        $frvtl[2] = 0;

        $fthro[2] = 0;
        $frage[2] = 0;
        $fgrip[2] = 0;
        $fdrai[2] = 0;
        $fthun[2] = 0;
        $fchai[2] = 0;
        $fdest[2] = 0;
        $fdead[2] = 0;
        $ftnrg[2] = 0;

        foreach ($weapon_model as $key => $weapon)
        {
        for ($i = 1; $i <= 5; $i++) {
          $temp = explode(",", $weapon["stat{$i}"]);
          if($temp[0] != ""){
          if ($temp[1] == "fspee") {
            $fspee[2] = $fspee[2] + $temp[2];
          }
          elseif ($temp[1] == "fjump") {
            $fjump[2] = $fjump[2] + $temp[2];
          }
          elseif ($temp[1] == "fpull") {
            $fpull[2] = $fpull[2] + $temp[2];
          }
          elseif ($temp[1] == "fpush") {
            $fpush[2] = $fpush[2] + $temp[2];
          }
          elseif ($temp[1] == "fseei") {
            $fseei[2] = $fseei[2] + $temp[2];
          }
          elseif ($temp[1] == "fsabe") {
            $fsabe[2] = $fsabe[2] + $temp[2];
          }
          elseif ($temp[1] == "fpers") {
            $fpers[2] = $fpers[2] + $temp[2];
          }
          elseif ($temp[1] == "fproj") {
            $fproj[2] = $fproj[2] + $temp[2];
          }
          elseif ($temp[1] == "fblin") {
            $fblin[2] = $fblin[2] + $temp[2];
          }
          elseif ($temp[1] == "fconf") {
            $fconf[2] = $fconf[2] + $temp[2];
          }
          elseif ($temp[1] == "fheal") {
            $fheal[2] = $fheal[2] + $temp[2];
          }
          elseif ($temp[1] == "fteam") {
            $fteam[2] = $fteam[2] + $temp[2];
          }
          elseif ($temp[1] == "fprot") {
            $fprot[2] = $fprot[2] + $temp[2];
          }
          elseif ($temp[1] == "fabso") {
            $fabso[2] = $fabso[2] + $temp[2];
          }
          elseif ($temp[1] == "frvtl") {
            $frvtl[2] = $rvtl[2] + $temp[2];
          }
          elseif ($temp[1] == "fthro") {
            $fthro[2] = $fthro[2] + $temp[2];
          }
          elseif ($temp[1] == "frage") {
            $frage[2] = $frage[2] + $temp[2];
          }
          elseif ($temp[1] == "fgrip") {
            $fgrip[2] = $fgrip[2] + $temp[2];
          }
          elseif ($temp[1] == "fdrai") {
            $fdrai[2] = $fdrai[2] + $temp[2];
          }
          elseif ($temp[1] == "fthun") {
            $fthun[2] = $fthun[2] + $temp[2];
          }
          elseif ($temp[1] == "fchai") {
            $fchai[2] = $fchai[2] + $temp[2];
          }
          elseif ($temp[1] == "fdest") {
            $fdest[2] = $fdest[2] + $temp[2];
          }
          elseif ($temp[1] == "fdead") {
            $fdead[2] = $fdead[2] + $temp[2];
          }
          elseif ($temp[1] == "ftnrg") {
            $ftnrg[2] = $ftnrg[2] + $temp[2];
          }
          elseif ($temp[1] == "allnf") {
            $fspee[2] = $fspee[2] + $temp[2];
            $fjump[2] = $fjump[2] + $temp[2];
            $fpull[2] = $fpull[2] + $temp[2];
            $fpush[2] = $fpush[2] + $temp[2];
            $fseei[2] = $fseei[2] + $temp[2];
            $fsabe[2] = $fsabe[2] + $temp[2];
          }
          elseif ($temp[1] == "alllf") {
            $fpers[2] = $fpers[2] + $temp[2];
            $fproj[2] = $fproj[2] + $temp[2];
            $fblin[2] = $fblin[2] + $temp[2];
            $fconf[2] = $fconf[2] + $temp[2];
            $fheal[2] = $fheal[2] + $temp[2];
            $fteam[2] = $fteam[2] + $temp[2];
            $fprot[2] = $fprot[2] + $temp[2];
            $fabso[2] = $fabso[2] + $temp[2];
            $frvtl[2] = $frvtl[2] + $temp[2];
          }
          elseif ($temp[1] == "alldf") {
            $fthro[2] = $fthro[2] + $temp[2];
            $frage[2] = $frage[2] + $temp[2];
            $fgrip[2] = $fgrip[2] + $temp[2];
            $fdrai[2] = $fdrai[2] + $temp[2];
            $fthun[2] = $fthun[2] + $temp[2];
            $fchai[2] = $fchai[2] + $temp[2];
            $fdest[2] = $fdest[2] + $temp[2];
            $fdead[2] = $fdead[2] + $temp[2];
            $ftnrg[2] = $ftnrg[2] + $temp[2];
          }
          elseif ($temp[1] == "allfor") {
            $fspee[2] = $fspee[2] + $temp[2];
            $fjump[2] = $fjump[2] + $temp[2];
            $fpull[2] = $fpull[2] + $temp[2];
            $fpush[2] = $fpush[2] + $temp[2];
            $fseei[2] = $fseei[2] + $temp[2];
            $fsabe[2] = $fsabe[2] + $temp[2];
    
            $fpers[2] = $fpers[2] + $temp[2];
            $fproj[2] = $fproj[2] + $temp[2];
            $fblin[2] = $fblin[2] + $temp[2];
            $fconf[2] = $fconf[2] + $temp[2];
            $fheal[2] = $fheal[2] + $temp[2];
            $fteam[2] = $fteam[2] + $temp[2];
            $fprot[2] = $fprot[2] + $temp[2];
            $fabso[2] = $fabso[2] + $temp[2];
            $frvtl[2] = $frvtl[2] + $temp[2];
    
            $fthro[2] = $fthro[2] + $temp[2];
            $frage[2] = $frage[2] + $temp[2];
            $fgrip[2] = $fgrip[2] + $temp[2];
            $fdrai[2] = $fdrai[2] + $temp[2];
            $fthun[2] = $fthun[2] + $temp[2];
            $fchai[2] = $fchai[2] + $temp[2];
            $fdest[2] = $fdest[2] + $temp[2];
            $fdead[2] = $fdead[2] + $temp[2];
            $ftnrg[2] = $ftnrg[2] + $temp[2];
          }
        }  
      }      
      }
      foreach ($jewelry_model as $key => $jewelry)
      {
      for ($i = 1; $i <= 5; $i++) {
        $temp = explode(",", $jewelry["stat{$i}"]);
        if($temp[0] != ""){
        if ($temp[1] == "fspee") {
          $fspee[2] = $fspee[2] + $temp[2];
        }
        elseif ($temp[1] == "fjump") {
          $fjump[2] = $fjump[2] + $temp[2];
        }
        elseif ($temp[1] == "fpull") {
          $fpull[2] = $fpull[2] + $temp[2];
        }
        elseif ($temp[1] == "fpush") {
          $fpush[2] = $fpush[2] + $temp[2];
        }
        elseif ($temp[1] == "fseei") {
          $fseei[2] = $fseei[2] + $temp[2];
        }
        elseif ($temp[1] == "fsabe") {
          $fsabe[2] = $fsabe[2] + $temp[2];
        }
        elseif ($temp[1] == "fpers") {
          $fpers[2] = $fpers[2] + $temp[2];
        }
        elseif ($temp[1] == "fproj") {
          $fproj[2] = $fproj[2] + $temp[2];
        }
        elseif ($temp[1] == "fblin") {
          $fblin[2] = $fblin[2] + $temp[2];
        }
        elseif ($temp[1] == "fconf") {
          $fconf[2] = $fconf[2] + $temp[2];
        }
        elseif ($temp[1] == "fheal") {
          $fheal[2] = $fheal[2] + $temp[2];
        }
        elseif ($temp[1] == "fteam") {
          $fteam[2] = $fteam[2] + $temp[2];
        }
        elseif ($temp[1] == "fprot") {
          $fprot[2] = $fprot[2] + $temp[2];
        }
        elseif ($temp[1] == "fabso") {
          $fabso[2] = $fabso[2] + $temp[2];
        }
        elseif ($temp[1] == "frvtl") {
          $frvtl[2] = $rvtl[2] + $temp[2];
        }
        elseif ($temp[1] == "fthro") {
          $fthro[2] = $fthro[2] + $temp[2];
        }
        elseif ($temp[1] == "frage") {
          $frage[2] = $frage[2] + $temp[2];
        }
        elseif ($temp[1] == "fgrip") {
          $fgrip[2] = $fgrip[2] + $temp[2];
        }
        elseif ($temp[1] == "fdrai") {
          $fdrai[2] = $fdrai[2] + $temp[2];
        }
        elseif ($temp[1] == "fthun") {
          $fthun[2] = $fthun[2] + $temp[2];
        }
        elseif ($temp[1] == "fchai") {
          $fchai[2] = $fchai[2] + $temp[2];
        }
        elseif ($temp[1] == "fdest") {
          $fdest[2] = $fdest[2] + $temp[2];
        }
        elseif ($temp[1] == "fdead") {
          $fdead[2] = $fdead[2] + $temp[2];
        }
        elseif ($temp[1] == "ftnrg") {
          $ftnrg[2] = $ftnrg[2] + $temp[2];
        }
        elseif ($temp[1] == "allnf") {
          $fspee[2] = $fspee[2] + $temp[2];
          $fjump[2] = $fjump[2] + $temp[2];
          $fpull[2] = $fpull[2] + $temp[2];
          $fpush[2] = $fpush[2] + $temp[2];
          $fseei[2] = $fseei[2] + $temp[2];
          $fsabe[2] = $fsabe[2] + $temp[2];
        }
        elseif ($temp[1] == "alllf") {
          $fpers[2] = $fpers[2] + $temp[2];
          $fproj[2] = $fproj[2] + $temp[2];
          $fblin[2] = $fblin[2] + $temp[2];
          $fconf[2] = $fconf[2] + $temp[2];
          $fheal[2] = $fheal[2] + $temp[2];
          $fteam[2] = $fteam[2] + $temp[2];
          $fprot[2] = $fprot[2] + $temp[2];
          $fabso[2] = $fabso[2] + $temp[2];
          $frvtl[2] = $frvtl[2] + $temp[2];
        }
        elseif ($temp[1] == "alldf") {
          $fthro[2] = $fthro[2] + $temp[2];
          $frage[2] = $frage[2] + $temp[2];
          $fgrip[2] = $fgrip[2] + $temp[2];
          $fdrai[2] = $fdrai[2] + $temp[2];
          $fthun[2] = $fthun[2] + $temp[2];
          $fchai[2] = $fchai[2] + $temp[2];
          $fdest[2] = $fdest[2] + $temp[2];
          $fdead[2] = $fdead[2] + $temp[2];
          $ftnrg[2] = $ftnrg[2] + $temp[2];
        }
        elseif ($temp[1] == "allfor") {
          $fspee[2] = $fspee[2] + $temp[2];
          $fjump[2] = $fjump[2] + $temp[2];
          $fpull[2] = $fpull[2] + $temp[2];
          $fpush[2] = $fpush[2] + $temp[2];
          $fseei[2] = $fseei[2] + $temp[2];
          $fsabe[2] = $fsabe[2] + $temp[2];
  
          $fpers[2] = $fpers[2] + $temp[2];
          $fproj[2] = $fproj[2] + $temp[2];
          $fblin[2] = $fblin[2] + $temp[2];
          $fconf[2] = $fconf[2] + $temp[2];
          $fheal[2] = $fheal[2] + $temp[2];
          $fteam[2] = $fteam[2] + $temp[2];
          $fprot[2] = $fprot[2] + $temp[2];
          $fabso[2] = $fabso[2] + $temp[2];
          $frvtl[2] = $frvtl[2] + $temp[2];
  
          $fthro[2] = $fthro[2] + $temp[2];
          $frage[2] = $frage[2] + $temp[2];
          $fgrip[2] = $fgrip[2] + $temp[2];
          $fdrai[2] = $fdrai[2] + $temp[2];
          $fthun[2] = $fthun[2] + $temp[2];
          $fchai[2] = $fchai[2] + $temp[2];
          $fdest[2] = $fdest[2] + $temp[2];
          $fdead[2] = $fdead[2] + $temp[2];
          $ftnrg[2] = $ftnrg[2] + $temp[2];
        }
      }
    }        
    }
    $array = array("tmpfspee" => $fspee[2], "tmpfjump" => $fjump[2], "tmpfpull" => $fpull[2],
    "tmpfpush" => $fpush[2], "tmpfseei" => $fseei[2], "tmpfsabe" => $fsabe[2], "tmpfpers" => $fpers[2],
    "tmpfproj" => $fproj[2], "tmpfblin" => $fblin[2], "tmpfconf" => $fconf[2], "tmpfheal" => $fheal[2],
    "tmpfteam" => $fteam[2], "tmpfprot" => $fprot[2], "tmpfabso" => $fabso[2], "tmpfrvtl" => $frvtl[2],
    "tmpfthro" => $fthro[2], "tmpfrage" => $frage[2], "tmpfgrip" => $fgrip[2], "tmpfdrai" => $fdrai[2],
    "tmpfthun" => $fthun[2], "tmpfchai" => $fchai[2], "tmpfdest" => $fdest[2], "tmpfdead" => $fdead[2],
    "tmpftnrg" => $ftnrg[2]);
    return $array;   
  }

}
?>