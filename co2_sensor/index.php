<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="center"><h1>『密』センサー</h1></div>
<div class="center"><h2> 現在のCO2濃度は、</h2></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="center"><h2><div class="data">
<?php
require_once './data_read.php';
echo "ppmです</div></h2></div>";
//
if($value[2] < 1000){
    echo "<h3><div class=\"style1\"><div class=\"mes\">換気状態は良好です</div></div></h3>";
    }
    elseif($value[2] < 2000){
        echo "<h3><div class=\"style2\"><div class=\"mes\">換気が必要かもしれません</div></div></h3>";
    }
    elseif($value[2] >= 2000){
        echo "<h3><div class=\"style3\"><div class=\"mes\">すぐに換気してください</div></div></h3>";
    }
//
?>
<script>
const warn_level = 1000;
const alarm_level = 2000;
// read and set the value
update();
// repeat ajax [rocess
function update(){
      setInterval( repeat, 1000*30 );
    }
// using jQuery
function repeat(){
      const message = ["換気状態は良好です", "換気が必要かもしれません", "すぐに換気してください"];
      $.ajax({
        type: "GET",
        url: "data_read.php",
        dataType : "text"
      })
      // Ajax request success
      .done(function(data){
        let val_int = Number(data)
        console.log(val_int);
        $(".data").text(data + 'ppmです');
        if (val_int < warn_level){
            $(".mes").text(message[0]);
        }
        if(val_int >= warn_level && val_int < alarm_level){
            $(".mes").text(message[1]);
        }
        if(val_int >= alarm_level){
            $(".mes").text(message[2]);
        }
        set_style(val_int);
      })
      // Ajax request failed
      //.fail(function(XMLHttpRequest, textStatus, errorThrown){
      //  alert(errorThrown);
      //});
      }
// set style to the class "style"
function class_set(key, style_ary){
    let color = document.querySelector("." + style_ary[key]);
    if (color === null){                    // need to change the style?
        for(let item in style_ary ){        // yes
            color = document.querySelector("." + style_ary[item]);
            if (color !== null){
                color.classList.remove(style_ary[item]);
                color.classList.add(style_ary[key]);
                break;
            }
        } 
    }
}
// select the syle from the value
function set_style(val){
    const style_ary = ["style1", "style2", "style3"];
    let key = (val < warn_level)? 0 
    : (val >= warn_level && val < alarm_level)? 1 
    : 2;
    // call class property setting
    class_set(key, style_ary);
}
</script>
</body>
</html>
