

<p class="profile_summary_title">{phrase var='waytime.thank_you_for_having_spent_on_our_website_you_have_just_unlocked_the_w_time_capsule_here_is_your'}</p>


<div id="summary_table">

    <table style="width: 100%;">
        <thead>
            <tr>
                <td>Question</td>
                <td>Answer</td>
                <td>Note</td>
                <td colspan="2" style="text-align: center;">Helpful for you</td>
            </tr>
        </thead>
        <tbody>
            {foreach from=$aSummarys item=aQuestion name=index key=key}
            <tr>
                <td>
                    <p>{$phpfox.iteration.index}. {$aQuestion.title}</p>
                </td>
                <td>
                    <p>{$aQuestion.answer}</p>
                </td>
                <td>
                    <p style="color: #444;">{$aQuestion.note}</p>
                </td>
                <td style="text-align: center;">
                    {if $aQuestion.is_helpful == 1}
                    <img src="{param var='core.path'}module/waytime/static/images/no.png">
                    {/if}
                </td>
                <td>
                    {if $aQuestion.is_helpful == 2}
                    <img src="{param var='core.path'}module/waytime/static/images/ticked.png">
                    {/if}
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<div class="clear"></div>
<div class="js_box_close" style="display: block;">
    <a onclick="$Core.waytime.exit(this);return false;" href="#">CLOSE</a>
</div>