<?php
namespace App\Helpers\FurnitureStore;

use Illuminate\Support\Facades\DB;

class Showmenus{

    public static function showmenus($data,$parent = 0,$slug = '',$tab = '',$endtab = ''){
        $isOpen = false;
        foreach ($data as $val){
            $url = $val['url'];
            $id = $val["id"];
            $name = $val["name"];
            if ($val["parent_id"] == $parent){
                echo $tab;
                $isOpen = true;
                break;
            }
        }
        foreach ($data as $val){
            $url = $val['url'];
            $id = $val["id"];
            $name = $val["name"];
            if ($val["parent_id"] == $parent){
                    echo "<li><a href='$slug/$url'>$name</a>";
                        self::showmenus($data,$id,$url,"<ul class='sub-menu'>","</ul>");
                    echo "</li>";
            }
        }
        if ($isOpen){
            echo $endtab;
        }
    }
}
