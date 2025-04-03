# 用户相关操作

传输格式: "content-type": "application/x-www-form-urlencoded"

## 登录

```js
fetch("https://next.0122.vip/e/member/doaction.php", {
  body: `enews=login&ecmsfrom=9&username=${用户名称}&password=${登录密码}&Submit=${encodeURIComponent(
    "登录"
  )}`,
  method: "POST",
});
// 返回数据包括：
`<b>登录成功!</b>`;
```

## 退出登录

```js
fetch("/e/member/doaction.php?enews=exit&ecmsfrom=9", {
  body: null,
  method: "GET",
});
// 返回数据包括：
`<b>退出系统成功！</b>`;
```
