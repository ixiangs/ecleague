<?php
function cnt(){
    return array('1', '2', '3');
}

print(count(cnt()));

try{
    print('11111');
}finally{
    print('33333');
}