<?php
if(!defined('InEmpireCMSIns'))
{
	exit();
}
?>
<script>
		  function CheckSubmit()
		  {
		  	var ok;
			ok=confirm("确认要进入下一步?");
			if(ok)
			{
		  		document.form1.Submit6223.disabled=true;
				return true;
			}
			return false;
		  }
		  </script> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form name="form1" method="post" action="index.php" onSubmit="document.form1.Submit6223.disabled=true;" autocomplete="off">
		  <input name="enews" type="hidden" id="enews" value="setdb">
		  <input name="f" type="hidden" id="f" value="5">
		  <input name="ok" type="hidden" id="ok" value="1">
          <tr class="header"> 
            <td height="25"> <div align="center"><strong><font color="#FFFFFF">第五步：配置数据库</font></strong></div></td>
          </tr>
          <tr> 
            <td height="100" bgcolor="#FFFFFF"> <div align="center"> 
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
                  <tr> 
                    <td height="23"><strong>提示信息</strong></td>
                  </tr>
                  <tr> 
                    <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
                        <tr> 
                          <td height="23"> <li>请在下面填写您的数据库账号信息, 通常情况下不需要修改绿色选项内容。</li></td>
                        </tr>
                        <tr> 
                          <td height="23"> <li>带*项为不能为空。</li></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                <br>
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
                  <tr> 
                    <td width="21%" height="23"> <div align="center"><strong>设置选项</strong></div></td>
                    <td width="36%"><div align="center"><strong>当前值</strong></div></td>
                    <td width="43%"><div align="center"><strong>注释</strong></div></td>
                  </tr>
					
                    <tr bgcolor="#FFFFFF">
                      <td height="25">使用数据库类型:</td>
                      <td><strong><?php echo eins_ReturnDbtypeName($echdbtype); ?></strong></td>
                      <td>&nbsp;</td>
                    </tr>
				<?php
				if($echdbtype<3)
				{
				?>
                    <tr bgcolor="#FFFFFF">
                      <td height="25"><font color="#009900">MYSQL接口类型:</font></td>
                      <td><select name="mydbtype" id="mydbtype">
					  	<?php
					  	if(function_exists('mysql_connect'))
					  	{
					  	?>
                        <option value="mysql">mysql</option>
						<?php
						}
						?>
                        <option value="mysqli">mysqli</option>
                      </select>                      </td>
                      <td><font color="#666666">一般默认即可</font></td>
                    </tr>
				  <?php
				  if(!$echdbtype)
				  {
				  ?>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><font color="#009900">MYSQL版本:</font></td>
                    <td><table width="100%" border="0" cellpadding="3" cellspacing="1">
                        <tr> 
                          <td height="22"><input type="radio" name="mydbver" value="auto">
                            自动识别</td>
                        </tr>
                        <tr> 
                          <td height="22"> <input type="radio" name="mydbver" value="4.0">
                            MYSQL 4.0.*/3.*</td>
                        </tr>
                        <tr> 
                          <td height="22"> <input type="radio" name="mydbver" value="4.1">
                            MYSQL 4.1.*</td>
                        </tr>
                        <tr> 
                          <td height="22"> <input type="radio" name="mydbver" value="5.0" checked>
                            MYSQL 5.*或以上</td>
                        </tr>
                      </table></td>
                    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr> 
                          <td><font color="#666666">一般默认即可，通常选“MYSQL 5.*或以上”</font></td>
                        </tr>
                      </table></td>
                  </tr>
				  <?php
				  }
				  else
				  {
				  ?>
				  <input name="mydbver" type="hidden" id="mydbver" value="5.0">
				  <?php
				  }
				  ?>
                  <tr bgcolor="#FFFFFF">
                    <td height="25"><font color="#009900">表引擎:</font></td>
                    <td><input name="euseinnodb" type="radio" value="0" checked="checked" />
                      MyISAM
                    <input type="radio" name="euseinnodb" value="1" />
                    InnoDB</td>
                    <td><font color="#666666">一般默认即可</font></td>
                  </tr>
                  <tr bgcolor="#FFFFFF">
                    <td height="25"><font color="#009900">编码:</font></td>
                    <td><input name="dbcharmb" type="radio" value="0" checked>utf8
                     <input name="dbcharmb" type="radio" value="1">utf8mb4</td>
                    <td><font color="#666666">一般默认即可，utf8mb4需要MYSQL5.7以上版本才支持</font></td>
                  </tr>
				<?php
				}
				else
				{
				?>
				  <input name="mydbver" type="hidden" id="mydbver" value="9.0">
				<?php
				}
				?>
                  <tr bgcolor="#FFFFFF"> 
                    <td width="21%" height="25"><font color="#009900">数据库服务器(*):</font></td>
                    <td width="36%"> <input name="mydbhost" type="text" id="mydbhost" value="127.0.0.1" size="30"></td>
                    <td width="43%"><font color="#666666">数据库服务器地址, 一般为 localhost或127.0.0.1</font></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><font color="#009900">数据库服务器端口:</font></td>
                    <td> <input name="mydbport" type="text" id="mydbport" size="30">                    </td>
                    <td><font color="#666666"><?php echo $eusedbtypename;?>端口,空为默认端口, 一般设为空</font></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25">数据库用户名:</td>
                    <td> <input name="mydbusername" type="text" id="mydbusername" value="username" size="30"></td>
                    <td><font color="#666666"><?php echo $eusedbtypename;?>数据库链接账号</font></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25">数据库密码:</td>
                    <td> <input name="mydbpassword" type="password" id="mydbpassword" size="30"></td>
                    <td><font color="#666666"><?php echo $eusedbtypename;?>数据库链接密码</font></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25">数据库名(*):</td>
                    <td> <input name="mydbname" type="text" id="mydbname" value="empirecms" size="30">                    </td>
                    <td><font color="#666666">数据库名称，使用PHP8以上或PostgreSQL需填写已建好的库名</font></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><font color="#009900">表名前缀(*):</font></td>
                    <td><input name="mydbtbpre" type="text" id="mydbtbpre" value="phome_" size="30"></td>
                    <td><font color="#666666">同一数据库安装多个CMS时可改变默认，不能数字开头</font></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"><font color="#009900">COOKIE前缀(*):</font></td>
                    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
                        <tr>
                          <td>前台：
                            <input name="mycookievarpre" type="text" id="mycookievarpre" value="<?php echo $mycookievarpre;?>" size="22"></td>
                        </tr>
                        <tr>
                          <td>后台：
                            <input name="myadmincookievarpre" type="text" id="myadmincookievarpre" value="<?php echo $myadmincookievarpre;?>" size="22"></td>
                        </tr>
                      </table>                    </td>
                    <td><font color="#666666">由<strong>英文字母</strong>组成，默认即可</font></td>
                  </tr>
				  <?php
				  if($eins_candefdata==1)
				  {
				  ?>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25">内置初始数据:</td>
                    <td><input name="defaultdata" type="checkbox" id="defaultdata" value="1">
                      是</td>
                    <td><font color="#666666">测试软件时选择</font></td>
                  </tr>
				  <?php
				  }
				  ?>
                </table>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center"> 
                <input type="button" name="Submit5223" value="上一步" onClick="javascript:history.go(-1);">
                &nbsp;&nbsp; &nbsp;&nbsp;
                <input type="submit" name="Submit6223" value="下一步">
                <input name="mydbchar" type="hidden" id="mydbchar" value="<?php echo $dbchar;?>">
                <input name="mysetchar" type="hidden" id="mysetchar" value="<?php echo $setchar;?>">
				<input name="echdbtype" type="hidden" id="echdbtype" value="<?php echo $echdbtype;?>">
              </div></td>
          </tr>
        </form>
      </table>