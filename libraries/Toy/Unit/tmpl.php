<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title></title>
    <style>
        table {
            background-color: #000000;
        }

        td, th {
            background-color: #ffffff;
        }

        td {
            padding: 5px;
        }

        .red td {
            background-color: #ff7777;
        }

        td.line, td.no {
            text-align: center;
        }

        td.method, td.classname, td.file, td.message {
        }
    </style>
</head>
<body>
<p>测试完成，共<?php echo count($this->_testSuccess) + count($this->_testFailures) ?>
    成功 <?php echo count($this->_testSuccess); ?> 失败 <?php echo count($this->_testFailures) ?></p>
<table cellpadding="1" cellspacing="1">
    <thead>
    <th style="width: 40px"></th>
    <th style="width: 40px">行号</th>
    <th style="width: 150px">方法</th>
    <th style="width: 200px">类</th>
    <th style="width: 450px">文件</th>
    <th>描述</th>
    </thead>
    <tbody>
    <?php foreach ($this->_testFailures as $i => $f): ?>
        <tr class="red">
            <td class="no"><?php echo $i + 1; ?></td>
            <td class="line"><?php echo $f['line']; ?></td>
            <td class="method"><?php echo $f['method']; ?></td>
            <td class="classname"><?php echo $f['class']; ?></td>
            <td class="file"><?php echo $f['file']; ?></td>
            <td class="message"><?php echo $f['message']; ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
    <table>
</body>
</html>
