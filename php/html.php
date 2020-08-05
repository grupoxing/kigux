<?php
  session_start();
  header("Content-Type: text/html; charset=UTF-8");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
   
if (isset($_POST["id"])) {
    if ($_POST["id"] == 'bg'){
        echo '
        <div class="box_demo">
             <div class="box_main">
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/1-normal.jpg) repeat\'" src="/foto/bg/1-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/2-normal.jpg) repeat\'" src="/foto/bg/2-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/3-normal.jpg) repeat\'" src="/foto/bg/3-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/4-normal.jpg) repeat\'" src="/foto/bg/4-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/5-normal.jpg) repeat\'" src="/foto/bg/5-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/6-normal.jpg) repeat\'" src="/foto/bg/6-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/7-normal.jpg) repeat\'" src="/foto/bg/7-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/8-normal.jpg) repeat\'" src="/foto/bg/8-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/9-normal.jpg) repeat\'" src="/foto/bg/9-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/10-normal.jpg) repeat\'" src="/foto/bg/10-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/11-normal.jpg) repeat\'" src="/foto/bg/11-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/12-normal.jpg) repeat\'" src="/foto/bg/12-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/13-normal.jpg) repeat\'" src="/foto/bg/13-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/14-normal.jpg) repeat\'" src="/foto/bg/14-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/15-normal.jpg) repeat\'" src="/foto/bg/15-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/16-normal.jpg) repeat\'" src="/foto/bg/16-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/17-normal.jpg) repeat\'" src="/foto/bg/17-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/18-normal.jpg) repeat\'" src="/foto/bg/18-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/19-normal.jpg) repeat\'" src="/foto/bg/19-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/20-normal.jpg) repeat\'" src="/foto/bg/20-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/21-normal.jpg) repeat\'" src="/foto/bg/21-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/22-normal.jpg) repeat\'" src="/foto/bg/22-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/23-normal.jpg) repeat\'" src="/foto/bg/23-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/24-normal.jpg) repeat\'" src="/foto/bg/24-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/25-normal.jpg) repeat\'" src="/foto/bg/25-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/26-normal.jpg) repeat\'" src="/foto/bg/26-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/27-normal.jpg) repeat\'" src="/foto/bg/27-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/28-normal.jpg) repeat\'" src="/foto/bg/28-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/29-normal.jpg) repeat\'" src="/foto/bg/29-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/30-normal.jpg) repeat\'" src="/foto/bg/30-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/31-normal.jpg) repeat\'" src="/foto/bg/31-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/32-normal.jpg) repeat\'" src="/foto/bg/32-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/33-normal.jpg) repeat\'" src="/foto/bg/33-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/34-normal.jpg) repeat\'" src="/foto/bg/34-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/35-normal.jpg) repeat\'" src="/foto/bg/35-mini.jpg" width="35px" border="0"></a>
                  <a href="#"><img onmouseover="document.body.style.background = \'url(/foto/bg/36-normal.jpg) repeat\'" src="/foto/bg/36-mini.jpg" width="35px" border="0"></a>
             </div>
        </div>';
    }
} ?>
