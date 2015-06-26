<?php
$data['number'];
$data['name'];
$data['period'];

//todo real doc/excel data
//$data['price'];
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Stage price</title>
    <style>
        /*-------------------------------- Reset --------------------------------*/
        html, body, div, span, h1, h2, h3, h4, h5,
        h6, p, a, em, img, strong, b, u, i, dl, dt,
        dd, ol, ul, li, fieldset, form, label, legend, table, tbody, tfoot, thead, tr, th, td {
            margin: 0;
            padding: 0;
            border: 0;
            vertical-align: baseline;
        }

        body, html {
            /*height: 100%;*/
            /*when height=100% have one more blank page*/
            /*todo get more info about html to pdf printing*/
        }

        img, fieldset, a img {
            border: none;
        }

        input[type="submit"],
        button {
            cursor: pointer;
        }

        textarea {
            overflow: auto
        }

        input, button {
            margin: 0;
            padding: 0;
            border: 0;
        }

        input, textarea, select, button,
        h1, h2, h3, h4, h5, h6, a, span, a:focus {
            outline: none;
        }

        ul, ol {
            list-style-type: none;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }

        /*-------------------------------- Common --------------------------------*/
        html, body {
            width: 100%;
            /*width: 800px;    */
            /*todo get more info about html to pdf printing*/
        }

        b {
            font-weight: 600;
            color: #000;
        }

        /*-------------------------------- Page styles --------------------------------*/
        /* Title block */
        .title-b {
            font-family: Arial, sans-serif;
            padding: 2px 0 3px 80px;
            /*background: url(../img/pdf-tpl/block-mark.gif) repeat-y;*/
            background: url(data:image/gif;base64,R0lGODdhPQABAPAAAI0vSAAAACH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QjEzRDhGMzMxMjk4MTFFNUI5RUFEN0MyNTc0MTQ4NUMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QjEzRDhGMzQxMjk4MTFFNUI5RUFEN0MyNTc0MTQ4NUMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpCMTNEOEYzMTEyOTgxMUU1QjlFQUQ3QzI1NzQxNDg1QyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpCMTNEOEYzMjEyOTgxMUU1QjlFQUQ3QzI1NzQxNDg1QyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcGBQQDAgEAACwAAAAAPQABAEACBoSPqcvtWgA7) repeat-y;
        }

        .title-b .title {
            font-size: 46px;
            font-weight: normal;
            color: #8d2f48;
        }

        .title-b .extra {
            font-size: 18px;
        }

        /* Prices */
        .tPrice {
            width: 720px;
            margin: 25px auto 0;
        }

        .tPrice th,
        .tPrice td {
            border: 1px solid #CCC;
            padding: 5px;
            vertical-align: middle;
        }

        .tPrice th {
            font-weight: 600;
        }

        .tPrice .stage,
        .tPrice .tooth,
            /*.tPrice .procedure,*/
        .tPrice .quantity,
        .tPrice .price,
        .tPrice .cost {
            text-align: center;
        }

        .tPrice .cost,
        .tPrice .price {
            width: 75px;
        }

        .tPrice .total,
        .tPrice .discount,
        .tPrice .total-with-disc {
            text-align: right;
        }

        .tPrice .discount,
        .tPrice .total-with-disc {
            border: none;
        }

        /* Signatures (footer) */
        .signatures {
            width: 700px;
            margin: 25px auto 0;
        }

        .signatures .left,
        .signatures .right {
            width: 300px;
        }

        .signatures .left {
            float: left;
        }

        .signatures .right {
            float: right;
        }

        .signatures span {
            color: #8d2f48;
            font-weight: 600;
        }

        .signatures .dots {
            margin-left: 10px;
            font-size: 10px;
            color: #777777;
        }

        /*-------------------------------- Layout --------------------------------*/
        .page {
            width: 795px;
            height: 100%;
            margin: 60px auto 0;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
<div class="page">
    <!-- Title block -->
    <div class="title-b">
        <div class="title"><?= $data['number'] ?> этап – <?= $data['name'] ?></div>
        <!--<p class="extra">Количество посещений: {{stage_visits}}</p>-->

        <p class="extra">Общая длительность этапа лечения: <?= $data['period'] ?></p>
    </div>
    <!-- /Title block -->

    <!-- Price table -->
    <table class="tPrice">
        <!-- table header -->
        <thead>
        <tr>
            <th class="stage">Этап</th>
            <th class="tooth">Зуб</th>
            <th class="procedure">Название процедуры</th>
            <th class="quantity">Кол-во</th>
            <th class="price">Цена</th>
            <th class="cost">Сумма</th>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <th class="stage"></th>
            <th class="tooth">Tooth</th>
            <th class="procedure">Procedures</th>
            <th class="quantity">Quantity</th>
            <th class="price">Price</th>
            <th class="cost">Cost</th>
        </tr>
        </thead>
        <!-- /table header -->

        <!-- table data -->
        <tbody>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Зняття допоміжного відбитка/One- layer impression</td>
            <td class="quantity">1</td>
            <td class="price">240,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Инфiльтрацiйна анестезія/Anesthesia</td>
            <td class="quantity">4</td>
            <td class="price">960,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">14</td>
            <td class="procedure">Безметалова керамічна короноково-коренева вкладка/All-ceramic post</td>
            <td class="quantity">1</td>
            <td class="price">2 760,00</td>
            <td class="cost">2 760,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Зняття допоміжного відбитка/One- layer impression</td>
            <td class="quantity">1</td>
            <td class="price">240,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Инфiльтрацiйна анестезія/Anesthesia</td>
            <td class="quantity">4</td>
            <td class="price">960,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">14</td>
            <td class="procedure">Безметалова керамічна короноково-коренева вкладка/All-ceramic post</td>
            <td class="quantity">1</td>
            <td class="price">2 760,00</td>
            <td class="cost">2 760,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Зняття допоміжного відбитка/One- layer impression</td>
            <td class="quantity">1</td>
            <td class="price">240,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Инфiльтрацiйна анестезія/Anesthesia</td>
            <td class="quantity">4</td>
            <td class="price">960,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">14</td>
            <td class="procedure">Безметалова керамічна короноково-коренева вкладка/All-ceramic post</td>
            <td class="quantity">1</td>
            <td class="price">2 760,00</td>
            <td class="cost">2 760,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Зняття допоміжного відбитка/One- layer impression</td>
            <td class="quantity">1</td>
            <td class="price">240,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Инфiльтрацiйна анестезія/Anesthesia</td>
            <td class="quantity">4</td>
            <td class="price">960,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">14</td>
            <td class="procedure">Безметалова керамічна короноково-коренева вкладка/All-ceramic post</td>
            <td class="quantity">1</td>
            <td class="price">2 760,00</td>
            <td class="cost">2 760,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Зняття допоміжного відбитка/One- layer impression</td>
            <td class="quantity">1</td>
            <td class="price">240,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">99</td>
            <td class="procedure">Инфiльтрацiйна анестезія/Anesthesia</td>
            <td class="quantity">4</td>
            <td class="price">960,00</td>
            <td class="cost">240,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">14</td>
            <td class="procedure">Безметалова керамічна короноково-коренева вкладка/All-ceramic post</td>
            <td class="quantity">1</td>
            <td class="price">2 760,00</td>
            <td class="cost">2 760,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">14</td>
            <td class="procedure">Безметалова керамічна короноково-коренева вкладка/All-ceramic post</td>
            <td class="quantity">1</td>
            <td class="price">2 760,00</td>
            <td class="cost">2 760,00</td>
        </tr>
        <tr>
            <td></td>
            <td class="tooth">14</td>
            <td class="procedure">Безметалова керамічна короноково-коренева вкладка/All-ceramic post</td>
            <td class="quantity">1</td>
            <td class="price">2 760,00</td>
            <td class="cost">2 760,00</td>
        </tr>
        </tbody>
        <!-- /table data -->

        <!-- table footer -->
        <tfoot>
        <tr>
            <th class="total" colspan="5">Вместе, грн. / Total, UAH / :</th>
            <th class="cost">52 560,00</th>
        </tr>
        <tr>
            <th class="discount" colspan="5">Скидка / Discount / :</th>
            <th class="cost">2 630,00</th>
        </tr>
        <tr>
            <th class="total-with-disc" colspan="5">Всього / Total / :</th>
            <th class="cost">495 930,00</th>
        </tr>
        </tfoot>
        <!-- /table footer -->
    </table>
    <!-- /Price table -->

    <!-- Signatures -->
    <div class="signatures">
        <div class="left">
            <span>Врач</span>
            <span class="dots">..........................................................................</span>
        </div>
        <div class="right">
            <span>Пациент</span>
            <span class="dots">..........................................................................</span>
        </div>
        <div class="clearfix"></div>
    </div>
    <!-- /Signatures -->
</div>
</body>
</html>