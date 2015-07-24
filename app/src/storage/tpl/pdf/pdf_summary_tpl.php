<?php
/**
 * @var \UTI\Lib\Data $data
 */
// Max period length is 109 for string like below:
// '2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2 2';
// With string where are no white spaces this value might be lower
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Summary</title>
    <style>
        body {
            font-family: 'pt-serif', Arial, sans-serif;
        }

        /* page */
        .page {
            padding: 0;
            margin: 0;

            /*debug*/
            /*background: #CCC;*/
        }

        .page p {
            margin: 0;
            padding: 0;
        }

        /* header */
        .header {
            margin: 0;
            padding: 2px 0 3px 80px;
            background: url(data:image/gif;base64,R0lGODdhPQABAPAAAI0vSAAAACH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QjEzRDhGMzMxMjk4MTFFNUI5RUFEN0MyNTc0MTQ4NUMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QjEzRDhGMzQxMjk4MTFFNUI5RUFEN0MyNTc0MTQ4NUMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpCMTNEOEYzMTEyOTgxMUU1QjlFQUQ3QzI1NzQxNDg1QyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpCMTNEOEYzMjEyOTgxMUU1QjlFQUQ3QzI1NzQxNDg1QyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcGBQQDAgEAACwAAAAAPQABAEACBoSPqcvtWgA7) repeat-y;
        }

        .header__title {
            font-family: 'helveticaneuecyr-thin', Helvetica, Arial, sans-serif;
            font-size: 48px;
            font-weight: bold;
            color: #8d2f48;
        }

        .header__subtitle {
            font-family: 'arial-narrow', Arial, sans-serif;
            font-size: 20px;
            font-weight: normal;
        }

        .header__light {
            font-family: 'helveticaneuecyr-light', Helvetica, Arial, sans-serif;
        }

        /* customer */
        .customer {
            margin: 30px 0 20px 80px;
            padding: 0;
            font-size: 16px;
        }

        .customer__photo {
            width: 140px;
            height: 200px;
            float: left;
            box-shadow: 5px 5px 5px #d6d6d6;
        }

        .customer__content {
            float: left;
            width: 485px;
            margin-left: 50px;

            /*debug*/
            /*border: 1px solid #000;*/
        }

        .customer__name {
            font-size: 18px;
            font-weight: bold;
        }

        .customer p {
            margin-bottom: 10px;
        }

        /* \customer */

        /* stage */
        .stage {
            width: 705px;
            margin: 28px 0 0 70px;
        }

        .stage__item {
            width: 340px;
            margin-bottom: 20px;
        }

        .stage__figure {
            width: 90px;
            height: 90px;
            /*background: url(../img/pdf-tpl/stage-fig.png) no-repeat;*/
            background: url(data:image/png;base64,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) no-repeat;
        }

        .stage__number {
            font-family: 'helveticaneuecyr-thin', Helvetica, Arial, sans-serif;
            font-size: 50px;
            font-weight: normal;
            color: #8d2f48;
        }

        .stage__content {
            margin: 15px 0 0 10px;
            padding: 0 0 10px 10px;
            font-family: 'arial-narrow', Arial, sans-serif;
            font-size: 16px;
            font-weight: 100;
            vertical-align: middle;
        }

        .stage__name {
            font-weight: bold;
            font-size: 20px;
        }

        .stage__period {
            font-size: 18px;
        }

        .stage p {
            margin-top: 7px;
        }

        /* clear */
        .clear {
            clear: both;
            margin: 0;
            padding: 0;
        }

        /* emit */
        .emit {
            color: #8d2f48;
            font-weight: bold;
        }

        /* table */
        .table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }

        .table__cell {
            padding: 0;
        }

        /* table mod, for stage number position */
        .table_number_center {
            width: 80px;

            /*debug*/
            /*border: 1px solid #00F;*/
        }

        .table__cell_number_center {
            height: 80px;
            text-align: center;
            vertical-align: middle;
            padding-bottom: 5px;
        }

        /* table mod, for each stage*/
        .table__cell_stage_item {
            padding-right: 25px;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="header__title">Уважаемый</div>
    </div>

    <div class="customer">
        <div class="customer__photo">
            <img width="140" height="200" src="<?= $data('doctor.photo') ?>" title="<?= $data->esc('doctor.name') ?>">
        </div>

        <div class="customer__content">
            <p><span class="customer__name"><?= $data->esc('customer.name') ?></span>,</p>

            <p>Как мы обсуждали с Вами на консультации, я подготовил
                детальный план лечения, в котором расписаны подробно
                все этапы лечения, виды работ, сроки, а также профессиональные
                термины и возможные вопросы, которые могут
                у Вас возникнуть.</p>

            <p>По любым вопросам или пожеланиям Вы можете обращаться ко
                мне по тел. ... или нашему клиент менеджеру
                Анастасии Гутянской по тел. ...</p>

            <br><br>

            <p><em>С уважением, <?= $data->esc('doctor.name') ?>,
                    врач-стоматолог, клиника «Порцелян»</em></p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="header">
        <div class="header__title">Этапы лечения</div>
        <p class="header__subtitle"><strong>Общая длительность лечения:</strong> <span class="header__light">(вручную печатаем)</span></p>
    </div>

    <table class="table stage">
        <?php
        $num = 1;
        $size = $data('stages.number');
        $startTr = true;
        $endTr = false;
        while ($num <= $size):
            if ($startTr): ?>
                <tr>
            <?php endif; ?>
            <td class="table__cell table__cell_stage_item">
                <table class="table stage__item">
                    <tr>
                        <td class="stage__figure table__cell">
                            <table class="table table_number_center">
                                <tr>
                                    <td class="table__cell table__cell_number_center stage__number">
                                        <?= $data('number' . $num) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="table__cell stage__content">
                            <div class="stage__name"><span class="emit"><?= $data('number' . $num) ?> этап</span>
                                – <?= $data('name' . $num) ?></div>
                            <!--<p>(вручную печатаем, что входит в этап)</p>-->
                            <p class="stage__period"><span class="emit">длительность</span> –
                                <?= $data->esc('period' . $num)->cut(null, 25) ?></p>

                        </td>
                    </tr>
                </table>
            </td>
            <?php if ($size === 1 || $endTr): ?>
            </tr>
        <?php endif; ?>
            <?php
            ++$num;
            if ($num % 2 === 0) {
                $startTr = false;
                $endTr = true;
            } else {
                $startTr = true;
                $endTr = false;
            }
        endwhile; ?>
    </table>
</div>
</body>
</html>