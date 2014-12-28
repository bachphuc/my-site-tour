

<p class="summary_title">{phrase var='waytime.thank_you_for_having_spent_1_year_on_our_website_br_you_have_just_unlocked_the_w_time_capsule_now'}</p>

<div id="waytime_error" style="display: none;" class="error_message">{phrase var='waytime.please_fill_all_questions'}</div>

<form action="" method="POST" onsubmit="$(this).ajaxCall('waytime.unlock');return false;">
    <div class="waytime_unlock">

        <table class="tb_unlock" style="width: 100%;">
            <thead>
                <tr style="text-align: center;">
                    <td style="width: 40%;">1 year ago...</td>
                    <td>Did the website help you to better yourself?</td>
                </tr>
            </thead>
            <tbody>
                {foreach from=$aSummarys item=aQuestion name=index key=key}
                <tr>
                    <td>
                        <p>{$phpfox.iteration.index}. {$aQuestion.title}</p>
                        <p>{$aQuestion.answer}</p>
                        <p>{$aQuestion.note}</p>
                    </td>
                    <td style="text-align: center;">
                        <input name="question[{$aQuestion.question_id}]" type="radio" value="1" id="question_yes_{$aQuestion.question_id}"> <label for="question_yes_{$aQuestion.question_id}">YES</label>
                        <input name="question[{$aQuestion.question_id}]" type="radio" value="0" id="question_no_{$aQuestion.question_id}"> <label for="question_no_{$aQuestion.question_id}" >NO</label>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>

    </div>
    <div class="clear"></div>
    <div class="js_box_close" style="display: block;">
        <span class="box_controll_left">
            <a type="button" class="button" onclick="$Core.waytime.exit(this);return false;">CLOSE</a>
        </span>
        <span class="box_controll_right">
            <a type="button" class="button" onclick="$Core.waytime.unlock(this);return false;">SAVE</a>
        </span>
    </div>
</form>