<?php
if(!defined('InEmpireCMSIns'))
{
	exit();
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form name="form1" method="post" action="index.php" onSubmit="document.form1.Submit62222.disabled=true" autocomplete="off">
          <input type="hidden" name="defaultdata" value="<?php echo intval($_GET['defaultdata']);?>">
		  <input name="echdbtype" type="hidden" id="echdbtype" value="<?php echo $echdbtype;?>">
		  <input name="enews" type="hidden" id="enews" value="firstadmin">
		  <input name="f" type="hidden" id="f" value="6">
		  <input name="ok" type="hidden" id="ok" value="1">
          <tr class="header"> 
            <td height="25"> <div align="center"><strong><font color="#FFFFFF">第六步：初始化管理员账号</font></strong></div></td>
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
                          <td height="25"> <li>请在下面填写您要设置的管理员账号信息。</li></td>
                        </tr>
                        
                        <tr>
                          <td height="25"> <li>密码不能包含：$、&amp;、*、#、&lt;、&gt;、'、&quot;、/、\、%、;、空格</li></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                <br>
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
                  <tr> 
                    <td height="23" colspan="3"><strong>初始化管理员账号</strong></td>
                  </tr>
                  <tr> 
                    <td width="21%" height="25" bgcolor="#FFFFFF">用户名:</td>
                    <td width="36%" bgcolor="#FFFFFF"> <input name="username" type="text" id="username" size="30"> 
                    </td>
                    <td width="43%" bgcolor="#FFFFFF"><font color="#666666">管理员用户名，最大30个字节</font></td>
                  </tr>
                  <tr> 
                    <td height="25" bgcolor="#FFFFFF">密码:</td>
                    <td bgcolor="#FFFFFF"> <input name="password" type="password" id="password" size="30"></td>
                    <td bgcolor="#FFFFFF"><font color="#666666">管理员账号密码，区分大小写，设置8~30位</font></td>
                  </tr>
                  <tr> 
                    <td height="25" bgcolor="#FFFFFF"> <p>重复密码:</p></td>
                    <td bgcolor="#FFFFFF"> <input name="repassword" type="password" id="repassword" size="30"></td>
                    <td bgcolor="#FFFFFF"><font color="#666666">确认账号密码</font></td>
                  </tr>
                  <tr>
                    <td height="25" bgcolor="#FFFFFF"><font color="#FF0000">登录认证码:</font></td>
                    <td bgcolor="#FFFFFF"><input name="loginauth" type="text" id="loginauth" size="30"></td>
                    <td bgcolor="#FFFFFF"><font color="#FF0000">如果设置后台登录要输入认证码，更安全</font></td>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center"> 
                <input type="button" name="Submit52223" value="上一步" onClick="javascript:history.go(-3);">
                &nbsp;&nbsp; 
                <input type="submit" name="Submit62222" value="下一步">
              </div></td>
          </tr>
        </form>
      </table>