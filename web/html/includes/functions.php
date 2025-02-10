<?php
function sortArrow($sortBy, $col, $dir){
    //return $sortBy === $col ? ($dir === 'ASC' ? '&darr;' : '&uarr;') : '';
    if($sortBy === $col){
        if($dir === 'ASC'){
            return '&darr;';
        }else {
            return '&uarr;';
        }
    } else {
        return '';
    }
}
