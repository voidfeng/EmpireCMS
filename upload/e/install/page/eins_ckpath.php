<?php
if(!defined('InEmpireCMSIns'))
{
	exit();
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form name="form1" method="post" action="">
          <tr class="header"> 
            <td height="25"> <div align="center"><strong><font color="#FFFFFF">第三步：设置目录权限</font></strong></div></td>
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
                          <td height="25"><li><font color="#FF0000">如果您的服务器使用 
                              Windows 操作系统，可跳过这一步。</font></li></td>
                        </tr>
                        <tr> 
                          <td height="25"> <li>将下面目录权限设为0777, 除了红色目录外，是目录全部要把权限应用于子目录与文件。 
                            </li></td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                <br>
                <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
                  <tr> 
                    <td width="34%" height="23"> <div align="center"><strong>目录文件名称</strong></div></td>
                    <td width="42%"> <div align="center"><strong>说明</strong></div></td>
                    <td width="24%"> <div align="center"><strong>权限检查</strong></div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left"><font color="#FF0000"><strong>/</strong></font></div></td>
                    <td> <div align="center"><font color="#FF0000">系统根目录(不要应用于子目录)</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/c</div></td>
                    <td> <div align="center"><font color="#666666">系统缓存目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../c","../../c/ecachetmp");?> 
                      </div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/d</div></td>
                    <td> <div align="center"><font color="#FF0000">附件目录 (无需脚本执行权限)</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../d","../../d/etmp/etitlepic");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/e/config/config.php</div></td>
                    <td> <div align="center"><font color="#666666">数据库等参数配置文件</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../config/config.php");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/e/data/dbcache</div></td>
                    <td> <div align="center"><font color="#666666">部分数据库缓存文件存放目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../data/dbcache");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25">/e/template</td>
                    <td> <div align="center"><font color="#666666">动态页面的模板目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../template");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25">/eadmin/admin/ebak/bdata</td>
                    <td> <div align="center"><font color="#666666">备份数据存放目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../eadmin/admin/ebak/bdata");?> 
                      </div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25">/eadmin/admin/ebak/zip</td>
                    <td> <div align="center"><font color="#666666">备份数据压缩存放目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../eadmin/admin/ebak/zip");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/ecachefiles</div></td>
                    <td> <div align="center"><font color="#666666">动态页面缓存目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../ecachefiles","../../ecachefiles/empirecms");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/esavedatas</div></td>
                    <td> <div align="center"><font color="#666666">内容存文本文件存放目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../esavedatas","../../esavedatas/edtxt");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/html</div></td>
                    <td> <div align="center"><font color="#666666">默认可选的HTML存放目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../html");?> 
                      </div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/s</div></td>
                    <td> <div align="center"><font color="#666666">专题存放目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../s");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/search</div></td>
                    <td> <div align="center"><font color="#666666">搜索表单</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../search","../../search/test.txt");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/t</div></td>
                    <td> <div align="center"><font color="#666666">标题分类存放目录</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../t");?> 
                      </div></td>
                  </tr>
				  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/w</div></td>
                    <td> <div align="center"><font color="#666666">可选的网站存放根目录(预留)</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../w");?> 
                      </div></td>
                  </tr>
                  <tr bgcolor="#FFFFFF"> 
                    <td height="25"> <div align="left">/index.html</div></td>
                    <td> <div align="center"><font color="#666666">网站首页</font></div></td>
                    <td> <div align="center"> <?php echo eins_CheckFileMod("../../index.html");?> 
                      </div></td>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center"> 
                <script>
			  function CheckNext()
			  {
			  var ok;
			  //ok=confirm("确认有应用于子目录?");
			  ok=true;
			  if(ok)
			  {
			  self.location.href='index.php?enews=chdbtype&f=4';
			  }
			  }
			  </script>
                <input type="button" name="Submit523" value="上一步" onClick="javascript:history.go(-1);">
                &nbsp;&nbsp; &nbsp;&nbsp;
                <input type="button" name="Submit72" value="刷新权限状态" onClick="javascript:self.location.href='index.php?enews=ckpath&f=3';">
                &nbsp;&nbsp; &nbsp;&nbsp;
                <input type="button" name="Submit623" value="下一步" onClick="javascript:CheckNext();">
              </div></td>
          </tr>
        </form>
      </table>