{{* Frame template for displaying admission data *}}
{{* Used by  *}}
{{* Elpidio Latorilla 2004-06-07 *}}

<style>
    .presptionSelect{
        width: 80px;
    }
</style>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
    <tbody>
        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" width="100%" border="1" class="table table-bordered table-condensed">
                    <tr valign="top">
                        <td>
                            {{if $notDiagnosed}}
                                {{include file="registration_admission/common_notdiagnonized.tpl"}}
                            {{else}}
                                {{include file="registration_admission/basic_data_in_line.tpl"}}
                                {{if $bShowNoRecord}}
                                {{include file="registration_admission/common_norecord.tpl"}}
                                {{/if}}
                                {{$sOptionBlock}}
                            {{/if}}

                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td valign="top">
                {{$sBottomControls}} {{$pbPersonData}} {{$pbAdmitData}} {{$pbMakeBarcode}} {{$pbKMakeWristBands}} {{$pbBottomClose}}
            </td>
        </tr>

        <tr>
            <td>
                &nbsp;
                <br>
                {{$sAdmitLink}}
                <br>
                {{$sSearchLink}}
                <br>
                {{$sArchiveLink}}
            </td>
        </tr>

    </tbody>
</table>
