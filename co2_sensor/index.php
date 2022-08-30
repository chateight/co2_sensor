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
    elseif($value[2] > 2000){
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
      setInterval( repeat, 1000*10 );
    }
// using jQuery
function repeat(){
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
            set_style(val_int);
            $(".mes").text('換気状態は良好です');
        }
        if(val_int >= warn_level && val_int < alarm_level){
            set_style(val_int);
            $(".mes").text('換気が必要かもしれません');
        }
        if(val_int >= alarm_level){
            set_style(val_int);
            $(".mes").text('すぐに換気してください');
        }
      })
      // Ajax request failed
      //.fail(function(XMLHttpRequest, textStatus, errorThrown){
      //  alert(errorThrown);
      //});
      }
// set style the style
function set_style(val){
    if (val < warn_level){ 
            let color = document.querySelector(".style1");
            if (color == null){                     // not style1
                color = document.querySelector(".style2");
                if (color !== null){                 // style2
                    color.classList.remove("style2");
                    color.classList.add("style1");
                } else{
                    color = document.querySelector(".style3");
                    if (color !== null){            // style3
                        color.classList.remove("style3");
                        color.classList.add("style1");
                    }
                }
            }
    }
    if (val >= warn_level && val < alarm_level){ 
            let color = document.querySelector(".style2");
            if (color == null){                     // not style2
                color = document.querySelector(".style1");
                if (color !== null){                 // style1
                    color.classList.remove("style1");
                    color.classList.add("style2");
                } else{
                    color = document.querySelector(".style3");
                    if (color !== null){            // style3
                        color.classList.remove("style3");
                        color.classList.add("style2");
                    }
                }
            }
    }
                      
    if (val > alarm_level){ 
            let color = document.querySelector(".style3");
            if (color == null){                     // not style3
                color = document.querySelector(".style1");
                if (color !== null){                 // style1
                    color.classList.remove("style1");
                    color.classList.add("style3");
                } else{
                    color = document.querySelector(".style2");
                    if (color !== null){            // style2
                        color.classList.remove("style2");
                        color.classList.add("style3");
                    }
                }
            }
    }
}
</script>
<!-- <button id="button">送信</button>-->
</body>
</html>
