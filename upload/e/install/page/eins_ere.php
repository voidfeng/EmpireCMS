<?php
if(!defined('InEmpireCMSIns'))
{
	exit();
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form name="form1" method="post" action="">
          <tr class="header"> 
            <td height="25"> <div align="center"><strong><font color="#FFFFFF">第二步：检测运行环境</font></strong></div></td>
          </tr>
          <tr> 
            <td height="350" bgcolor="#FFFFFF"> <div align="center"> 
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
                  <tr> 
                    <td height="23"><strong>提示信息</strong></td>
                  </tr>
                  <tr> 
                    <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
                        <tr> 
                          <td height="21"> <li>粗体字项目是必须支持的项目。</li></td>
                        </tr>
                        <tr> 
                          <td height="21"> <li>不支持GD库不影响系统正常运行，但图片缩略图与水印功能不能使用。</li></td>
                        </tr>
                        <tr> 
                          <td height="21"> <li>不支持采集不影响系统正常使用，但采集功能与远程保存附件不能正常使用。</li></td>
                        </tr>
                        <tr> 
                          <td height="21"> <li>点击“支持采集”链接可对采集进行测试。</li></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                <br>
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
                  <tr> 
                    <td width="25%" height="23"> <div align="center"><strong>项目</strong></div></td>
                    <td width="30%"> <div align="center"><strong>帝国CMS所需配置</strong></div></td>
                    <td width="30%"> <div align="center"><strong>当前服务器</strong></div></td>
                    <td width="15%"> <div align="center"><strong>测试结果</strong></div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><div align="center">操作系统</div></td>
                    <td><div align="center">不限</div></td>
                    <td><div align="center"> 
                        <?php echo eins_GetUseSys();?>
                      </div></td>
                    <td><div align="center">√</div></td>
                  </tr>
					<?php
					$phpr=eins_GetPhpVer();
					?>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><div align="center"><strong>PHP版本</strong></div></td>
                    <td><div align="center"><strong>4.2.3+<br>
                        </strong></div></td>
                    <td><div align="center"> <b> 
                        <?php echo $phpr['ver'];?>
                        </b> </div></td>
                    <td><div align="center"> 
                        <?php echo $phpr['result'];?>
                      </div></td>
                  </tr>
					<?php
  					$mysqlr=eins_CanMysql();
  					?>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><div align="center"><strong>MYSQL支持</strong></div></td>
                    <td><div align="center"><strong>支持</strong></div></td>
                    <td><div align="center"> <b> 
                        <?php echo $mysqlr['can'];?>
                        </b> </div></td>
                    <td><div align="center"> 
                        <?php echo $mysqlr['result'];?>
                      </div></td>
                  </tr>
					<?php
  					$pgsqlr=eins_CanPgsql();
  					?>
                  <tr bgcolor="#FFFFFF">
                    <td height="25"><div align="center"><strong>PostgreSQL‌支持</strong></div></td>
                    <td><div align="center">不限</div></td>
                    <td><div align="center"><?php echo $pgsqlr['can'];?></div></td>
                    <td><div align="center"><?php echo $pgsqlr['result'];?></div></td>
                  </tr>
					<?php
 					$phpsafer=eins_GetPhpSafemod();
  					?>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><div align="center"><strong>PHP运行于安全模式</strong></div></td>
                    <td><div align="center"><strong>否</strong></div></td>
                    <td><div align="center"> <b> 
                        <?php echo $phpsafer['word'];?>
                        </b> </div></td>
                    <td><div align="center"> 
                        <?php echo $phpsafer['result'];?>
                      </div></td>
                  </tr>
					<?php
  					$gdr=eins_GetGd();
  					?>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><div align="center">支持GD库</div></td>
                    <td><div align="center">不限</div></td>
                    <td><div align="center"> 
                        <?php echo $gdr['can'];?>
                      </div></td>
                    <td><div align="center"> 
                        <?php echo $gdr['result'];?>
                      </div></td>
                  </tr>
					<?php
  					$cjr=eins_GetCj();
  					?>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="24"> <div align="center"><a title="测试采集" href="#empirecms" onClick="window.open('index.php?enews=TestCj','','width=200,height=80');"><u>支持采集</u></a></div></td>
                    <td><div align="center">不限</div></td>
                    <td><div align="center"> 
                        <?php echo $cjr['word'];?>
                      </div></td>
                    <td><div align="center"> 
                        <?php echo $cjr['result'];?>
                      </div></td>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center"> 
                <input type="button" name="Submit523" value="上一步" onClick="javascript:history.go(-1);">
                &nbsp;&nbsp; &nbsp;&nbsp;
                <input type="button" name="Submit623" value="下一步" onClick="self.location.href='index.php?enews=ckpath&f=3';">
              </div></td>
          </tr>
        </form>
      </table>