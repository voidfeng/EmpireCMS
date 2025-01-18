<?php
if(!defined('InEmpireCMSIns'))
{
	exit();
}
?>
<?php
$mysqlr=eins_CanMysql();
$pgsqlr=eins_CanPgsql();
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form name="einsform" method="GET" action="index.php">
		  <input name="enews" type="hidden" id="enews" value="setdb">
		  <input name="f" type="hidden" id="f" value="5">
          <tr class="header"> 
            <td height="25"> <div align="center"><strong><font color="#FFFFFF">第四步：选择数据库类型</font></strong></div></td>
          </tr>
          <tr> 
            <td height="350" bgcolor="#FFFFFF"> <div align="center"> 
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D6E0EF">
                  <tr> 
                    <td height="23"><strong>提示信息</strong></td>
                  </tr>
                  <tr> 
                    <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
                        <tr> 
                          <td height="21"> <li>MySQL、MariaDB、其它MySQL‌内核的数据库 服务器PHP需开启<strong>mysql</strong>或<strong>mysqli</strong>模块。</li></td>
                        </tr>
                        <tr> 
                          <td height="21"> <li> PostgreSQL、国产华为高斯(openGauss)、‌国产金仓数据库(kingbase)、其它PostgreSQL‌内核的数据库 服务器PHP需开启<strong>pgsql</strong>模块。</li></td>
                        </tr>
                        <tr> 
                          <td height="21"> <li>其中 PostgreSQL、国产华为高斯(openGauss)、‌国产金仓数据库(kingbase)、其它PostgreSQL‌内核的数据库 目前需要商业授权版才支持。</li></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                <br>
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D6E0EF">
                  <tr> 
                    <td width="10%" height="23"> <div align="center"><strong>选择</strong></div></td>
                    <td width="45%"> <div align="center"><strong>数据库类型</strong></div></td>
                    <td width="30%"> <div align="center"><strong>当前服务器</strong></div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
                    <td height="30"><div align="center">
                      <input name="echdbtype" id="echdbtype" type="radio" value="0" checked="checked" />
                    </div></td>
                    <td><strong>MySQL</strong></td>
                    <td><div align="center"> 
                        <?php echo $mysqlr['can'];?>
                      </div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
                    <td height="30"><div align="center">
                      <input type="radio" name="echdbtype" id="echdbtype" value="1" />
                    </div></td>
                    <td><strong>MariaDB<br>
                        </strong></td>
                    <td><div align="center">  
                        <?php echo $mysqlr['can'];?>
                         </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'">
                    <td height="30"><div align="center">
                      <input type="radio" name="echdbtype" id="echdbtype" value="2" />
                    </div></td>
                    <td><strong>其它MySQL‌内核的数据库</strong></td>
                    <td>
                    <div align="center"><?php echo $mysqlr['can'];?></div></td>
                  </tr>

                  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"<?php echo $eins_usepgsql==1?'':' title="此项需商业授权版才支持"';?>> 
                    <td height="30"><div align="center">
                      <input type="radio" name="echdbtype" id="echdbtype" value="3"<?php echo $eins_usepgsql==1?'':' disabled';?> />
                    </div></td>
                    <td><strong>PostgreSQL‌</strong></td>
                    <td><div align="center">  
                        <?php echo $pgsqlr['can'];?>
                         </div></td>
                  </tr>
					
                  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"<?php echo $eins_usepgsql==1?'':' title="此项需商业授权版才支持"';?>> 
                    <td height="30"><div align="center">
                      <input type="radio" name="echdbtype" id="echdbtype" value="4"<?php echo $eins_usepgsql==1?'':' disabled';?> />
                    </div></td>
                    <td><strong>国产华为高斯(openGauss)</strong></td>
                    <td><div align="center">  
                        <?php echo $pgsqlr['can'];?>
                         </div></td>
                  </tr>
					
                  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"<?php echo $eins_usepgsql==1?'':' title="此项需商业授权版才支持"';?>> 
                    <td height="30"><div align="center">
                      <input type="radio" name="echdbtype" id="echdbtype" value="5"<?php echo $eins_usepgsql==1?'':' disabled';?> />
                    </div></td>
                    <td><strong>国产金仓数据库(kingbase)</strong></td>
                    <td><div align="center"> 
                        <?php echo $pgsqlr['can'];?>
                      </div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"<?php echo $eins_usepgsql==1?'':' title="此项需商业授权版才支持"';?>> 
                    <td height="30"> <div align="center">
                      <input type="radio" name="echdbtype" id="echdbtype" value="6"<?php echo $eins_usepgsql==1?'':' disabled';?> />
                    </div></td>
                    <td><strong>其它PostgreSQL‌内核的数据库</strong></td>
                    <td><div align="center"> 
                        <?php echo $pgsqlr['can'];?>
                      </div></td>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center"> 
                <input type="button" name="Submit523" value="上一步" onClick="javascript:history.go(-1);">
                &nbsp;&nbsp; &nbsp;&nbsp;
                <input type="submit" name="Submit623" value="下一步">
              </div></td>
          </tr>
        </form>
      </table>