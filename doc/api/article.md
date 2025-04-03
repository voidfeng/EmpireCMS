# 信息操作

## 增加信息

```js
fetch("/e/DoInfo/ecms.php", {
  // body 为 form-data 格式
  body: `
    enews: MAddInfo
    classid: 2
    id: 0
    filepass: 1743165247 // 文件临时id
    mid: 9
    title: "标题"
    ftitle: "副标题"
    titlepicfile: 封面图片
    smalltext: 简介
    writer: 作者
    befrom: 来源
    newstext: 正文
    addnews: 提交
  `
  method: "POST",
  mode: "cors",
  credentials: "include",
});
```

// 返回数据包括：
`<b>信息提交成功</b>`;

````

## 修改信息

```js
fetch("/e/DoInfo/ecms.php", {
  // body 为 form-data 格式
  body: `
    enews: MEditInfo
    classid: 2
    id: 2
    filepass: 2
    mid: 9
    title: 标题
    ftitle: 副标题
    titlepicfile: 封面图片
    smalltext: 简介
    writer: 作者
    befrom: 来源
    newstext: 正文
    addnews: 提交
  `,
  method: "POST",
});
````
